@extends('layouts.frontend')

@section('content')
<div class="bg-[#f8fafc] min-h-screen py-12">
    <div class="container mx-auto px-6">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div class="animate-fade-in-up">
                <nav class="flex mb-4 text-sm font-bold uppercase tracking-widest text-gray-400">
                    <a href="/" class="hover:text-blue-600 transition-colors">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-blue-600">Shop</span>
                </nav>
                <h1 class="text-5xl font-black text-gray-900 tracking-tighter">
                    Our Collection<span class="text-blue-600">.</span>
                </h1>
                <p class="text-gray-500 mt-3 text-lg font-medium">Temukan produk terbaik yang dikurasi khusus untuk kebutuhan Anda.</p>
            </div>
            
            {{-- Filter/Sort Shortcut (Visual Only) --}}
            <div class="flex items-center gap-4">
                <button class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-100 rounded-2xl font-bold text-gray-600 shadow-sm hover:shadow-md transition-all">
                    <i class="fa-solid fa-arrow-down-wide-short"></i>
                    Sort By
                </button>
            </div>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
            @foreach($products as $product)
                <div class="group relative flex flex-col h-full animate-fade-in-up" style="animation-delay: {{ $loop->index * 50 }}ms">
                    
                    {{-- Product Image & Overlay --}}
                    <div class="relative aspect-[4/5] mb-5 overflow-hidden rounded-[2.5rem] bg-gray-200 shadow-sm transition-all duration-500 group-hover:shadow-2xl group-hover:shadow-blue-900/10">
                        <img src="{{ asset(optional($product->images->first())->image_url ?? 'https://via.placeholder.com/600x800') }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                        
                        {{-- Quick Action Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-blue-900/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-[85%] translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500">
                            <a href="{{ route('product.show', $product->id) }}" 
                               class="block w-full py-4 bg-white text-gray-900 text-center font-black rounded-2xl shadow-xl hover:bg-blue-600 hover:text-white transition-colors">
                                <i class="fa-solid fa-eye mr-2"></i> Quick View
                            </a>
                        </div>

                        {{-- Category Badge --}}
                        <div class="absolute top-6 left-6">
                            <span class="px-4 py-2 bg-white/90 backdrop-blur-md text-[10px] font-black uppercase tracking-[0.2em] text-gray-900 rounded-full shadow-sm">
                                NEW ARRIVAL
                            </span>
                        </div>
                    </div>

                    {{-- Product Details --}}
                    <div class="px-2 flex flex-col flex-grow">
                        <a href="{{ route('product.show', $product->id) }}" class="flex-grow">
                            <h2 class="text-xl font-bold text-gray-900 mb-2 leading-tight group-hover:text-blue-600 transition-colors">
                                {{ $product->name }}
                            </h2>
                            <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mb-4">
                                {{ $product->description ?? 'No description available for this premium product.' }}
                            </p>
                        </a>
                        
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Price</span>
                                <span class="text-2xl font-black text-blue-600 tracking-tighter">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                            
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" 
                                        class="w-12 h-12 bg-gray-900 text-white rounded-2xl flex items-center justify-center hover:bg-blue-600 hover:scale-110 transition-all duration-300 shadow-lg shadow-gray-200">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>
@endsection