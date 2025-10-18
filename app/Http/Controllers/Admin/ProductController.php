<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
 public function index()
{
    $products = Product::with('category')->latest()->get();
    return view('admin.products.index', compact('products'));
}


    public function create()
    {
        $categories = Category::all();
        $product = null;
        return view('admin.products.create', compact('categories', 'product'));
    }

    
public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'description' => 'required',
        'whatsapp_link' => 'sometimes|url',
        'quantity' => 'required|integer|min:0',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'images' => 'nullable|array',
        'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    // Simpan gambar utama ke storage
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    // Simpan data produk ke database
    $product = Product::create([
        'category_id' => $request->category_id,
        'name' => $request->name,
        'slug' => Str::slug($request->name),
        'description' => $request->description,
        'price' => $request->price,
        'whatsapp_link' => $request->whatsapp_link,
        'image_url' => $imagePath ? Storage::url($imagePath) : null, // simpan path URL publik
        'is_active' => $request->has('is_active'),
        'quantity' => $request->quantity,
    ]);

    // Simpan galeri gambar (bisa lebih dari satu)
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $i => $file) {
            $path = $file->store('product_images', 'public');
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => Storage::url($path), // simpan path ke database
                'position' => $i,
            ]);
        }
    }

    return redirect()->route('admin.products.index')
        ->with('success', 'Produk berhasil ditambahkan.');
}

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'required',
            'whatsapp_link' => 'required|url',
            'image_url' => 'nullable|url',
            'images' => 'nullable|array',
            'images.*' => 'nullable|url',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'whatsapp_link' => $request->whatsapp_link,
            'image_url' => $request->image_url,
            'is_active' => $request->has('is_active'),
        ]);

        // Hapus gambar lama (opsional: bisa simpan jika ingin edit)
        $product->images()->delete();

        if ($request->filled('images')) {
            foreach ($request->images as $i => $url) {
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => $url,
                        'position' => $i,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->images()->delete();
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleActive(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();
        return back()->with('success', 'Status produk diperbarui.');
    }
}   