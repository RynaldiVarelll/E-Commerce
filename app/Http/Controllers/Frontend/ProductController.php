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
        // 🚨 KEAMANAN: Admin tidak boleh belanja atau melihat produk toko lain di frontend
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Admin hanya diperbolehkan mengelola produk, bukan berbelanja.');
        }

        $query = Product::query()->with(['category', 'user', 'reviews']);
        $shops = collect(); // Default empty collection

        // Jika pencarian text
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('name', 'like', '%' . $searchTerm . '%');

            // Cari toko yang namanya mirip (Sellers only)
            $shops = \App\Models\User::where('role', 'admin')
                ->where('name', 'like', '%' . $searchTerm . '%')
                ->get();
        }

    // Jika kategori dipilih
    if ($request->has('category') && $request->category) {
        $query->where('category_id', $request->category);
    }

    $products = $query->where('is_active', true)->latest()->get();
    $categories = Category::all();

        return view('frontend.home', compact('products', 'categories', 'shops'));
}


    /**
     * Menampilkan detail satu produk tertentu.
     */
    public function show(Product $product)
    {
        // 🔥 OPTIMASI: Eager loading relasi untuk performa & rating yang reaktif
        // Menambahkan user.storeReviews agar rating toko bisa dibandingkan di halaman produk
        $product->load(['category', 'user.storeReviews', 'reviews.user', 'images']);

        return view('product.show', compact('product'));
    }

}
