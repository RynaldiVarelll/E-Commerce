@extends('layouts.frontend')
@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-200 text-green-800 px-6 py-4 rounded-3xl flex items-center shadow-sm animate-fade-in">
                <i class="fa-solid fa-circle-check text-xl mr-3"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-3xl flex items-center shadow-sm animate-fade-in">
                <i class="fa-solid fa-circle-exclamation text-xl mr-3"></i>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-3xl shadow-sm animate-fade-in">
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
            <a href="{{ route('orders.index') }}" class="group inline-flex items-center text-blue-600 font-bold text-sm hover:text-blue-800 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> 
                Kembali ke Riwayat Pesanan
            </a>
        </div>

        {{-- Header & Status --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4 pb-8">
            <div>
                <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none">
                    Detail Pesanan <span class="text-blue-600">#{{ $transaction->id }}</span>
                </h1>
                <p class="text-gray-500 font-medium mt-2 italic">
                    Dipesan pada {{ $transaction->created_at->format('d F Y, H:i') }} WIB
                </p>
            </div>
            
            <div>
                @php
                    $statusClasses = [
                        'pending'   => 'bg-amber-100 text-amber-700',
                        'confirmed' => 'bg-blue-100 text-blue-700',
                        'shipped'   => 'bg-indigo-100 text-indigo-700',
                        'completed' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    ];
                @endphp
                <span class="px-6 py-2.5 rounded-full text-xs font-black uppercase tracking-[0.15em] {{ $statusClasses[$transaction->status] ?? 'bg-gray-100 text-gray-600' }}">
                    Status: {{ $transaction->status }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI: Daftar Produk & Instruksi --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- Card Produk --}}
                <div class="glass-panel rounded-[2.5rem] overflow-hidden">
                    <div class="p-6 border-b border-white/40 bg-white/20 backdrop-blur-md">
                        <h2 class="font-black text-gray-900 uppercase text-xs tracking-widest">Produk yang Dibeli</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-50">
                        @foreach($transaction->items as $item)
                        <div class="p-6 flex items-center gap-6">
                            {{-- Gambar Produk (Update: Menggunakan image_url) --}}
                            <div class="w-20 h-20 bg-white/50 backdrop-blur-md rounded-2xl overflow-hidden border border-white/60 flex-shrink-0 shadow-inner">
                                @if($item->product)
                                    <img src="{{ $item->product->image_url }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.src='https://via.placeholder.com/200';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <i class="fa-solid fa-image text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            
                            {{-- Info Produk --}}
                            <div class="flex-1">
                                <h3 class="font-bold text-gray-900 leading-tight text-lg">
                                    {{ $item->product->name ?? 'Produk Terhapus' }}
                                </h3>
                                <p class="text-sm text-gray-500 font-medium mt-1">
                                    {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Subtotal per Item --}}
                            <div class="text-right">
                                <p class="font-black text-gray-900">
                                    Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Instruksi Pembayaran --}}
                @if($transaction->status == 'pending')
                <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-100 relative overflow-hidden">
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
                                <div class="bg-white/95 backdrop-blur-md rounded-3xl p-5 text-gray-900 shadow-xl shadow-blue-900/10 border border-white">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600">Bank BCA</span>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" class="h-3" alt="BCA">
                                    </div>
                                    <p class="text-xl font-black tracking-tighter mb-0.5">123 456 7890</p>
                                    <p class="font-bold text-gray-400 uppercase text-[10px] tracking-wider">a.n. PT Invoify Indonesia</p>
                                </div>

                                {{-- Mandiri --}}
                                <div class="bg-white/95 backdrop-blur-md rounded-3xl p-5 text-gray-900 shadow-xl shadow-blue-900/10 border border-white">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600">Bank Mandiri</span>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/1200px-Bank_Mandiri_logo_2016.svg.png" class="h-3" alt="Mandiri">
                                    </div>
                                    <p class="text-xl font-black tracking-tighter mb-0.5">987 654 3210</p>
                                    <p class="font-bold text-gray-400 uppercase text-[10px] tracking-wider">a.n. PT Invoify Indonesia</p>
                                </div>

                                {{-- BNI --}}
                                <div class="bg-white/95 backdrop-blur-md rounded-3xl p-5 text-gray-900 shadow-xl shadow-blue-900/10 border border-white">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600">Bank BNI</span>
                                        <img src="https://upload.wikimedia.org/wikipedia/id/thumb/5/55/BNI_logo.svg/1200px-BNI_logo.svg.png" class="h-3" alt="BNI">
                                    </div>
                                    <p class="text-xl font-black tracking-tighter mb-0.5">554 321 0987</p>
                                    <p class="font-bold text-gray-400 uppercase text-[10px] tracking-wider">a.n. PT Invoify Indonesia</p>
                                </div>

                                {{-- BRI --}}
                                <div class="bg-white/95 backdrop-blur-md rounded-3xl p-5 text-gray-900 shadow-xl shadow-blue-900/10 border border-white">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[9px] font-black uppercase tracking-widest text-blue-600">Bank BRI</span>
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_2020.svg/1200px-BRI_2020.svg.png" class="h-3" alt="BRI">
                                    </div>
                                    <p class="text-xl font-black tracking-tighter mb-0.5">001 2345 6789</p>
                                    <p class="font-bold text-gray-400 uppercase text-[10px] tracking-wider">a.n. PT Invoify Indonesia</p>
                                </div>
                            </div>

                            {{-- Tombol Konfirmasi Bayar --}}
                            <div class="mt-6">
                                <button type="button" onclick="showPaymentModal()" 
                                        class="w-full bg-white text-blue-600 py-4 rounded-2xl font-black text-sm text-center flex items-center justify-center hover:bg-blue-50 transition-all active:scale-95 shadow-xl shadow-blue-900/20">
                                    <i class="fa-solid fa-credit-card mr-2"></i> Konfirmasi Pembayaran
                                </button>
                                <p class="text-center text-[10px] text-blue-200 mt-3 font-bold uppercase tracking-widest">Klik jika Anda sudah melakukan transfer</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- KOLOM KANAN: Ringkasan --}}
            <div class="space-y-6">
                <div class="glass-panel rounded-[2.5rem] p-8 sticky top-28">
                    <h2 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-6 border-b border-white/40 pb-4">Ringkasan Biaya</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium">Kurir</span>
                            <span class="font-bold text-gray-900 text-right">{{ $transaction->shippingMethod->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium">Layanan</span>
                            <span class="font-bold text-gray-900">{{ $transaction->shippingMethod->service ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 font-medium">Metode Bayar</span>
                            <span class="font-bold text-gray-900 uppercase tracking-tighter">{{ $transaction->payment_method }}</span>
                        </div>
                        
                        <div class="py-4 border-t border-dashed border-gray-200 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-900 font-black italic uppercase text-xs">Total Bayar</span>
                                <span class="text-2xl font-black text-blue-600 tracking-tighter">
                                    Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('transactions.print-invoice', $transaction->id) }}" target="_blank" 
                           class="w-full bg-gray-900 text-white py-4 rounded-2xl font-black text-sm text-center block hover:bg-blue-600 transition-all active:scale-95 shadow-lg shadow-gray-200">
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
            <div class="bg-white rounded-[3rem] w-full max-w-md overflow-hidden shadow-2xl shadow-black/20 animate-scale-up border border-white">
                <div class="p-8 text-center">
                    {{-- Icon Animasi --}}
                    <div class="w-20 h-20 bg-blue-100 rounded-3xl flex items-center justify-center mx-auto mb-6 text-blue-600 relative">
                        <i class="fa-solid fa-lock text-3xl"></i>
                        <div class="absolute inset-0 bg-blue-400 rounded-3xl animate-ping opacity-20"></div>
                    </div>

                    <h2 class="text-2xl font-black text-gray-900 mb-2 tracking-tight uppercase">Verifikasi Keamanan</h2>
                    <p class="text-gray-500 font-medium text-sm mb-8">Masukkan password akun Anda untuk melanjutkan proses konfirmasi pembayaran.</p>

                    <form action="{{ route('orders.pay', $transaction->id) }}" method="POST" id="payForm">
                        @csrf
                        <div class="relative text-left mb-6">
                            <label class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2 block ml-1">Password Anda</label>
                            <div class="relative">
                                <i class="fa-solid fa-key absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="password" name="password" required placeholder="••••••••"
                                       class="w-full pl-14 pr-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-600 transition-all font-bold">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" onclick="closePaymentModal()"
                                    class="py-4 rounded-2xl font-black text-sm text-gray-500 hover:bg-gray-100 transition-all uppercase tracking-widest">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="py-4 bg-gray-900 text-white rounded-2xl font-black text-sm hover:bg-blue-600 transition-all shadow-xl shadow-gray-200 uppercase tracking-widest active:scale-95">
                                Konfirmasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
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