@extends('layouts.frontend')

@section('content')
<div class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4">
        
        {{-- Header & Back Button --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('cart.index') }}" class="group inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 transition-colors mb-2">
                    <i class="fa-solid fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Keranjang
                </a>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight">Checkout</h1>
            </div>
            <div class="hidden md:block">
                <span class="text-sm font-bold uppercase tracking-widest text-gray-400">Langkah 2 dari 2</span>
            </div>
        </div>

        @if (session('error'))
            <div class="bg-red-50 border border-red-100 text-red-700 px-6 py-4 rounded-2xl mb-6 flex items-center">
                <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="bg-white p-12 rounded-[2.5rem] shadow-sm border border-gray-100 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-cart-shopping text-3xl text-gray-300"></i>
                </div>
                <p class="text-gray-500 text-lg font-medium">Keranjang kamu masih kosong nih.</p>
                <a href="{{ route('product.index') }}" class="inline-block mt-4 text-blue-600 font-bold hover:underline">Ayo belanja sekarang!</a>
            </div>
        @else
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    {{-- Left Column: Product List --}}
                    <div class="lg:col-span-2 space-y-4">
                        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                                <h3 class="font-bold text-gray-800">Rincian Pesanan</h3>
                            </div>
                            
                            <div class="divide-y divide-gray-100">
                                @php $total = 0; @endphp
                                @foreach($cartItems as $index => $item)
                                    @php 
                                        $subtotal = $item->product->price * $item->quantity; 
                                        $total += $subtotal;
                                    @endphp
                                    <div class="p-6 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-gray-50/50 transition-colors">
                                        {{-- Product Info --}}
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900 mb-1">{{ $item->product->name }}</h4>
                                            <p class="text-sm text-gray-500">Harga Satuan: Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product->id }}">
                                        </div>

                                        {{-- Quantity & Subtotal --}}
                                        <div class="flex items-center justify-between sm:justify-end gap-8">
                                            <div class="flex items-center bg-gray-50 rounded-xl px-3 py-1 border border-gray-200">
                                                <span class="text-xs font-bold text-gray-400 mr-2 uppercase">Qty</span>
                                                <input type="number" name="items[{{ $index }}][quantity]" 
                                                       value="{{ $item->quantity }}" min="1"
                                                       class="w-10 bg-transparent font-bold text-gray-800 focus:outline-none text-center">
                                            </div>
                                            <div class="text-right min-w-[120px]">
                                                <p class="text-xs text-gray-400 font-bold uppercase tracking-tighter">Subtotal</p>
                                                <p class="font-black text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Payment Method Placeholder (UX Tips) --}}
                        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-8">
                            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fa-solid fa-shield-check text-green-500 mr-2"></i>
                                Pembayaran Aman
                            </h3>
                            <p class="text-sm text-gray-500 italic">Pesanan Anda akan diproses secara aman. Pastikan jumlah pesanan sudah sesuai sebelum menekan tombol konfirmasi.</p>
                        </div>
                    </div>

                    {{-- Right Column: Summary Sidebar --}}
                    <div class="lg:sticky lg:top-8">
                        <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-200 relative overflow-hidden">
                            {{-- Decorative Circle --}}
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                            
                            <h3 class="text-xl font-bold mb-6 relative z-10">Ringkasan Biaya</h3>
                            
                            <div class="space-y-4 relative z-10">
                                <div class="flex justify-between text-blue-100">
                                    <span>Total Item</span>
                                    <span class="font-bold">{{ $cartItems->sum('quantity') }}</span>
                                </div>
                                <div class="flex justify-between text-blue-100 border-b border-blue-500/50 pb-4">
                                    <span>Biaya Admin</span>
                                    <span class="font-bold">Gratis</span>
                                </div>
                                <div class="pt-2">
                                    <p class="text-sm text-blue-200 uppercase font-bold tracking-widest mb-1">Total Pembayaran</p>
                                    <p class="text-4xl font-black">
                                        Rp {{ number_format($total, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full mt-8 bg-white text-blue-600 py-4 rounded-2xl font-black text-lg hover:bg-blue-50 transition-all duration-300 transform active:scale-95 shadow-lg">
                                Konfirmasi & Bayar
                            </button>
                            
                            <p class="text-center text-[10px] text-blue-200 mt-4 uppercase tracking-[0.2em] font-medium">
                                <i class="fa-solid fa-lock mr-1"></i> Enkripsi Keamanan 256-bit
                            </p>
                        </div>
                    </div>

                </div>
            </form>
        @endif
    </div>
</div>
@endsection