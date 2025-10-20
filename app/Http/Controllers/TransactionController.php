<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItems;
use App\Models\Product;
use App\Models\Cart;
use Barryvdh\DomPDF\Facade\Pdf; // pastikan sudah install barryvdh/laravel-dompdf
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class TransactionController extends Controller
{
    public function checkout(Request $request)
{
    $request->validate([
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $totalAmount = 0;
    foreach ($request->items as $item) {
        $product = Product::find($item['product_id']);
        $totalAmount += $product->price * $item['quantity'];
    }

    // 🔒 Buat invoice code yang benar-benar unik
    $invoiceCode = null;
    $maxAttempts = 10;

    for ($i = 0; $i < $maxAttempts; $i++) {
        // Tambahkan microtime + random string biar benar-benar unik
        $candidate = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(3)) . '-' . substr(md5(microtime(true) . Str::random(8)), 0, 5);

        if (!Transaction::where('invoice_code', $candidate)->exists()) {
            $invoiceCode = $candidate;
            break;
        }
    }

    if (!$invoiceCode) {
        return back()->withErrors(['invoice' => 'Gagal membuat kode invoice unik. Silakan coba lagi.']);
    }

    DB::beginTransaction();
    try {
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'invoice_code' => $invoiceCode,
        ]);

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);

            if ($product->quantity < $item['quantity']) {
                DB::rollBack();
                return back()->withErrors([
                    'quantity' => "Stok untuk {$product->name} tidak mencukupi.",
                ]);
            }

            $product->quantity -= $item['quantity'];
            $product->save();

            TransactionItems::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
        }

        Cart::where('user_id', auth()->id())->delete();

        DB::commit();

        return redirect()
            ->route('checkout.success', ['id' => $transaction->id])
            ->with('success', 'Transaksi berhasil diproses!');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Checkout error: ' . $e->getMessage());
        return back()->withErrors(['system' => 'Terjadi kesalahan sistem. Silakan coba lagi.']);
    }
}

    
    public function checkoutPage()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        return view('frontend.checkout', compact('cartItems'));
    }

    public function generateReport(Request $request)
{
    $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
    $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

    $transactions = \App\Models\Transaction::with('user')
        ->whereBetween('created_at', [$startDate, $endDate])
        ->orderBy('created_at', 'desc')
        ->get();

    $totalRevenue = $transactions->sum('total_amount');

    $pdf = Pdf::loadView('admin.transactions.report', compact('transactions', 'totalRevenue', 'startDate', 'endDate'))
              ->setPaper('A4', 'portrait');

    return $pdf->download("laporan-transaksi-$startDate-$endDate.pdf");
}
    public function generateInvoice(Transaction $transaction)
{
    $this->authorize('view', $transaction); // pastikan user hanya bisa lihat miliknya sendiri

    $transaction->load('user', 'items.product');

    $pdf = Pdf::loadView('user.transactions.invoice', compact('transaction'))
              ->setPaper('A4', 'portrait');

    return $pdf->download("invoice-{$transaction->id}.pdf");
}

    public function printInvoice(Transaction $transaction)
    {
        // Pastikan hanya pemilik transaksi yang bisa melihat
        if ($transaction->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $pdf = Pdf::loadView('admin.transactions.invoice', compact('transaction'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('Invoice-' . $transaction->invoice_code . '.pdf');
    }
    public function destroy(Transaction $transaction)
    {
        // Hapus transaksi beserta item-itemnya
        $transaction->deleteRecords();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus beserta item-itemnya.');

    }

}
