@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6 max-w-2xl">
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none uppercase italic">
            {{ $category ? 'Edit' : 'Create' }} <span class="text-blue-600">Category.</span>
        </h1>
        <p class="text-gray-500 mt-2 font-medium">Atur nama kategori agar produk lebih terorganisir.</p>
    </div>

    <form action="{{ $category ? route('admin.categories.update', $category) : route('admin.categories.store') }}" 
        method="POST" class="glass-panel p-10 rounded-[2.5rem] relative overflow-hidden">
        <div class="absolute inset-0 bg-white/40 pointer-events-none"></div>
        <div class="relative z-10">
            @csrf
            @if($category)
                @method('PUT')
            @endif

            <div class="mb-8">
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Nama Kategori</label>
                <input type="text" name="name" value="{{ old('name', $category?->name) }}" 
                    class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-black text-lg placeholder-gray-400 text-gray-900" 
                    placeholder="Contoh: Fashion, Elektronik..." required>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-white/40">
                <button type="submit" 
                    class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 leading-none">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> {{ $category ? 'UPDATE' : 'SAVE' }}
                </button>
                <a href="{{ route('admin.categories.index') }}" 
                    class="px-8 py-4 bg-white/40 backdrop-blur-md border border-white/60 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-600 hover:bg-white/60 transition-all leading-none">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection