@extends('layouts.frontend')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight uppercase italic">
                My <span class="text-blue-600 dark:text-blue-400">Orders.</span>
            </h1>
            <p class="text-gray-500 dark:text-gray-400 font-medium mt-1">Pantau status pesanan dan riwayat belanja Anda.</p>
            <a href="{{ route('product.index') }}" 
               class="group mt-2 inline-flex items-center text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
               <i class="fa-solid fa-chevron-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
               Lanjut Belanja
            </a>
        </div>



        <div class="glass-panel rounded-[2.5rem] overflow-hidden">
            {{-- Tabel yang sama seperti sebelumnya --}}
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-gray-800/50 text-gray-400 dark:text-gray-500 text-[11px] font-black uppercase tracking-[0.2em]">
                            <th class="px-8 py-5 text-left">Order ID</th>
                            <th class="px-8 py-5 text-left">Tanggal</th>
                            <th class="px-8 py-5 text-left">Toko / Seller</th>
                            <th class="px-8 py-5 text-left">Total</th>
                            <th class="px-8 py-5 text-left">Status</th>
                            <th class="px-8 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-white/40 dark:hover:bg-gray-700/40 transition-colors">
                                <td class="px-8 py-6 font-bold text-gray-900 dark:text-white">#{{ $order->id }}</td>
                                <td class="px-8 py-6 text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-8 py-6 text-sm font-bold text-gray-700 dark:text-gray-300">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-store mr-2 text-blue-500"></i>
                                        {{ $order->seller->name ?? 'Official Store' }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 font-black text-gray-700 dark:text-gray-300">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-8 py-6">
                                    {{-- Badge Status --}}
                                    @php
                                        $statusClasses = [
                                            'pending'   => 'bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400',
                                            'confirmed' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400',
                                            'shipped'   => 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400',
                                            'completed' => 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400',
                                            'cancelled' => 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400',
                                        ];
                                        $colorClass = $statusClasses[$order->status] ?? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300';
                                    @endphp
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $colorClass }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center flex justify-center gap-3">
                                    {{-- TOMBOL DETAIL (Ini yang harus diklik) --}}
                                    <a href="{{ route('orders.show', $order->id) }}" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-900 dark:bg-gray-700 text-white rounded-xl text-xs font-black hover:bg-blue-600 dark:hover:bg-blue-500 transition-all">
                                        <i class="fa-solid fa-eye mr-2"></i> DETAIL
                                    </a>

                                    {{-- TOMBOL INVOICE --}}
                                    <a href="{{ route('transactions.print-invoice', $order->id) }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 rounded-xl text-xs font-black hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                                        <i class="fa-solid fa-print mr-2"></i> INVOICE
                                    </a>

                                    {{-- TOMBOL HAPUS --}}
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat pesanan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/50 text-red-600 dark:text-red-400 rounded-xl text-xs font-black hover:bg-red-600 hover:text-white dark:hover:text-white transition-all">
                                            <i class="fa-solid fa-trash mr-2"></i> HAPUS
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 dark:bg-gray-800 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa-solid fa-receipt text-3xl text-gray-300 dark:text-gray-600"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum ada pesanan</h3>
                                        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">Sepertinya Anda belum pernah berbelanja di sini.</p>
                                        <a href="{{ route('product.index') }}" 
                                           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl text-sm font-black hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 dark:shadow-none">
                                            Mulai Belanja Sekarang
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection