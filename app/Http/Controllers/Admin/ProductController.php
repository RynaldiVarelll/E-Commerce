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
            'whatsapp_link' => 'nullable|url',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $imageName = basename($path); // simpan cuma nama file
        }

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'whatsapp_link' => $request->whatsapp_link,
            'image_url' => $imageName,
            'is_active' => $request->has('is_active'),
            'quantity' => $request->quantity,
        ]);

        // Simpan galeri
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('product_images', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => basename($path),
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // 🔥 Ganti gambar utama
        if ($request->hasFile('image')) {

            // hapus lama
            if ($product->image_url && Storage::disk('public')->exists('products/' . $product->image_url)) {
                Storage::disk('public')->delete('products/' . $product->image_url);
            }

            $path = $request->file('image')->store('products', 'public');
            $validated['image_url'] = basename($path);
        }

        $product->update($validated);

        // 🔥 Update galeri
        if ($request->hasFile('images')) {

            foreach ($product->images as $img) {
                if (Storage::disk('public')->exists('product_images/' . $img->image_url)) {
                    Storage::disk('public')->delete('product_images/' . $img->image_url);
                }
                $img->delete();
            }

            foreach ($request->file('images') as $i => $file) {
                $path = $file->store('product_images', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => basename($path),
                    'position' => $i,
                ]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image_url && Storage::disk('public')->exists('products/' . $product->image_url)) {
            Storage::disk('public')->delete('products/' . $product->image_url);
        }

        foreach ($product->images as $img) {
            if (Storage::disk('public')->exists('product_images/' . $img->image_url)) {
                Storage::disk('public')->delete('product_images/' . $img->image_url);
            }
            $img->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }

    public function toggleActive(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();
        return back()->with('success', 'Status produk diperbarui.');
    }
}
