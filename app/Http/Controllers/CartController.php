<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', auth()->id())
                        ->where('product_id', $request->product_id)
                        ->first();

        $product = Product::find($request->product_id);
        if ($product->quantity <= 0) {
            return back()->with('error', 'Produk ini sedang habis dan tidak dapat ditambahkan ke keranjang.');
        }

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->total_price = $cartItem->quantity * $product->price;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => auth()->id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_price' => $request->quantity * $product->price,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function viewCart()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        return view('frontend.cart', compact('cartItems'));
    }

    // 🟢 Ubah jumlah item di keranjang
    public function updateQuantity(Request $request, Cart $cart)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $product = $cart->product;

        if ($request->quantity > $product->quantity) {
            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart->quantity = $request->quantity;
        $cart->total_price = $product->price * $request->quantity;
        $cart->save();

        return back()->with('success', 'Jumlah produk berhasil diperbarui.');
    }

    // 🗑️ Hapus item dari keranjang
    public function removeItem(Cart $cart)
    {
        $cart->delete();
        return back()->with('success', 'Produk telah dihapus dari keranjang.');
    }
}
