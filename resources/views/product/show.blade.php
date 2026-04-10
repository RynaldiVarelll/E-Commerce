@extends('layouts.frontend')

@section('content')
<div class="min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-6xl">
        
        {{-- Breadcrumb --}}
        <nav class="flex mb-8 text-sm font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            <a href="{{ route('product.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Shop</a>
            <span class="mx-3">/</span>
            <span class="text-gray-900 dark:text-white">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            {{-- Left: Image Gallery (FIXED VERSION) --}}
            <div class="space-y-4">
                {{-- Main Image Container --}}
                <div class="relative glass-panel dark:bg-gray-800/60 rounded-[2.5rem] overflow-hidden flex items-center justify-center group min-h-[400px] md:min-h-[500px]">
                    @php $firstImg = $product->images->first(); @endphp
                    
                    {{-- 
                        FIX: Menghapus 'aspect-square' dan mengganti 'object-cover' menjadi 'object-contain'. 
                        'object-contain' memastikan seluruh gambar terlihat tanpa distorsi/pecah.
                    --}}
                    <img id="mainImage" 
                         src="{{ $firstImg ? $firstImg->image_url : ($product->image_url ?: 'https://via.placeholder.com/800') }}" 
                         alt="{{ $product->name }}" 
                         class="max-w-full max-h-[500px] w-auto h-auto object-contain transition-transform duration-700 group-hover:scale-105">
                    
                    {{-- Status Badge --}}
                    <div class="absolute top-6 left-6">
                        @if($product->quantity > 0)
                            <span class="bg-blue-600/90 backdrop-blur-md text-white text-[10px] font-black px-4 py-2 rounded-full shadow-lg uppercase tracking-widest">In Stock</span>
                        @else
                            <span class="bg-red-500/90 backdrop-blur-md text-white text-[10px] font-black px-4 py-2 rounded-full shadow-lg uppercase tracking-widest">Out of Stock</span>
                        @endif
                    </div>
                </div>

                {{-- Thumbnails --}}
                @if($product->images->count() > 1)
                    <div class="flex gap-4 overflow-x-auto py-2 custom-scrollbar justify-center">
                        @foreach($product->images as $img)
                            <button onclick="changeMainImage('{{ $img->image_url }}')" 
                                    class="w-20 h-20 flex-shrink-0 rounded-2xl overflow-hidden border-2 border-transparent hover:border-blue-500 transition-all focus:border-blue-500 outline-none glass-panel p-1 dark:bg-gray-800/60">
                                {{-- Thumbnail tetap pakai object-cover agar seragam ukurannya --}}
                                <img src="{{ $img->image_url }}" class="w-full h-full object-cover rounded-xl mt-[-1px]">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            {{-- Right: Product Info --}}
            <div class="flex flex-col h-full">
                <div class="glass-panel dark:bg-gray-800/60 rounded-[2.5rem] p-8 md:p-10">
                    <div class="mb-4">
                        <a href="{{ route('product.index', ['category' => $product->category->id]) }}" class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors font-black text-xs px-3 py-1.5 rounded-full uppercase tracking-widest border border-indigo-100 dark:border-indigo-900/50 inline-block">
                            {{ $product->category->name }}
                        </a>
                    </div>
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white leading-tight mb-2 uppercase tracking-tighter">
                        {{ $product->name }}
                    </h1>
                    <div class="flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 mb-6 uppercase tracking-widest bg-white/40 dark:bg-gray-700/40 backdrop-blur-sm inline-block px-3 py-1 rounded-lg border border-white/60 dark:border-gray-600">
                        <i class="fa-solid fa-store text-blue-600 dark:text-blue-400 mr-2"></i>
                        Store: <span class="text-gray-900 dark:text-white ml-1">{{ $product->user ? $product->user->name : 'Official Store' }}</span>
                        
                        {{-- Sinkronisasi Tampilan: Menampilkan reputasi real toko di samping namanya --}}
                        @if($product->user)
                            <span class="mx-2 text-gray-300 dark:text-gray-600">|</span>
                            <div class="flex items-center gap-1 text-amber-500">
                                <i class="fa-solid fa-star text-[10px]"></i>
                                <span class="react-store-rating text-gray-900 dark:text-white" data-store-id="{{ $product->user->id }}">{{ number_format($product->user->store_rating, 1) }}</span>
                                <span class="text-[9px] text-gray-400 font-bold">(<span class="react-store-count" data-store-id="{{ $product->user->id }}">{{ $product->user->store_review_count }}</span>)</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-6 mb-8">
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500 mb-1">Harga Produk</span>
                            <span class="text-3xl font-black text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="h-10 w-[1px] bg-gray-100 dark:bg-gray-700 hidden sm:block"></div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500 mb-1">Rating Produk</span>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa-{{ $i <= round($product->rating) ? 'solid' : 'regular' }} fa-star text-sm"></i>
                                    @endfor
                                </div>
                                <span class="react-product-rating text-sm font-black text-gray-900 dark:text-white" data-product-id="{{ $product->id }}">{{ number_format($product->rating, 1) }}</span>
                                <span class="text-[10px] font-bold text-gray-400">(<span class="react-product-count" data-product-id="{{ $product->id }}">{{ $product->review_count }}</span> Ulasan)</span>
                            </div>
                        </div>
                        <div class="h-10 w-[1px] bg-gray-100 dark:bg-gray-700 hidden sm:block"></div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500 mb-1">Ketersediaan</span>
                            <span class="text-sm font-bold {{ $product->quantity < 5 ? 'text-orange-500' : 'text-gray-600 dark:text-gray-400' }} uppercase tracking-widest">
                                {{ $product->quantity }} pcs Tersedia
                            </span>
                        </div>
                    </div>

                    <div class="prose prose-blue text-gray-500 dark:text-gray-400 max-w-none mb-8 leading-relaxed italic">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    {{-- Action Area --}}
                    <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                {{-- Modern Quantity Selector --}}
                                <div class="flex items-center bg-white/40 dark:bg-gray-800/40 backdrop-blur-md rounded-2xl p-1 border border-white/60 dark:border-gray-700 focus-within:border-blue-400 dark:focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-400/20 transition-all">
                                    <button type="button" class="w-12 h-12 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-bold transition-colors" onclick="changeQuantity(this, -1)">−</button>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}"
                                           class="w-12 bg-transparent text-center font-black text-gray-800 dark:text-gray-200 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    <button type="button" class="w-12 h-12 flex items-center justify-center text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 font-bold transition-colors" onclick="changeQuantity(this, 1)">+</button>
                                </div>

                                <button type="submit" 
                                        {{ $product->quantity <= 0 ? 'disabled' : '' }}
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 dark:disabled:bg-gray-700 dark:disabled:text-gray-500 text-white py-4 rounded-2xl font-black text-lg transition-all shadow-xl shadow-blue-100 dark:shadow-none hover:shadow-blue-200 active:scale-95 flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    ADD TO CART
                                </button>
                            </div>
                        </form>

                        {{-- Chat Seller Button --}}
                        @auth
                            <a href="{{ route('chat.show', $product->user->id) }}"
                               class="w-full flex items-center justify-center gap-3 bg-white/30 dark:bg-gray-800/60 backdrop-blur-md border border-white/60 dark:border-gray-600 text-blue-600 dark:text-blue-400 py-4 rounded-2xl font-black hover:bg-blue-600 hover:text-white dark:hover:text-white transition-all duration-300 shadow-xl shadow-blue-900/5 dark:shadow-none group">
                                <i class="fa-solid fa-comments text-2xl group-hover:scale-110 transition-transform"></i>
                                CHAT PENJUAL
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="w-full flex items-center justify-center gap-3 bg-white/30 dark:bg-gray-800/60 backdrop-blur-md border border-white/60 dark:border-gray-600 text-blue-600 dark:text-blue-400 py-4 rounded-2xl font-black hover:bg-blue-600 hover:text-white dark:hover:text-white transition-all duration-300">
                                <i class="fa-solid fa-comments text-2xl"></i>
                                LOGIN UNTUK CHAT
                            </a>
                        @endauth

                        {{-- Seller Profile Card --}}
                        <div class="glass-panel dark:bg-gray-800/60 rounded-[2rem] p-6 mt-8 flex items-center gap-4 bg-white/30 border border-white/60 dark:border-gray-700">
                            <div class="w-16 h-16 bg-white/50 dark:bg-gray-700/50 backdrop-blur-md rounded-2xl overflow-hidden flex-shrink-0 border border-white/80 dark:border-gray-600 shadow-inner group transition-transform hover:scale-105">
                                <img src="{{ $product->user->profile_photo_url }}" 
                                     alt="{{ $product->user->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-tighter leading-tight">{{ $product->user->name }}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                                    <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Penjual Terverifikasi</span>
                                </div>
                                <div class="text-[9px] text-gray-400 dark:text-gray-500 font-bold uppercase tracking-widest mt-1 italic">
                                    Bergabung {{ $product->user->created_at->diffForHumans() }}
                                </div>
                            </div>
                            <a href="{{ $product->user ? route('shop.show', $product->user->id) : '#' }}" 
                               class="px-4 py-2.5 bg-gray-900 dark:bg-gray-700 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-blue-600 dark:hover:bg-blue-500 transition-all active:scale-95 shadow-lg shadow-gray-200 dark:shadow-none">
                                Kunjungi Toko
                            </a>
                        </div>
                    </div>

                    {{-- Trust Badges --}}
                    <div class="grid grid-cols-3 gap-4 mt-10 pt-8 border-t border-gray-50 dark:border-gray-700">
                        <div class="text-center">
                            <i class="fa-solid fa-truck-fast text-blue-600 dark:text-blue-400 text-xl mb-2"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Fast Delivery</p>
                        </div>
                        <div class="text-center">
                            {{-- FIXED: Menggunakan ikon shield yang support versi free agar muncul --}}
                            <i class="fa-solid fa-shield-halved text-blue-600 dark:text-blue-400 text-xl mb-2"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Secure Payment</p>
                        </div>
                        <div class="text-center">
                            <i class="fa-solid fa-rotate-left text-blue-600 dark:text-blue-400 text-xl mb-2"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500">Easy Returns</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Product Reviews Section --}}
        <div class="mt-20">
            <div class="flex items-center justify-between mb-10 pb-6 border-b border-gray-100 dark:border-gray-800">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic">Ulasan Pelanggan</h2>
                    <p class="text-gray-400 dark:text-gray-500 font-bold text-xs uppercase tracking-widest mt-1 italic">Apa kata mereka tentang produk ini?</p>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <div class="flex items-center gap-2 text-amber-500 mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa-{{ $i <= round($product->rating) ? 'solid' : 'regular' }} fa-star text-lg"></i>
                            @endfor
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 dark:text-gray-500"><span class="react-product-count" data-product-id="{{ $product->id }}">{{ $product->review_count }}</span> Ulasan Terverifikasi</p>
                    </div>
                    <div class="react-product-rating text-4xl font-black text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-800/60 px-6 py-4 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-inner" data-product-id="{{ $product->id }}">
                        {{ number_format($product->rating, 1) }}
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($product->reviews as $review)
                    <div class="glass-panel dark:bg-gray-800/60 rounded-[2.5rem] p-8 hover:translate-y-[-5px] transition-all duration-500 group border border-transparent hover:border-blue-100 dark:hover:border-blue-900/50">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white dark:border-gray-700 shadow-md">
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
                            <span class="text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded-lg">Verified Purchase</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center glass-panel dark:bg-gray-800/60 rounded-[3rem] border-2 border-dashed border-gray-200 dark:border-gray-700">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-900/40 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300 dark:text-gray-600">
                            <i class="fa-solid fa-comment-slash text-3xl"></i>
                        </div>
                        <p class="font-black text-gray-400 dark:text-gray-500 uppercase tracking-widest italic">Belum ada ulasan untuk produk ini.</p>
                        <p class="text-[10px] text-gray-300 dark:text-gray-600 font-bold mt-2 uppercase tracking-[0.2em]">Jadilah yang pertama memberikan ulasan!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
}

function changeQuantity(btn, delta) {
    const input = btn.parentElement.querySelector('input[name="quantity"]');
    let value = parseInt(input.value || 1);
    let max = parseInt(input.max);
    
    value = value + delta;
    if (value < 1) value = 1;
    if (value > max) value = max;
    
    input.value = value;
}
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endsection