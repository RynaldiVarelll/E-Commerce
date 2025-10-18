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

    if ($cartItem) {
        $cartItem->quantity += $request->quantity;
        $cartItem->total_price += $request->quantity * $product->price;
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
}
