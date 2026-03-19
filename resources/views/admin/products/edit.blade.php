@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6 max-w-5xl">
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none uppercase italic">
            Edit <span class="text-blue-600">Produk.</span>
        </h1>
        <p class="text-gray-500 mt-2 font-medium">Lengkapi detail produk agar lebih menarik bagi pelanggan.</p>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" 
        class="glass-panel p-10 rounded-[2.5rem] relative overflow-hidden">
        <div class="absolute inset-0 bg-white/40 pointer-events-none"></div>
        <div class="relative z-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kiri --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                            class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-black text-lg placeholder-gray-400 text-gray-900" 
                            required>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Kategori</label>
                        <select name="category_id" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold text-gray-900" required>
                            <option value="">Pilih</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Harga (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" 
                            class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-black text-lg placeholder-gray-400 text-gray-900" 
                            min="0" step="0.01" required>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Stok</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" 
                            class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-black text-lg placeholder-gray-400 text-gray-900" 
                            min="0" required>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Link WhatsApp (lengkap)</label>
                        <input type="url" name="whatsapp_link" value="{{ old('whatsapp_link', $product->whatsapp_link) }}" 
                            class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold text-gray-900 placeholder-gray-400 text-sm" 
                            placeholder="https://wa.me/6281234567890" required>
                    </div>
                </div>

                {{-- Kanan --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Deskripsi</label>
                        <textarea name="description" rows="5" 
                            class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-medium text-gray-900 placeholder-gray-400" 
                            required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Gambar Utama (Ubah Opsional)</label>
                        <input type="file" name="image" 
                            class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200" 
                            accept="image/*">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Galeri Gambar (Multi Upload)</label>
                        <input type="file" name="images[]" 
                            class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold text-gray-900 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-black file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200" 
                            accept="image/*" multiple>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Galeri Gambar (URL, 1 per baris)</label>
                        <textarea name="images" rows="4" 
                            class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-medium text-gray-900 placeholder-gray-400 text-sm" 
                            placeholder="https://example.com/img1.jpg&#10;https://example.com/img2.jpg">{{ implode("\n", $product->images->pluck('image_url')->toArray()) ?? old('images') }}</textarea>
                    </div>

                    <div class="flex items-center bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-4">
                        <input type="checkbox" name="is_active" id="is_active" {{ (old('is_active', $product->is_active ?? true)) ? 'checked' : '' }} 
                            class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="is_active" class="ml-3 text-sm font-black uppercase tracking-widest text-gray-700 cursor-pointer">
                            Status Aktif
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-8 mt-4 border-t border-white/40">
                <button type="submit" 
                    class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 leading-none">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> UPDATE
                </button>
                <a href="{{ route('admin.products.index') }}" 
                    class="px-8 py-4 bg-white/40 backdrop-blur-md border border-white/60 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-600 hover:bg-white/60 transition-all leading-none inline-flex items-center">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection