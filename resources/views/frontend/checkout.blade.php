@extends('layouts.frontend')

@section('content')
<div class="min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4">
        
        {{-- Header --}}
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

        @if($cartItems->isEmpty())
            <div class="glass-panel p-12 rounded-[2.5rem] text-center">
                <p class="text-gray-500 text-lg font-medium">Keranjang kamu masih kosong.</p>
            </div>
        @else

            @php $subtotalTotal = 0; @endphp

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    {{-- LEFT COLUMN --}}
                    <div class="lg:col-span-2 space-y-6">

                        {{-- Rincian Pesanan --}}
                        <div class="glass-panel rounded-[2rem] overflow-hidden">
                            <div class="p-6 border-b border-white/40 bg-white/20 backdrop-blur-md">
                                <h3 class="font-bold text-gray-800">Rincian Pesanan</h3>
                            </div>

                            <div class="divide-y divide-gray-100">
                                @foreach($cartItems as $index => $item)
                                    @php 
                                        $itemSubtotal = $item->product->price * $item->quantity; 
                                        $subtotalTotal += $itemSubtotal;
                                    @endphp

                                    <div class="p-6 flex justify-between items-center">
                                        <div>
                                            <h4 class="font-bold text-gray-900">
                                                {{ $item->product->name }}
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                Rp {{ number_format($item->product->price, 0, ',', '.') }} x {{ $item->quantity }}
                                            </p>
                                            <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product->id }}">
                                            <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item->quantity }}">
                                        </div>
                                        <div class="font-black text-gray-900">
                                            Rp {{ number_format($itemSubtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- SHIPPING METHOD --}}
                        <div class="glass-panel rounded-[2rem] p-8">
                            <h3 class="font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fa-solid fa-truck text-blue-500 mr-2"></i>
                                Pilih Metode Pengiriman
                            </h3>

                            <div class="space-y-4">
                                @foreach($shippingMethods as $method)
                                    <label class="flex items-center justify-between border border-white/60 bg-white/30 backdrop-blur-sm rounded-2xl p-4 cursor-pointer hover:bg-white/50 transition-all group has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50 has-[:checked]:ring-2 has-[:checked]:ring-blue-400/20">
                                        <div class="flex items-center gap-3">
                                            <input type="radio"
                                                name="shipping_method_id"
                                                value="{{ $method->id }}"
                                                data-cost="{{ $method->cost }}"
                                                class="shipping-radio w-5 h-5 text-blue-600"
                                                {{ $loop->first ? 'checked' : '' }}
                                                required>
                                            <div>
                                                <p class="font-bold text-gray-800">{{ $method->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $method->service }}</p>
                                            </div>
                                        </div>
                                        <span class="font-bold text-gray-900">
                                            Rp {{ number_format($method->cost, 0, ',', '.') }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- PAYMENT INFO --}}
                        <div class="glass-panel rounded-[2rem] p-8">
                            <h3 class="font-bold text-gray-800 mb-6 flex items-center">
                                <i class="fa-solid fa-shield-check text-green-500 mr-2"></i>
                                Pembayaran Aman & Terpercaya
                            </h3>
                            <p class="text-sm text-gray-500 mb-6">
                                Pesanan Anda akan diproses setelah pembayaran dikonfirmasi. Kami mendukung berbagai bank di Indonesia:
                            </p>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 opacity-70 grayscale hover:grayscale-0 transition-all">
                                <div class="bg-white/50 p-3 rounded-xl flex items-center justify-center border border-white/40">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" class="h-4" alt="BCA">
                                </div>
                                <div class="bg-white/50 p-3 rounded-xl flex items-center justify-center border border-white/40">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1200px-Bank_Mandiri_logo_2016.svg.png" class="h-4" alt="Mandiri">
                                </div>
                                <div class="bg-white/50 p-3 rounded-xl flex items-center justify-center border border-white/40">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Bank_Negara_Indonesia_logo_%282004%29.svg/1280px-Bank_Negara_Indonesia_logo_%282004%29.svg.png" class="h-4" alt="BNI">
                                </div>
                                <div class="bg-white/50 p-3 rounded-xl flex items-center justify-center border border-white/40">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_2020.svg/1200px-BRI_2020.svg.png" class="h-4" alt="BRI">
                                </div>
                            </div>
                            
                            <p class="text-[11px] text-gray-400 mt-6 font-medium italic">
                                *Instruksi detail nomor rekening akan diberikan setelah Anda menekan tombol "Konfirmasi & Bayar".
                            </p>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN (SUMMARY) --}}
                    <div class="lg:sticky lg:top-28">
                        <div class="bg-blue-600/90 backdrop-blur-2xl rounded-[2.5rem] p-8 text-white shadow-2xl shadow-blue-900/10 relative overflow-hidden border border-white/20">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                            
                            @php
                                $initialShipping = $shippingMethods->first() ? $shippingMethods->first()->cost : 0;
                                $initialGrandTotal = $subtotalTotal + $initialShipping;
                            @endphp

                            <h3 class="text-xl font-bold mb-6">Ringkasan Biaya</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between text-blue-100">
                                    <span>Subtotal</span>
                                    <span id="display-subtotal" data-value="{{ $subtotalTotal }}">
                                        Rp {{ number_format($subtotalTotal, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="flex justify-between text-blue-100">
                                    <span>Ongkir</span>
                                    <span id="display-shipping">
                                        Rp {{ number_format($initialShipping, 0, ',', '.') }}
                                    </span>
                                </div>

                                <div class="border-t border-blue-400 pt-4 mt-4">
                                    <p class="text-sm text-blue-200 uppercase font-bold tracking-wider">
                                        Total Pembayaran
                                    </p>
                                    <p class="text-4xl font-black mt-1" id="display-grand-total">
                                        Rp {{ number_format($initialGrandTotal, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full mt-8 bg-white text-blue-600 py-4 rounded-2xl font-black text-lg hover:bg-blue-50 transition-all transform active:scale-95 shadow-lg">
                                Konfirmasi & Bayar
                            </button>

                        </div>
                    </div>

                </div>
            </form>
        @endif
    </div>
</div>

{{-- JAVASCRIPT UNTUK UPDATE ANGKA OTOMATIS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shippingRadios = document.querySelectorAll('.shipping-radio');
        const displayShipping = document.getElementById('display-shipping');
        const displayGrandTotal = document.getElementById('display-grand-total');
        const subtotalElement = document.getElementById('display-subtotal');
        
        // Ambil nilai subtotal mentah dari data-attribute
        const subtotalValue = parseInt(subtotalElement.getAttribute('data-value'));

        // Fungsi Helper untuk format mata uang Rupiah
        const formatRupiah = (number) => {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(number);
        };

        shippingRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    // Ambil biaya ongkir dari atribut data-cost
                    const selectedShippingCost = parseInt(this.getAttribute('data-cost'));
                    const newGrandTotal = subtotalValue + selectedShippingCost;

                    // Update tampilan teks di layar
                    displayShipping.innerText = formatRupiah(selectedShippingCost);
                    displayGrandTotal.innerText = formatRupiah(newGrandTotal);
                    
                    // Efek visual sedikit (optional)
                    displayGrandTotal.classList.add('animate-pulse');
                    setTimeout(() => displayGrandTotal.classList.remove('animate-pulse'), 500);
                }
            });
        });
    });
</script>

<style>
    /* Menghilangkan radio button default dan styling custom jika diperlukan */
    .shipping-radio {
        cursor: pointer;
    }
</style>
@endsection