<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class ShopController extends Controller
{
    public function show(Request $request, $id)
    {
        // 🚨 KEAMANAN: Admin tidak boleh belanja atau melihat produk toko lain di frontend
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Akses ditolak. Admin hanya diperbolehkan mengelola produk, bukan berbelanja.');
        }

        $shop = User::findOrFail($id);
        
        $query = Product::query()->with(['category', 'user', 'reviews'])->where('user_id', $shop->id)->where('is_active', true);

        // Jika pencarian text
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();

        return view('frontend.shop.show', compact('shop', 'products'));
    }
}
