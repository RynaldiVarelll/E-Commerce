@extends('layouts.frontend')

@section('content')
<div class="min-h-screen">
    <div class="container mx-auto px-4 py-8">
        
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('product.index') }}" class="inline-flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors px-4 py-2 bg-white/50 dark:bg-gray-800/50 backdrop-blur-sm rounded-full border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>
        </div>

        {{-- Store Details Banner --}}
        <div class="glass-panel dark:bg-gray-800/60 rounded-[2rem] p-8 md:p-10 mb-12 bg-white/40 dark:bg-gray-800/40 border border-white/60 dark:border-gray-700 relative overflow-hidden">
            {{-- Background Accent --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-400/10 dark:bg-blue-600/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
                <div class="w-32 h-32 bg-white/50 dark:bg-gray-700/50 backdrop-blur-md rounded-[2rem] overflow-hidden flex-shrink-0 border-2 border-white/80 dark:border-gray-600 shadow-xl shadow-blue-100/50 dark:shadow-none">
                    <img src="{{ $shop->profile_photo_url }}" 
                         alt="{{ $shop->name }}" 
                         class="w-full h-full object-cover">
                </div>
                
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter leading-tight">{{ $shop->name }}</h1>
                    
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-3">
                        <div class="flex items-center gap-2 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 px-3 py-1.5 rounded-full border border-green-100 dark:border-green-900/50">
                            <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Penjual Terverifikasi</span>
                        </div>
                        <div class="flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 px-3 py-1.5 rounded-full border border-blue-100 dark:border-blue-900/50">
                            <i class="fa-solid fa-box-open text-[10px]"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">{{ $products->count() }} Produk</span>
                        </div>
                        <div class="flex items-center gap-2 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 px-3 py-1.5 rounded-full border border-amber-100 dark:border-amber-900/50">
                            <i class="fa-solid fa-star text-[10px]"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">{{ number_format($shop->store_rating, 1) }} ({{ $shop->store_review_count }} Ulasan)</span>
                        </div>
                    </div>
                    
                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-4 uppercase tracking-widest italic">
                        <i class="fa-solid fa-calendar-check mr-2 text-blue-400 dark:text-blue-500"></i>Bergabung Sejak {{ $shop->created_at->format('M Y') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Store Products Section --}}
        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">Katalog Produk</h2>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Menampilkan semua produk dari <span class="text-blue-600 dark:text-blue-400 font-bold uppercase">{{ $shop->name }}</span></p>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    {{-- Pencarian --}}
                    <form action="{{ route('shop.show', $shop->id) }}" method="GET" class="flex w-full md:w-auto items-center gap-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari di toko ini..." class="glass-panel dark:bg-gray-800/40 dark:text-gray-100 px-4 py-2 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400/50 shadow-sm w-full sm:w-64 placeholder-gray-500 dark:placeholder-gray-400 font-medium border border-gray-100 dark:border-gray-700">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 shadow-sm transition-colors text-sm font-bold">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>
            </div>

            @if($products->isEmpty())
                <div class="text-center py-20 glass-panel dark:bg-gray-800/60 rounded-[2rem] border border-gray-100 dark:border-gray-700">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gray-50 dark:bg-gray-700 mb-6">
                        <i class="fa-solid fa-box-open text-3xl text-gray-300 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Belum ada produk</h3>
                    <p class="text-gray-500 dark:text-gray-400">Toko ini sepertinya belum menambahkan produk apapun, atau produk yang Anda cari tidak ditemukan.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                    @foreach($products as $product)
                        <div class="group glass-panel dark:bg-gray-800/60 rounded-[2rem] hover:shadow-2xl hover:shadow-blue-900/10 dark:hover:shadow-black/30 hover:-translate-y-1 transition-all duration-500 overflow-hidden flex flex-col h-full bg-white dark:bg-gray-800 border border-gray-50 dark:border-gray-700">
                            
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
                                                    class="w-full bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 py-3 rounded-xl font-bold flex items-center justify-center cursor-not-allowed border border-gray-200 dark:border-gray-700">
                                                <i class="fa-solid fa-circle-xmark mr-2"></i> Habis
                                            </button>
                                        @else
                                            @auth
                                                <div class="flex items-center gap-2">
                                                    <div class="flex items-center bg-gray-50 dark:bg-gray-800 rounded-xl p-1 border border-gray-200 dark:border-gray-700 focus-within:border-blue-200 dark:focus-within:border-blue-800 transition-all">
                                                        <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-bold transition-colors" onclick="changeQuantity(this, -1)">−</button>
                                                        <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}"
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
            @endif
        </div>

        {{-- Store Reviews Section --}}
        <div class="mt-20">
            <div class="flex items-center justify-between mb-10 pb-6 border-b border-gray-100 dark:border-gray-800">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Ulasan Pelayanan Toko</h2>
                    <p class="text-gray-400 dark:text-gray-500 font-bold text-xs uppercase tracking-widest mt-1 italic">Apa pengalaman mereka bertransaksi di toko ini?</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($shop->storeReviews as $review)
                    <div class="glass-panel dark:bg-gray-800/60 rounded-[2.5rem] p-8 hover:translate-y-[-5px] transition-all duration-500 group border border-transparent hover:border-blue-100 dark:hover:border-blue-900/50">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white dark:border-gray-700 shadow-md flex-shrink-0">
                                <img src="{{ $review->user->profile_photo_url }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-black text-gray-900 dark:text-white text-xs uppercase tracking-widest">{{ $review->user->name }}</h4>
                                <div class="flex items-center gap-1 text-amber-500 mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star text-[10px]"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium leading-relaxed italic mb-4">
                            "{{ $review->comment ?: 'Tidak ada komentar.' }}"
                        </p>
                        <div class="text-[9px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500 flex items-center justify-between">
                            <span>Diterbitkan {{ $review->created_at->diffForHumans() }}</span>
                            <div class="flex items-center gap-2">
                                @if(Auth::check() && Auth::id() === $review->user_id)
                                    <form action="{{ route('reviews.destroy-store', $review->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ulasan toko ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600 bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded-lg transition-colors cursor-pointer" title="Hapus Ulasan Toko">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif
                                <span class="text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded-lg">Verified Buyer</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center glass-panel dark:bg-gray-800/60 rounded-[3rem] border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-900/40 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-store-slash text-3xl"></i>
                        </div>
                        <p class="font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest italic">Belum ada ulasan pelayanan toko.</p>
                        <p class="text-[10px] text-gray-300 dark:text-gray-600 font-bold mt-2 uppercase tracking-[0.2em]">Selesaikan transaksi dan jadilah yang pertama!</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<script>
    // Fungsi untuk mengubah kuantitas
    function changeQuantity(btn, delta) {
        const input = btn.parentElement.querySelector('input[name="quantity"]');
        let value = parseInt(input.value || 1);
        let max = parseInt(input.max || 999);
        
        value = value + delta;
        if (value < 1) value = 1;
        if (value > max) value = max;
        
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
