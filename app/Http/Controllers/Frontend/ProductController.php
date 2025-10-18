<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
class ProductController extends Controller
{
 public function index()
    {
        $products = Product::with('category')->get();
        $categories = Category::all();
        return view('frontend.home', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show($id)
{
    $product = Product::findOrFail($id);
    return view('product.show', compact('product'));
}

}
