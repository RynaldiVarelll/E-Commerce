@extends('layouts.frontend')
@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-black text-gray-900 tracking-tight uppercase italic">
                My <span class="text-blue-600">Orders.</span>
            </h1>
            <p class="text-gray-500 font-medium mt-1">Pantau status pesanan dan riwayat belanja Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-600 px-6 py-4 rounded-2xl flex items-center font-bold">
                <i class="fa-solid fa-circle-check mr-3 text-xl"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="glass-panel rounded-[2.5rem] overflow-hidden">
            {{-- Tabel yang sama seperti sebelumnya --}}
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-400 text-[11px] font-black uppercase tracking-[0.2em]">
                            <th class="px-8 py-5 text-left">Order ID</th>
                            <th class="px-8 py-5 text-left">Tanggal</th>
                            <th class="px-8 py-5 text-left">Toko / Seller</th>
                            <th class="px-8 py-5 text-left">Total</th>
                            <th class="px-8 py-5 text-left">Status</th>
                            <th class="px-8 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-white/40 transition-colors">
                                <td class="px-8 py-6 font-bold text-gray-900">#{{ $order->id }}</td>
                                <td class="px-8 py-6 text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="px-8 py-6 text-sm font-bold text-gray-700">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-store mr-2 text-blue-500"></i>
                                        {{ $order->seller->name ?? 'Official Store' }}
                                    </div>
                                </td>
                                <td class="px-8 py-6 font-black text-gray-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-8 py-6">
                                    {{-- Badge Status --}}
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center flex justify-center gap-3">
                                    {{-- TOMBOL DETAIL (Ini yang harus diklik) --}}
                                    <a href="{{ route('orders.show', $order->id) }}" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-xl text-xs font-black hover:bg-blue-600 transition-all">
                                        <i class="fa-solid fa-eye mr-2"></i> DETAIL
                                    </a>

                                    {{-- TOMBOL INVOICE --}}
                                    <a href="{{ route('transactions.print-invoice', $order->id) }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-xs font-black hover:bg-gray-50 transition-all">
                                        <i class="fa-solid fa-print mr-2"></i> INVOICE
                                    </a>

                                    {{-- TOMBOL HAPUS --}}
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus riwayat pesanan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 text-red-600 rounded-xl text-xs font-black hover:bg-red-600 hover:text-white transition-all">
                                            <i class="fa-solid fa-trash mr-2"></i> HAPUS
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            {{-- Pesan jika kosong --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection