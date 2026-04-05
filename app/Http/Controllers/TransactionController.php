<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItem; 
use App\Models\Product;
use App\Models\Cart;
use App\Models\ShippingMethod;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk mengelola transaksi, checkout, dan laporan keuangan.
 */
class TransactionController extends Controller
{
    /**
     * Memproses alur checkout belanja.
     * 1. Validasi stok produk.
     * 2. Pengelompokan produk berdasarkan penjual (seller).
     * 3. Pembuatan transaksi untuk masing-masing penjual.
     * 4. Pengurangan stok produk.
     * 5. Pengosongan keranjang belanja.
     */
    public function checkout(Request $request)
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Admin tidak diperbolehkan melakukan transaksi pembelian.');
        }

        if (empty(auth()->user()->address)) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi alamat rumah Anda terlebih dahulu.');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
        ]);

        DB::beginTransaction();

        try {
            // 🔥 Ambil metode pengiriman
            $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);

            // 🔥 Kelompokkan produk berdasarkan toko (seller_id)
            $groupedItems = [];
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);

                if ($product->quantity < $itemData['quantity']) {
                    return back()->withErrors([
                        'quantity' => "Stok {$product->name} tidak mencukupi."
                    ]);
                }

                $groupedItems[$product->user_id][] = [
                    'product' => $product,
                    'quantity' => $itemData['quantity']
                ];
            }

            // 🔥 Buat transaksi per toko
            $transactionIds = [];
            foreach ($groupedItems as $sellerId => $items) {
                $subtotal = 0;
                foreach ($items as $item) {
                    $subtotal += $item['product']->price * $item['quantity'];
                }

                $grandTotal = $subtotal + $shippingMethod->cost;

                $transaction = Transaction::create([
                    'user_id' => auth()->id(),
                    'seller_id' => $sellerId,
                    'shipping_method_id' => $shippingMethod->id,
                    'total_amount' => $grandTotal,
                    'status' => 'pending',
                    'payment_method' => 'manual', 
                ]);

                // 🔥 Simpan detail item transaksi & kurangi stok produk
                foreach ($items as $item) {
                    $product = $item['product'];
                    $product->quantity -= $item['quantity'];
                    $product->save();

                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);
                }
                $transactionIds[] = $transaction->id;
            }

            // 🔥 Kosongkan cart belanja setelah checkout sukses
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            // Redirect ke halaman sukses dengan membawa ID transaksi
            return redirect()
                ->route('checkout.success')
                ->with('success', 'Transaksi berhasil diproses! Pesanan yang berbeda toko telah dipisahkan secara otomatis.')
                ->with('transactionIds', $transactionIds);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());

            return back()->withErrors([
                'system' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ]);
        }
    }

    /**
     * Menampilkan halaman sukses setelah checkout.
     */
    public function success()
    {
        $transactionIds = session('transactionIds');
        if (!$transactionIds) {
            return redirect()->route('orders.index');
        }

        $transactions = Transaction::whereIn('id', $transactionIds)->get();
        if ($transactions->isEmpty() || $transactions->first()->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('frontend.checkout-success', compact('transactions'));
    }

    /**
     * Menampilkan halaman formulir checkout.
     */
    public function checkoutPage()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (empty(auth()->user()->address)) {
            return redirect()->route('profile.edit')->with('error', 'Silakan lengkapi alamat rumah Anda terlebih dahulu sebelum melakukan checkout.');
        }

        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $shippingMethods = ShippingMethod::where('is_active', true)->get();

        return view('frontend.checkout', compact('cartItems', 'shippingMethods'));
    }

    /**
     * Membuat dan mengunduh file PDF invoice untuk transaksi tertentu.
     */
    public function printInvoice(Transaction $transaction)
    {
        // Izinkan jika dia pembeli pengguna, admin/seller pemilik transaksi, atau super admin
        if ($transaction->user_id !== auth()->id() && !auth()->user()->isSuperAdmin() && $transaction->seller_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        $transaction->load('user', 'seller', 'items.product', 'shippingMethod');

        // Menggunakan view admin.transactions.invoice
        $pdf = Pdf::loadView('admin.transactions.invoice', compact('transaction'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("Invoice-{$transaction->invoice_code}.pdf");
    }

    /**
     * Membuat dan mengunduh laporan transaksi dalam rentang waktu tertentu (PDF).
     */
    public function generateReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $query = Transaction::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc');

        if (!auth()->user()->isSuperAdmin()) {
            $query->where('seller_id', auth()->id());
        }

        $transactions = $query->get();

        $totalRevenue = $transactions->sum('total_amount');

        $pdf = Pdf::loadView(
            'admin.transactions.report',
            compact('transactions', 'totalRevenue', 'startDate', 'endDate')
        )->setPaper('A4', 'portrait');

        return $pdf->download("laporan-transaksi-$startDate-$endDate.pdf");
    }

    /**
     * Menghapus data transaksi (hanya oleh Super Admin atau Seller pemilik).
     */
    public function destroy(Transaction $transaction)
    {
        if (!auth()->user()->isSuperAdmin() && $transaction->seller_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Pastikan model Transaction memiliki method deleteWithItems()
        $transaction->deleteWithItems();

        return redirect()->back()
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}