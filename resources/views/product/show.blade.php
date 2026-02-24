@extends('layouts.frontend')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-6xl">
        
        {{-- Breadcrumb --}}
        <nav class="flex mb-8 text-sm font-bold uppercase tracking-widest text-gray-400">
            <a href="{{ route('product.index') }}" class="hover:text-blue-600 transition-colors">Shop</a>
            <span class="mx-3">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            {{-- Left: Image Gallery (FIXED VERSION) --}}
            <div class="space-y-4">
                {{-- Main Image Container --}}
                <div class="relative bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-100 flex items-center justify-center group min-h-[400px] md:min-h-[500px]">
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
                                    class="w-20 h-20 flex-shrink-0 rounded-2xl overflow-hidden border-2 border-transparent hover:border-blue-600 transition-all focus:border-blue-600 outline-none bg-white shadow-sm">
                                {{-- Thumbnail tetap pakai object-cover agar seragam ukurannya --}}
                                <img src="{{ $img->image_url }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            {{-- Right: Product Info --}}
            <div class="flex flex-col h-full">
                <div class="bg-white rounded-[2.5rem] p-8 md:p-10 shadow-sm border border-gray-100">
                    <h1 class="text-4xl font-black text-gray-900 leading-tight mb-2 uppercase tracking-tighter">
                        {{ $product->name }}
                    </h1>
                    
                    <div class="flex items-center gap-4 mb-6">
                        <span class="text-3xl font-black text-blue-600">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                        <div class="h-6 w-[1px] bg-gray-200"></div>
                        <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">
                            Stock: {{ $product->quantity }} pcs
                        </span>
                    </div>

                    <div class="prose prose-blue text-gray-500 max-w-none mb-8 leading-relaxed italic">
                        {!! nl2br(e($product->description)) !!}
                    </div>

                    {{-- Action Area --}}
                    <div class="space-y-4 pt-6 border-t border-gray-100">
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                {{-- Modern Quantity Selector --}}
                                <div class="flex items-center bg-gray-100 rounded-2xl p-1 border border-transparent focus-within:border-blue-200 transition-all">
                                    <button type="button" class="w-12 h-12 flex items-center justify-center text-gray-500 hover:text-blue-600 font-bold transition-colors" onclick="changeQuantity(this, -1)">−</button>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}"
                                           class="w-12 bg-transparent text-center font-black text-gray-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                    <button type="button" class="w-12 h-12 flex items-center justify-center text-gray-500 hover:text-blue-600 font-bold transition-colors" onclick="changeQuantity(this, 1)">+</button>
                                </div>

                                <button type="submit" 
                                        {{ $product->quantity <= 0 ? 'disabled' : '' }}
                                        class="flex-1 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-300 text-white py-4 rounded-2xl font-black text-lg transition-all shadow-xl shadow-blue-100 hover:shadow-blue-200 active:scale-95 flex items-center justify-center gap-3">
                                    <i class="fa-solid fa-cart-plus"></i>
                                    ADD TO CART
                                </button>
                            </div>
                        </form>

                        {{-- WhatsApp Button --}}
                        <a href="https://wa.me/{{ parse_url($product->whatsapp_link, PHP_URL_HOST) }}?text=Halo, saya tertarik dengan produk {{ urlencode($product->name) }}"
                           target="_blank"
                           class="w-full flex items-center justify-center gap-3 bg-white border-2 border-green-500 text-green-600 py-4 rounded-2xl font-black hover:bg-green-500 hover:text-white transition-all duration-300">
                            <i class="fa-brands fa-whatsapp text-2xl"></i>
                            TANYA ADMIN (WA)
                        </a>
                    </div>

                    {{-- Trust Badges --}}
                    <div class="grid grid-cols-3 gap-4 mt-10 pt-8 border-t border-gray-50">
                        <div class="text-center">
                            <i class="fa-solid fa-truck-fast text-blue-600 text-xl mb-2"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Fast Delivery</p>
                        </div>
                        <div class="text-center">
                            {{-- FIXED: Menggunakan ikon shield yang support versi free agar muncul --}}
                            <i class="fa-solid fa-shield-halved text-blue-600 text-xl mb-2"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Secure Payment</p>
                        </div>
                        <div class="text-center">
                            <i class="fa-solid fa-rotate-left text-blue-600 text-xl mb-2"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Easy Returns</p>
                        </div>
                    </div>
                </div>
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