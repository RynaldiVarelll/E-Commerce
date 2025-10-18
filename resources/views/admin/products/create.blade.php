@extends('layouts.admin')

@section('content')
<h2 class="text-2xl font-bold mb-6">{{ $product ? 'Edit Produk' : 'Tambah Produk' }}</h2>

<form action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
    @csrf
    @if($product)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name', $product?->name) }}" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Kategori</label>
                <select name="category_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Pilih</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (old('category_id', $product?->category_id) == $cat->id) ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product?->price) }}" class="w-full border rounded px-3 py-2" min="0" step="0.01" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Link WhatsApp (lengkap)</label>
                <input type="url" name="whatsapp_link" value="{{ old('whatsapp_link', $product?->whatsapp_link) }}" class="w-full border rounded px-3 py-2" placeholder="https://wa.me/6281234567890" required>
            </div>

           <div class="mb-4">
            <label class="block text-gray-700 mb-2">Gambar Utama</label>
            <input type="file" name="image" class="w-full border rounded px-3 py-2" accept="image/*">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Galeri Gambar (bisa lebih dari satu)</label>
            <input type="file" name="images[]" class="w-full border rounded px-3 py-2" accept="image/*" multiple>
        </div>


            <div class="mb-4 flex items-center">
                <input type="checkbox" name="is_active" id="is_active" {{ (old('is_active', $product?->is_active ?? true)) ? 'checked' : '' }} class="mr-2">
                <label for="is_active">Aktif</label>
            </div>
        </div>

        <div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Deskripsi</label>
                <textarea name="description" rows="5" class="w-full border rounded px-3 py-2" required>{{ old('description', $product?->description) }}</textarea>
            </div>
             <div class="mb-4">
                <label class="block text-gray-700 mb-2">Stok</label>
                <input type="number" name="stock" value="{{ old('quantity', $product?->quantity) }}" class="w-full border rounded px-3 py-2" min="0" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Galeri Gambar (URL, satu per baris)</label>
                <textarea name="images[]" rows="4" class="w-full border rounded px-3 py-2" placeholder="https://example.com/img1.jpg&#10;https://example.com/img2.jpg">{{ 
                    $product ? implode("\n", $product->images->pluck('image_url')->toArray()) : old('images.*')
                }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Pisahkan tiap URL dengan baris baru (enter)</p>
            </div>
        </div>
    </div>

    <div class="flex gap-3 mt-6">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ $product ? 'Perbarui' : 'Simpan' }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border rounded">Batal</a>
    </div>
</form>
@endsection