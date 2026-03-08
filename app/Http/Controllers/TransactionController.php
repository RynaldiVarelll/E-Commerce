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

class TransactionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CHECKOUT PROCESS
    |--------------------------------------------------------------------------
    */
    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_method_id' => 'required|exists:shipping_methods,id',
        ]);

        DB::beginTransaction();

        try {
            $subtotal = 0;

            // 🔥 Hitung subtotal & cek stok dulu
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    return back()->withErrors([
                        'quantity' => "Stok {$product->name} tidak mencukupi."
                    ]);
                }

                $subtotal += $product->price * $item['quantity'];
            }

            // 🔥 Ambil shipping
            $shippingMethod = ShippingMethod::findOrFail($request->shipping_method_id);
            $grandTotal = $subtotal + $shippingMethod->cost;

            // 🔥 Buat transaksi (invoice auto dari model)
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'shipping_method_id' => $shippingMethod->id,
                'total_amount' => $grandTotal,
                'status' => 'pending',
                'payment_method' => 'manual', 
            ]);

            // 🔥 Simpan items & kurangi stok
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                $product->quantity -= $item['quantity'];
                $product->save();

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);
            }

            // 🔥 Kosongkan cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            // Redirect ke halaman sukses dengan membawa ID transaksi
            return redirect()
                ->route('checkout.success', $transaction->id)
                ->with('success', 'Transaksi berhasil diproses!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout Error: ' . $e->getMessage());

            return back()->withErrors([
                'system' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT SUCCESS PAGE (MODIFIKASI BARU)
    |--------------------------------------------------------------------------
    */
    public function success(Transaction $transaction)
    {
        // Pastikan hanya pemilik transaksi yang bisa melihat halaman sukses ini
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        // Load relasi agar data di blade checkout-success tidak error
        $transaction->load('items.product', 'shippingMethod');

        return view('frontend.checkout-success', compact('transaction'));
    }

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT PAGE
    |--------------------------------------------------------------------------
    */
    public function checkoutPage()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        $shippingMethods = ShippingMethod::where('is_active', true)->get();

        return view('frontend.checkout', compact('cartItems', 'shippingMethods'));
    }

    /*
    |--------------------------------------------------------------------------
    | PRINT INVOICE (USER & ADMIN)
    |--------------------------------------------------------------------------
    */
    public function printInvoice(Transaction $transaction)
    {
        // Izinkan jika dia pemilik OR dia adalah Admin
        if ($transaction->user_id !== auth()->id() && !auth()->user()->is_admin) {
            abort(403, 'Anda tidak memiliki akses ke invoice ini.');
        }

        $transaction->load('user', 'items.product', 'shippingMethod');

        // Menggunakan view admin.transactions.invoice yang sudah kita rapikan tadi
        $pdf = Pdf::loadView('admin.transactions.invoice', compact('transaction'))
            ->setPaper('A4', 'portrait');

        return $pdf->download("Invoice-{$transaction->invoice_code}.pdf");
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE REPORT (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function generateReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $transactions = Transaction::with('user')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $transactions->sum('total_amount');

        $pdf = Pdf::loadView(
            'admin.transactions.report',
            compact('transactions', 'totalRevenue', 'startDate', 'endDate')
        )->setPaper('A4', 'portrait');

        return $pdf->download("laporan-transaksi-$startDate-$endDate.pdf");
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE TRANSACTION
    |--------------------------------------------------------------------------
    */
    public function destroy(Transaction $transaction)
    {
        // Pastikan model Transaction memiliki method deleteWithItems()
        $transaction->deleteWithItems();

        return redirect()->back()
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}