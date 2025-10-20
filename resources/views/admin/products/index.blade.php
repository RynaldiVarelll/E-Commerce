@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Daftar Produk</h2>
    <a href="{{ route('admin.products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    
    @foreach($products as $p)
        <div class="bg-white border rounded-lg overflow-hidden shadow-sm">
            <img src="{{ asset($p->image_url) }}"
                 alt="{{ $p->name }}"
                 class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-800">{{ $p->name }}</h3>
                <p class="text-sm text-gray-600">{{ $p->category->name ?? 'Tanpa Kategori' }}</p>
                <p class="text-xl font-semibold text-blue-700 mt-1 mb-2">
                    Rp {{ number_format($p->price, 0, ',', '.') }}
                </p>
                <span class="inline-block text-xs px-2 py-1 rounded 
                    {{ $p->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>

                <div class="flex gap-3 mt-3 text-sm">
                    <a href="{{ route('admin.products.edit', $p) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
