@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none uppercase italic">Kelola <span class="text-blue-600">Produk.</span></h1>
            <p class="text-gray-500 mt-2 font-medium">Atur inventaris produk store Anda dengan tampilan modern.</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." 
                        class="glass-panel px-6 py-3 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 pl-12 w-64 placeholder-gray-400 font-bold">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </form>
            <a href="{{ route('admin.products.create') }}" 
                class="inline-flex items-center bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-black shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 whitespace-nowrap">
                <i class="fa-solid fa-plus mr-2"></i> TAMBAH PRODUK
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
    @foreach($products as $p)
        <div class="group relative glass-panel rounded-[2.5rem] overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 flex flex-col h-full">
            <div class="relative aspect-[4/3] overflow-hidden bg-white/40">
                <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest backdrop-blur-md {{ $p->is_active ? 'bg-green-500/80 text-white' : 'bg-red-500/80 text-white' }}">
                        {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>

            <div class="p-6 flex flex-col flex-grow">
                <div class="mb-auto">
                    <div class="mb-3">
                        <span class="bg-blue-50/50 text-blue-600 font-black text-[10px] px-2.5 py-1 rounded-lg uppercase tracking-widest border border-blue-100">
                            {{ $p->category->name ?? 'Uncategorized' }}
                        </span>
                    </div>
                    <h3 class="text-lg font-black text-gray-900 leading-tight mb-2 truncate">{{ $p->name }}</h3>
                    
                    @if(auth()->user()->isSuperAdmin())
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 flex items-center">
                        <i class="fa-solid fa-store mr-2 text-blue-500"></i> {{ $p->user ? $p->user->name : 'Official Store' }}
                    </p>
                    @endif
                    
                    <p class="text-2xl font-black text-blue-600 mb-6">
                        <span class="text-sm font-medium">Rp</span>{{ number_format($p->price, 0, ',', '.') }}
                    </p>
                </div>

                <div class="flex items-center gap-2 pt-4 border-t border-white/40 mt-4">
                    <a href="{{ route('admin.products.edit', $p) }}" 
                       class="flex-1 flex items-center justify-center bg-white/50 hover:bg-blue-600 hover:text-white py-3 rounded-2xl text-xs font-black transition-all border border-white/60">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> EDIT
                    </a>
                    <form action="{{ route('admin.products.destroy', $p) }}" method="POST" onsubmit="return confirm('Hapus produk ini?')" class="flex-shrink-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-12 h-12 flex items-center justify-center bg-red-50/50 text-red-600 hover:bg-red-600 hover:text-white rounded-2xl transition-all border border-red-100/40">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    </div>
</div>
@endsection
