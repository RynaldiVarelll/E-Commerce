@extends('layouts.admin')

@section('content')
<h2 class="text-2xl font-bold mb-6">{{ $category ? 'Edit Kategori' : 'Tambah Kategori' }}</h2>

<form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST">
    @csrf
    @if($category)
        @method('PUT')
    @endif

    <div class="mb-4">
        <label class="block text-gray-700 mb-2">Nama Kategori</label>
        <input type="text" name="name" value="{{ old('name', $category?->name) }}" class="w-full border rounded px-3 py-2" required>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            {{ $category ? 'Perbarui' : 'Simpan' }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border rounded">Batal</a>
    </div>
</form>
@endsection