<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
class ProductController extends Controller
{
 public function index(Request $request)
{
    $query = Product::query()->with(['category', 'user']);

    // Jika pencarian text
    if ($request->has('search') && $request->search != '') {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Jika kategori dipilih
    if ($request->has('category') && $request->category) {
        $query->where('category_id', $request->category);
    }

    $products = $query->where('is_active', true)->latest()->get();
    $categories = Category::all();

    return view('frontend.home', compact('products', 'categories'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function show($id)
{
    $product = Product::with('user')->findOrFail($id);
    return view('product.show', compact('product'));
}

}
