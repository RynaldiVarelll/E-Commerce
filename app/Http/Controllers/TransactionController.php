<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionItems;
use App\Models\Product;
use App\Models\Cart;

    
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

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'total_amount' => $totalAmount,
            'status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);

            if ($product->quantity < $item['quantity']) {
                return back()->withErrors([
                    'quantity' => "Stok untuk {$product->name} tidak mencukupi.",
                ]);
            }

            $product->quantity -= $item['quantity'];
            $product->save();

            // Simpan item transaksi
            TransactionItems::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);
        }
        Cart::where('user_id', auth()->id())->delete();

        return redirect()->route('checkout.success', ['id' => $transaction->id]);
    }

    public function checkoutPage()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('product')
            ->get();

        return view('frontend.checkout', compact('cartItems'));
    }




}
