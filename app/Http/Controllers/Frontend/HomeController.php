<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::all();
        // Eager load category dan reviews agar rating reaktif
        $products = \App\Models\Product::where('is_active', true)
            ->with(['category', 'reviews'])
            ->latest()
            ->get();
            
        return view('frontend.home', compact('categories', 'products'));
    }
}
