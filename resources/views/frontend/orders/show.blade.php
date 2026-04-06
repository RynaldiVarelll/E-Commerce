@extends('layouts.frontend')
@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-900/50 text-green-800 dark:text-green-400 px-6 py-4 rounded-3xl flex items-center shadow-sm animate-fade-in">
                <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-400 px-6 py-4 rounded-3xl flex items-center shadow-sm animate-fade-in">
                <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-400 px-6 py-4 rounded-3xl shadow-sm animate-fade-in">
                <div class="flex items-center mb-2">
                    <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
                    <span class="font-bold">Terjadi kesalahan:</span>
                </div>
                <ul class="list-disc list-inside text-sm font-medium ml-8">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ route('orders.index') }}" class="group inline-flex items-center text-blue-600 dark:text-blue-400 font-bold text-sm hover:text-blue-800 dark:hover:text-blue-300 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> 
                Kembali ke Riwayat Pesanan
            </a>
        </div>

        {{-- Header & Status --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4 pb-8 border-b border-gray-100/50 dark:border-gray-800/50">
            <div>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight leading-none">
                    Detail Pesanan <span class="text-blue-600 dark:text-blue-400">#{{ $transaction->id }}</span>
                </h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium mt-3 italic flex items-center gap-2 text-sm">
                    <i class="fa-solid fa-calendar-day text-blue-400 dark:text-blue-500"></i>
                    Dipesan pada {{ $transaction->created_at->format('d F Y, H:i') }} WIB
                </p>
            </div>
            
            <div>
                @php
                    $statusClasses = [
                        'pending'   => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
                        'confirmed' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                        'shipped'   => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
                        'completed' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                        'cancelled' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                    ];
                @endphp
                <span class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] {{ $statusClasses[$transaction->status] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400' }} shadow-sm">
                    Status: {{ $transaction->status }}
                </span>
            </div>
        </div>
        
        {{-- Progress Status Bar --}}
        <div class="mb-12 glass-panel rounded-[3rem] p-8 md:p-12 relative overflow-hidden">
            {{-- Background patterns --}}
            <div class="absolute -left-10 -top-10 w-40 h-40 bg-blue-50/50 dark:bg-blue-900/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-50/50 dark:bg-indigo-900/20 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-10">
                    <h2 class="text-xs font-black uppercase tracking-[0.25em] text-gray-400 dark:text-gray-500">Order Tracking</h2>
                    @if($transaction->status == 'cancelled')
                        <div class="flex items-center gap-2 text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 px-4 py-1.5 rounded-full border border-red-100 dark:border-red-900/50">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 dark:bg-red-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500 dark:bg-red-600"></span>
                            </span>
                            <span class="text-[10px] font-black uppercase tracking-widest">Pesanan Dibatalkan</span>
                        </div>
                    @endif
                </div>

                @php
                    $stages = [
                        ['id' => 'confirmed', 'label' => 'Konfirmasi', 'icon' => 'fa-check-circle', 'desc' => 'Pembayaran Berhasil'],
                        ['id' => 'shipped', 'label' => 'Pengiriman', 'icon' => 'fa-truck-fast', 'desc' => 'Sedang Dikirim'],
                        ['id' => 'completed', 'label' => 'Selesai', 'icon' => 'fa-box-open', 'desc' => 'Pesanan Diterima'],
                    ];
                    
                    $currentStatus = $transaction->status;
                    $statusOrder = ['pending' => 0, 'confirmed' => 1, 'shipped' => 2, 'completed' => 3, 'cancelled' => -1];
                    $currentPriority = $statusOrder[$currentStatus] ?? 0;
                @endphp

                <div class="relative">
                    {{-- Progress Line --}}
                    <div class="absolute top-1/2 left-0 w-full h-[2px] bg-gray-100 dark:bg-gray-700 -translate-y-1/2 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-600 transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(37,99,235,0.4)]" 
                             style="width: {{ $currentPriority <= 1 ? '0' : ($currentPriority == 2 ? '50' : '100') }}%">
                        </div>
                    </div>

                    {{-- Step Points --}}
                    <div class="relative flex justify-between items-center">
                        @foreach($stages as $index => $stage)
                            @php
                                $isCompleted = $currentPriority >= ($index + 1);
                                $isActive = $currentPriority == ($index + 1);
                                $isFailed = $currentStatus == 'cancelled' && $currentPriority < ($index + 1);
                            @endphp
                            
                            <div class="flex flex-col items-center group relative">
                                <div class="w-16 h-16 md:w-20 md:h-20 rounded-[1.75rem] flex items-center justify-center transition-all duration-500 shadow-xl border-4
                                    {{ $isCompleted ? 'bg-blue-600 border-white dark:border-gray-800 text-white rotate-6' : 'bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-700 text-gray-300 dark:text-gray-600' }}
                                    {{ $isActive ? 'ring-8 ring-blue-50/50 dark:ring-blue-900/30 scale-110 !rotate-0' : '' }}
                                    {{ $isFailed && $index == 0 ? 'bg-red-50 dark:bg-red-900/20 border-red-100 dark:border-red-900/50 text-red-400 opacity-50' : '' }}">
                                    
                                    <i class="fa-solid {{ $stage['icon'] }} text-xl md:text-2xl transition-transform group-hover:scale-110"></i>
                                    
                                    @if($isActive)
                                        <div class="absolute -inset-1 bg-blue-400 rounded-[1.75rem] blur opacity-20 animate-pulse"></div>
                                    @endif
                                </div>
                                
                                <div class="mt-6 text-center">
                                    <p class="text-[10px] md:text-[11px] font-black uppercase tracking-[0.2em] {{ $isCompleted ? 'text-gray-900 dark:text-white' : 'text-gray-400 dark:text-gray-500' }}">
                                        {{ $stage['label'] }}
                                    </p>
                                    <p class="text-[9px] font-medium text-gray-400 dark:text-gray-500 mt-1 hidden md:block">
                                        {{ $stage['desc'] }}
                                    </p>
                                </div>

                                {{-- Tooltip --}}
                                @if($isActive)
                                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-gray-900 text-white text-[9px] font-black py-1.5 px-4 rounded-full uppercase tracking-widest whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity">
                                        Status Sekarang
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: Daftar Produk & Instruksi --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Card Produk --}}
                <div class="glass-panel rounded-[2.5rem] overflow-hidden">
                    <div class="p-6 border-b border-white/40 dark:border-gray-700 bg-white/20 dark:bg-gray-800/20 backdrop-blur-md">
                        <h2 class="font-black text-gray-900 dark:text-white uppercase text-xs tracking-widest">Produk yang Dibeli</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-50 dark:divide-gray-700/50">
                        @foreach($transaction->items as $item)
                        <div class="p-6 flex items-center gap-6">
                            {{-- Gambar Produk (Update: Menggunakan image_url) --}}
                            <div class="w-20 h-20 aspect-square bg-white/50 dark:bg-gray-700/50 backdrop-blur-md rounded-2xl overflow-hidden border border-white/60 dark:border-gray-600 flex-shrink-0 shadow-inner">
                                @if($item->product)
                                    <img src="{{ $item->product->image_url }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/200';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-gray-600">
                                        <i class="fa-solid fa-image text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Info Produk --}}
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 dark:text-white leading-tight text-lg">
                                    {{ $item->product->name ?? 'Produk Terhapus' }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Subtotal per Item --}}
                            <div class="text-right">
                                <p class="font-black text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Instruksi Pembayaran --}}
                @if($transaction->status == 'pending')
                <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-100 dark:shadow-none relative overflow-hidden">
                    {{-- Dekorasi Abstract --}}
                    <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                    
                    <div class="flex items-start gap-4 relative z-10">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-black text-xl mb-1 text-white">Instruksi Pembayaran</h3>
                            <p class="text-blue-100 mb-6 font-medium text-sm">Pilih bank dan transfer sesuai nominal total:</p>
                            
                            {{-- Pilihan Bank --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                                {{-- BCA --}}
                                <div class="bg-white/95 dark:bg-gray-800/40 backdrop-blur-md rounded-3xl p-5 text-gray-900 dark:text-white shadow-xl shadow-blue-900/10 border border-white dark:border-gray-700">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Bank BCA</span>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" class="h-3 filter dark:brightness-0 dark:invert" alt="BCA">
                                    </div>
                                    <p class="text-xl font-black tracking-tighter mb-0.5">123 456 7890</p>
                                    <p class="font-bold text-gray-400 dark:text-gray-500 uppercase text-[10px] tracking-wider">a.n. PT Invoify Indonesia</p>
                                </div>

                                {{-- Mandiri --}}
                                <div class="bg-white/95 dark:bg-gray-800/40 backdrop-blur-md rounded-3xl p-5 text-gray-900 dark:text-white shadow-xl shadow-blue-900/10 border border-white dark:border-gray-700">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Bank Mandiri</span>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1200px-Bank_Mandiri_logo_2016.svg.png" class="h-3 filter dark:brightness-0 dark:invert" alt="Mandiri">
                                    </div>
                                    <p class="text-xl font-black tracking-tighter mb-0.5">987 654 3210</p>
                                    <p class="font-bold text-gray-400 dark:text-gray-500 uppercase text-[10px] tracking-wider">a.n. PT Invoify Indonesia</p>
                                </div>

                                {{-- BNI --}}
                                <div class="bg-white/95 dark:bg-gray-800/40 backdrop-blur-md rounded-3xl p-5 text-gray-900 dark:text-white shadow-xl shadow-blue-900/10 border border-white dark:border-gray-700">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Bank BNI</span>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Bank_Negara_Indonesia_logo_%282004%29.svg/1280px-Bank_Negara_Indonesia_logo_%282004%29.svg.png" class="h-3 filter dark:brightness-0 dark:invert" alt="BNI">
                                    </div>
                                    <p class="text-xl font-black tracking-tighter mb-0.5">554 321 0987</p>
                                    <p class="font-bold text-gray-400 dark:text-gray-500 uppercase text-[10px] tracking-wider">a.n. PT Invoify Indonesia</p>
                                </div>

                                {{-- BRI --}}
                                <div class="bg-white/95 dark:bg-gray-800/40 backdrop-blur-md rounded-3xl p-5 text-gray-900 dark:text-white shadow-xl shadow-blue-900/10 border border-white dark:border-gray-700">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">Bank BRI</span>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_2020.svg/1200px-BRI_2020.svg.png" class="h-3 filter dark:brightness-0 dark:invert" alt="BRI">
                                    </div>
                                    <p class="text-xl font-black tracking-tighter mb-0.5">001 2345 6789</p>
                                    <p class="font-bold text-gray-400 dark:text-gray-500 uppercase text-[10px] tracking-wider">a.n. PT Invoify Indonesia</p>
                                </div>
                            </div>

                            {{-- Tombol Konfirmasi Bayar --}}
                            <div class="mt-6">
                                <button type="button" onclick="showPaymentModal()" 
                                        class="w-full bg-white text-blue-600 py-4 rounded-2xl font-black text-sm text-center flex items-center justify-center hover:bg-blue-50 transition-all active:scale-95 shadow-xl shadow-blue-900/20 dark:shadow-none">
                                    <i class="fa-solid fa-credit-card mr-2"></i> Konfirmasi Pembayaran
                                </button>
                                <p class="text-center text-[10px] text-blue-200 mt-3 font-bold uppercase tracking-widest">Klik jika Anda sudah melakukan transfer</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- KOLOM KANAN: Ringkasan & Info --}}
            <div class="space-y-6">
                {{-- CARD: INFO TOKO & PEMBELI --}}
                <div class="glass-panel rounded-[2.5rem] p-8 space-y-8 animate-fade-in-up">
                    {{-- Detail Toko --}}
                    <div class="relative group">
                        <div class="absolute -right-2 -top-2 w-10 h-10 bg-blue-50 dark:bg-blue-900/20 rounded-full flex items-center justify-center text-blue-400 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <i class="fa-solid fa-store text-xs"></i>
                        </div>
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-600 dark:text-blue-400 mb-5 flex items-center gap-2">
                             <i class="fa-solid fa-shop"></i> Informasi Penjual
                        </h3>
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 flex-shrink-0 aspect-square bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center overflow-hidden shadow-lg shadow-blue-200 dark:shadow-none border-2 border-white dark:border-gray-700 ring-4 ring-blue-50 dark:ring-blue-900/20 transition-transform group-hover:rotate-3">
                                <img src="{{ $transaction->seller->profile_photo_url }}" 
                                     alt="{{ $transaction->seller->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <p class="font-black text-gray-900 dark:text-white leading-tight mb-1 truncate text-lg uppercase tracking-tighter italic">
                                    {{ $transaction->seller->name }}
                                </p>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest truncate">Verified Seller</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative py-2">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-dashed border-gray-100 dark:border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white/40 dark:bg-gray-800/40 backdrop-blur-sm px-3 text-gray-300 dark:text-gray-500 uppercase text-[8px] font-black tracking-[0.4em]">Connection</span>
                        </div>
                    </div>

                    {{-- Detail Pembeli --}}
                    <div class="group">
                        <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500 mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-user-check"></i> Detail Penerima
                        </h3>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 flex-shrink-0 aspect-square bg-white dark:bg-gray-700 rounded-2xl flex items-center justify-center overflow-hidden shadow-md border border-gray-100 dark:border-gray-600 group-hover:-rotate-3 transition-transform">
                                <img src="{{ $transaction->user->profile_photo_url }}" 
                                     alt="{{ $transaction->user->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="min-w-0">
                                <p class="font-black text-gray-900 dark:text-white leading-tight mb-0.5 truncate text-sm uppercase tracking-tight">
                                    {{ $transaction->user->name }}
                                </p>
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 font-bold tracking-wider truncate mb-1">{{ $transaction->user->email }}</p>
                            </div>
                        </div>
                        
                        {{-- Badge Role --}}
                        <div class="inline-flex items-center px-4 py-1.5 bg-gray-50 dark:bg-gray-700/50 text-[9px] font-black text-gray-500 dark:text-gray-400 rounded-full border border-gray-100 dark:border-gray-600 uppercase tracking-widest">
                            Customer Profile
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-[2.5rem] p-8 sticky top-28 animate-fade-in-up delay-100">
                    <h2 class="font-black text-gray-900 dark:text-white uppercase text-xs tracking-widest mb-6 border-b border-white/40 dark:border-gray-700 pb-4">Ringkasan Biaya</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Kurir</span>
                            <span class="font-bold text-gray-900 dark:text-white text-right">{{ $transaction->shippingMethod->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Layanan</span>
                            <span class="font-bold text-gray-900 dark:text-white">{{ $transaction->shippingMethod->service ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Metode Bayar</span>
                            <span class="font-bold text-gray-900 dark:text-white uppercase tracking-tighter">{{ $transaction->payment_method }}</span>
                        </div>
                        
                        <div class="py-4 border-t border-dashed border-gray-200 dark:border-gray-700 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-900 dark:text-white font-black italic uppercase text-xs">Total Bayar</span>
                                <span class="text-2xl font-black text-blue-600 dark:text-blue-400 tracking-tighter">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('transactions.print-invoice', $transaction->id) }}" target="_blank" 
                           class="w-full bg-gray-900 dark:bg-gray-700 text-white py-4 rounded-2xl font-black text-sm text-center block hover:bg-blue-600 dark:hover:bg-blue-500 transition-all active:scale-95 shadow-lg shadow-gray-200 dark:shadow-none">
                            <i class="fa-solid fa-print mr-2"></i> Cetak Invoice
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL KONFIRMASI PEMBAYARAN --}}
    <div id="paymentModal" class="fixed inset-0 z-[150] hidden">
        {{-- Overlay dengan Blur Luar Biasa --}}
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-all duration-500" onclick="closePaymentModal()"></div>
        
        <div class="relative flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-[3rem] w-full max-w-md overflow-hidden shadow-2xl shadow-black/20 animate-scale-up border border-white dark:border-gray-700">
                <div class="p-8 text-center">
                    {{-- Icon Animasi --}}
                    <div class="w-20 h-20 bg-blue-100 dark:bg-blue-900/40 rounded-3xl flex items-center justify-center mx-auto mb-6 text-blue-600 dark:text-blue-400 relative">
                        <i class="fa-solid fa-lock text-3xl"></i>
                        <div class="absolute inset-0 bg-blue-400 dark:bg-blue-500 rounded-3xl animate-ping opacity-20"></div>
                    </div>

                    <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2 tracking-tight uppercase">Verifikasi Keamanan</h2>
                    <p class="text-gray-500 dark:text-gray-400 font-medium text-sm mb-8">Masukkan password akun Anda untuk melanjutkan proses konfirmasi pembayaran.</p>

                    <form action="{{ route('orders.pay', $transaction->id) }}" method="POST" id="payForm">
                        @csrf
                        <div class="relative text-left mb-6">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500 mb-2 block ml-1">Password Anda</label>
                            <div class="relative">
                                <i class="fa-solid fa-key absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                                <input type="password" name="password" required placeholder="••••••••"
                                       class="w-full pl-14 pr-6 py-4 bg-gray-50 dark:bg-gray-700 dark:text-white border-none dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-600 transition-all font-bold">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" onclick="closePaymentModal()"
                                    class="py-4 rounded-2xl font-black text-sm text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all uppercase tracking-widest">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="py-4 bg-gray-900 dark:bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-600 dark:hover:bg-blue-700 transition-all shadow-xl shadow-gray-200 dark:shadow-none uppercase tracking-widest active:scale-95">
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
    .delay-100 { animation-delay: 0.1s; }

    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }

    @keyframes scale-up {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-scale-up { animation: scale-up 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    </style>

    <script>
    function showPaymentModal() {
        const modal = document.getElementById('paymentModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePaymentModal() {
        const modal = document.getElementById('paymentModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    </script>
@endsection