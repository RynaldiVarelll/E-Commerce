@extends('layouts.frontend')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight flex items-center">
                    Keranjang Belanja 
                    <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-600 text-sm rounded-full tracking-normal">
                        {{ $cartItems->sum('quantity') }} Item
                    </span>
                </h1>
                <a href="{{ route('product.index') }}" 
                   class="group mt-2 inline-flex items-center text-sm font-bold text-gray-500 hover:text-blue-600 transition-colors">
                   <i class="fa-solid fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                   Lanjut Belanja
                </a>
            </div>
        </div>

        @if($cartItems->isEmpty())
            <div class="bg-white rounded-[3rem] p-20 shadow-sm border border-gray-100 text-center">
                <div class="relative w-32 h-32 mx-auto mb-6">
                    <div class="absolute inset-0 bg-blue-100 rounded-full animate-pulse"></div>
                    <div class="relative flex items-center justify-center h-full">
                        <i class="fa-solid fa-cart-ghost text-5xl text-blue-400"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Wah, keranjangmu kosong!</h2>
                <p class="text-gray-500 mt-2 mb-8">Yuk, cari produk impianmu dan masukkan ke sini.</p>
                <a href="{{ route('product.index') }}" 
                   class="px-8 py-4 bg-blue-600 text-white rounded-2xl font-black shadow-lg shadow-blue-200 hover:scale-105 transition-all">
                    Mulai Belanja Sekarang
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                {{-- Left: Cart Items List --}}
                <div class="lg:col-span-2 space-y-6">
                    @foreach($cartItems as $item)
                    <div class="group relative bg-white rounded-[2.5rem] p-6 shadow-sm border border-gray-50 hover:shadow-xl hover:shadow-gray-200/50 transition-all duration-500">
                        <div class="flex flex-col sm:flex-row items-center gap-6">
                            
                            {{-- Image --}}
                            <div class="relative w-full sm:w-32 h-32 flex-shrink-0">
                                <img src="{{ $item->product->image_url ?? '/images/default.png' }}" 
                                     alt="{{ $item->product->name }}" 
                                     class="w-full h-full object-cover rounded-3xl border border-gray-50 group-hover:scale-105 transition-transform duration-500">
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 text-center sm:text-left">
                                <h2 class="text-lg font-black text-gray-900 leading-tight mb-1">{{ $item->product->name }}</h2>
                                <p class="text-blue-600 font-bold mb-4">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                
                                <div class="flex items-center justify-center sm:justify-start gap-4">
                                    {{-- Modern Quantity Update --}}
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="inline-flex items-center bg-gray-50 rounded-2xl p-1 border border-gray-100">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="quantity" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}">

                                        <button type="button" 
                                                onclick="updateQuantity({{ $item->id }}, -1)"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white hover:shadow-sm text-gray-500 hover:text-blue-600 transition-all">−</button>
                                        
                                        <span class="w-12 text-center font-black text-gray-800" id="display-quantity-{{ $item->id }}">{{ $item->quantity }}</span>
                                        
                                        <button type="button" 
                                                onclick="updateQuantity({{ $item->id }}, 1)"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white hover:shadow-sm text-gray-500 hover:text-blue-600 transition-all">+</button>
                                    </form>

                                    {{-- Remove Button --}}
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Hapus dari keranjang?')"
                                                class="w-12 h-12 flex items-center justify-center rounded-2xl text-red-400 hover:bg-red-50 hover:text-red-600 transition-all">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Subtotal --}}
                            <div class="text-center sm:text-right min-w-[120px]">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Subtotal</p>
                                <p class="text-xl font-black text-gray-900">
                                    Rp {{ number_format($item->total_price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Right: Order Summary Sticky --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 bg-white rounded-[3rem] p-8 shadow-2xl shadow-blue-900/5 border border-gray-50 overflow-hidden">
                        {{-- Decorative gradient background --}}
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-400 to-indigo-600"></div>
                        
                        <h2 class="text-2xl font-black text-gray-900 mb-6">Ringkasan Pesanan</h2>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between text-gray-500 font-medium">
                                <span>Total Harga ({{ $cartItems->sum('quantity') }} produk)</span>
                                <span>Rp {{ number_format($cartItems->sum('total_price'), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-gray-500 font-medium pb-4 border-b border-gray-100">
                                <span>Pengiriman</span>
                                <span class="text-green-500 font-bold uppercase text-xs tracking-widest mt-1">Gratis Ongkir</span>
                            </div>
                            
                            <div class="pt-2">
                                <div class="flex justify-between items-end mb-8">
                                    <span class="text-gray-900 font-bold">Total Tagihan</span>
                                    <span class="text-3xl font-black text-blue-600 tracking-tighter">
                                        Rp {{ number_format($cartItems->sum('total_price'), 0, ',', '.') }}
                                    </span>
                                </div>

                                <a href="{{ route('checkout.page') }}" 
                                   class="group relative block w-full bg-blue-600 text-white py-5 rounded-[2rem] font-black text-center text-lg shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all duration-300 overflow-hidden">
                                    <span class="relative z-10">Lanjut ke Checkout</span>
                                    <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                                </a>
                                
                                <div class="mt-6 flex items-center justify-center gap-2 text-[11px] text-gray-400 font-bold uppercase tracking-widest">
                                    <i class="fa-solid fa-shield-halved"></i>
                                    Keamanan Terjamin
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif
    </div>
</div>

<script>
function updateQuantity(itemId, delta) {
    const input = document.getElementById('quantity-' + itemId);
    const display = document.getElementById('display-quantity-' + itemId);
    let value = parseInt(input.value);
    let newValue = value + delta;

    if (newValue < 1) return;

    // Tambahkan efek loading sederhana pada tampilan
    display.classList.add('opacity-30');
    
    input.value = newValue;
    display.textContent = newValue;

    // Submit form otomatis
    input.form.submit();
}
</script>

<style>
    /* Haluskan scroll dan hilangkan spinner input */
    html { scroll-behavior: smooth; }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
</style>
@endsection