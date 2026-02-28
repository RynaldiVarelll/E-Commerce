<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-black text-gray-900 tracking-tight uppercase italic">
                My <span class="text-blue-600">Orders.</span>
            </h1>
            <p class="text-gray-500 font-medium mt-1">Pantau status pesanan dan riwayat belanja Anda.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
            {{-- Tabel yang sama seperti sebelumnya --}}
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-400 text-[11px] font-black uppercase tracking-[0.2em]">
                            <th class="px-8 py-5 text-left">Order ID</th>
                            <th class="px-8 py-5 text-left">Tanggal</th>
                            <th class="px-8 py-5 text-left">Total</th>
                            <th class="px-8 py-5 text-left">Status</th>
                            <th class="px-8 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-8 py-6 font-bold text-gray-900">#{{ $order->id }}</td>
                                <td class="px-8 py-6 text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
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
</x-app-layout>