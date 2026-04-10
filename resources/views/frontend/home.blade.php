@extends('layouts.frontend')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-12">
        
        <div class="mb-12">
            <div class="flex items-center space-x-2 mb-6">
                <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Eksplor Kategori</h2>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('product.index') }}"
                   class="px-6 py-2.5 {{ !request('category') ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-200 dark:shadow-none backdrop-blur-md' : 'glass-panel text-gray-700 dark:text-gray-300' }} rounded-xl text-sm font-bold hover:scale-105 transition-all duration-200">
                    ✨ Semua Produk
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('product.index', ['category' => $category->id]) }}"
                       class="px-6 py-2.5 {{ request('category') == $category->id ? 'bg-blue-600/90 text-white shadow-lg shadow-blue-200 dark:shadow-none backdrop-blur-md' : 'glass-panel text-gray-700 dark:text-gray-300' }} rounded-xl text-sm font-bold hover:border-blue-300 dark:hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Hasil Pencarian Toko --}}
        @if($shops->isNotEmpty())
            <div class="mb-12 animate-fade-in">
                <div class="flex items-center space-x-2 mb-6">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full shadow-lg shadow-indigo-200 dark:shadow-none"></div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight uppercase italic">Toko <span class="text-indigo-600 dark:text-indigo-400">Terindex.</span></h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($shops as $shop)
                        <a href="{{ route('shop.show', $shop->id) }}" class="glass-panel rounded-[2rem] p-6 flex items-center gap-5 hover:shadow-2xl hover:shadow-indigo-900/10 dark:hover:shadow-black/30 hover:-translate-y-1.5 transition-all duration-500 group bg-white/60 dark:bg-gray-800/60 border border-white dark:border-gray-700">
                            <div class="w-20 h-20 rounded-2xl overflow-hidden border-2 border-white dark:border-gray-700 shadow-xl shadow-indigo-100/50 dark:shadow-none flex-shrink-0">
                                <img src="{{ $shop->profile_photo_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-gray-900 dark:text-white uppercase tracking-tighter group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">{{ $shop->name }}</h3>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest flex items-center gap-1">
                                        <i class="fa-solid fa-box-open text-indigo-400"></i>
                                        {{ $shop->products->count() }} Item
                                    </span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                                    <span class="text-[10px] font-bold text-green-600 dark:text-green-400 uppercase tracking-widest">Verified</span>
                                </div>
                                <div class="mt-3 inline-flex items-center gap-2 text-[9px] font-black text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    Kunjungi Toko <i class="fa-solid fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Semua Produk</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Menampilkan <span class="text-blue-600 dark:text-blue-400 font-bold">{{ $products->count() }}</span> koleksi terbaik untukmu</p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    {{-- Pencarian --}}
                    <form action="{{ route('product.index') }}" method="GET" class="flex w-full md:w-auto items-center gap-2">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk..." class="glass-panel dark:bg-gray-800/40 dark:text-gray-100 px-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400/50 shadow-sm w-full sm:w-64 placeholder-gray-500 dark:placeholder-gray-400 font-medium">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 shadow-sm transition-colors text-sm font-bold">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>

                    {{-- Sort Dummy --}}
                    <div class="flex items-center w-full sm:w-auto glass-panel dark:bg-gray-800/40 rounded-xl px-4 py-2 text-sm text-gray-700 dark:text-gray-300 shadow-sm relative font-medium">
                        <i class="fa-solid fa-sliders mr-2"></i>
                        <span class="font-medium whitespace-nowrap">Urutkan: Terpopuler</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                @foreach($products as $product)
                    <div class="group glass-panel dark:bg-gray-800/50 rounded-[2rem] hover:shadow-2xl hover:shadow-blue-900/10 dark:hover:shadow-black/20 hover:-translate-y-1 transition-all duration-500 overflow-hidden flex flex-col h-full">
                        
                        <div class="relative aspect-square overflow-hidden bg-white/40 dark:bg-gray-700/40">
                            <img src="{{ $product->image_url ?: 'https://via.placeholder.com/400' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                            
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if ($product->quantity <= 0)
                                    <span class="backdrop-blur-md bg-red-500/80 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Sold Out</span>
                                @else
                                    <span class="backdrop-blur-md bg-blue-600/80 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Ready Stock</span>
                                @endif
                            </div>

                            <div class="absolute inset-0 bg-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <a href="{{ route('product.show', $product->id) }}" 
                                   class="bg-white text-blue-600 p-4 rounded-full shadow-xl transform translate-y-10 group-hover:translate-y-0 transition-transform duration-500">
                                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="mb-auto">
                                <div class="mb-3">
                                    <span class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 font-black text-[10px] px-2.5 py-1 rounded-full uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/50">
                                        {{ $product->category->name }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-gray-900 dark:text-white mb-1 line-clamp-2 leading-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ $product->name }}
                                </h3>
                                <div class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-2 flex items-center">
                                    <i class="fa-solid fa-store mr-1 text-blue-500"></i>
                                    {{ $product->user ? $product->user->name : 'Official Store' }}
                                </div>
                                <div class="flex items-center justify-between mt-3 mb-4">
                                    <div class="flex items-center text-[11px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-tighter">
                                        <i class="fa-solid fa-box-open mr-1"></i>
                                        Stok: <span class="{{ $product->quantity < 5 ? 'text-orange-500' : 'text-gray-500 dark:text-gray-400' }} ml-1">{{ $product->quantity }} pcs</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-amber-500">
                                        <i class="fa-solid fa-star text-[10px]"></i>
                                        <span class="react-product-rating text-[10px] font-black text-gray-900 dark:text-white" data-product-id="{{ $product->id }}">{{ number_format($product->rating, 1) }}</span>
                                        <span class="text-[9px] font-bold text-gray-400">(<span class="react-product-count" data-product-id="{{ $product->id }}">{{ $product->review_count }}</span>)</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-2xl font-black text-blue-600 mb-4">
                                    <span class="text-sm font-medium">Rp</span>{{ number_format($product->price, 0, ',', '.') }}
                                </p>

                                <form action="{{ route('cart.add') }}" method="POST" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    @if ($product->quantity <= 0)
                                        <button type="button" disabled
                                                class="w-full bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 py-3 rounded-xl font-bold flex items-center justify-center cursor-not-allowed border border-transparent dark:border-gray-700">
                                            <i class="fa-solid fa-circle-xmark mr-2"></i> Habis
                                        </button>
                                    @else
                                        @auth
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center bg-gray-100 dark:bg-gray-800 rounded-xl p-1 border border-transparent dark:border-gray-700 focus-within:border-blue-200 dark:focus-within:border-blue-800 transition-all">
                                                    <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-bold transition-colors" onclick="changeQuantity(this, -1)">−</button>
                                                    <input type="number" name="quantity" value="1" min="1"
                                                           class="w-10 bg-transparent text-center text-sm font-bold text-gray-800 dark:text-gray-200 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                    <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-bold transition-colors" onclick="changeQuantity(this, 1)">+</button>
                                                </div>

                                                <button type="submit"
                                                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition-all duration-300 shadow-[0_10px_20px_rgba(37,_99,_235,_0.2)] hover:shadow-blue-300 active:scale-95">
                                                    <i class="fa-solid fa-cart-plus"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('login') }}" 
                                                   class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold flex items-center justify-center transition-all duration-300 shadow-[0_10px_20px_rgba(37,_99,_235,_0.2)] hover:shadow-blue-300 active:scale-95">
                                                    <i class="fa-solid fa-cart-plus mr-2"></i> Tambah Keranjang
                                                </a>
                                            </div>
                                        @endauth
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk mengubah kuantitas
    function changeQuantity(btn, delta) {
        const input = btn.parentElement.querySelector('input[name="quantity"]');
        let value = parseInt(input.value || 1);
        value = Math.max(1, value + delta);
        input.value = value;
    }
</script>

<style>
    /* Styling tambahan untuk membersihkan input number */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
      -webkit-appearance: none; 
      margin: 0; 
    }
</style>
@endsection