<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
{
    $categories = Category::all();
    $products = Product::where('is_active', true)->with('category')->get();
    return view('frontend.home', compact('categories', 'products'));
}
}
