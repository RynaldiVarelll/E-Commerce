<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Tombol Kembali --}}
        <div class="mb-6">
            <a href="{{ route('orders.index') }}" class="group inline-flex items-center text-blue-600 font-bold text-sm hover:text-blue-800 transition-colors">
                <i class="fa-solid fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> 
                Kembali ke Riwayat Pesanan
            </a>
        </div>

        {{-- Header & Status --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4 border-b border-gray-100 pb-8">
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
                <div class="bg-white rounded-[2.5rem] border border-gray-100 overflow-hidden shadow-sm">
                    <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                        <h2 class="font-black text-gray-900 uppercase text-xs tracking-widest">Produk yang Dibeli</h2>
                    </div>
                    
                    <div class="divide-y divide-gray-50">
                        @foreach($transaction->items as $item)
                        <div class="p-6 flex items-center gap-6">
                            {{-- Gambar Produk (Update: Menggunakan image_url) --}}
                            <div class="w-20 h-20 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex-shrink-0 shadow-inner">
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
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-100">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-wallet text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-xl mb-1">Instruksi Pembayaran</h3>
                            <p class="text-blue-100 mb-6 font-medium text-sm">Silakan transfer sesuai nominal total ke rekening di bawah ini:</p>
                            
                            <div class="bg-white rounded-3xl p-6 text-gray-900">
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-600">Bank Transfer</span>
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/1200px-Bank_Central_Asia.svg.png" class="h-4" alt="BCA">
                                </div>
                                <p class="text-3xl font-black tracking-tighter mb-1">123 456 7890</p>
                                <p class="font-bold text-gray-500 uppercase text-xs tracking-wider">a.n. PT Invoify Indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- KOLOM KANAN: Ringkasan --}}
            <div class="space-y-6">
                <div class="bg-white rounded-[2.5rem] border border-gray-100 p-8 shadow-sm sticky top-24">
                    <h2 class="font-black text-gray-900 uppercase text-xs tracking-widest mb-6 border-b border-gray-50 pb-4">Ringkasan Biaya</h2>
                    
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
</x-app-layout>