@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-10">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">Shipping Delivery</h1>
            <p class="text-gray-500 mt-1 font-medium">Kelola metode pengiriman dan biaya ongkir secara dinamis.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.shipping-methods.create') }}" 
               class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all transform active:scale-95">
                <i class="fa-solid fa-plus mr-2"></i>
                Tambah Metode
            </a>
        </div>
    </div>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 px-6 py-4 rounded-2xl mb-6 flex items-center animate-fade-in">
            <i class="fa-solid fa-circle-check text-xl mr-3"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Shipping Methods Table --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex items-center justify-between">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">Daftar Ekspedisi</h2>
            <div class="flex gap-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <div class="w-3 h-3 bg-gray-200 rounded-full"></div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 text-[11px] font-black uppercase tracking-[0.2em]">
                        <th class="px-8 py-5 text-left">Kurir & Layanan</th>
                        <th class="px-8 py-5 text-left">Biaya Ongkir</th>
                        <th class="px-8 py-5 text-left text-center">Status</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($shippingMethods as $method)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black text-xs mr-4">
                                        <i class="fa-solid fa-truck"></i>
                                    </div>
                                    <div>
                                        <span class="block font-bold text-gray-900">{{ $method->name }}</span>
                                        <span class="text-xs text-gray-500 font-medium">{{ $method->service }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 font-black text-gray-700">
                                Rp {{ number_format($method->cost, 0, ',', '.') }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($method->is_active)
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 text-green-700">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-red-100 text-red-600">
                                        <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('admin.shipping-methods.edit', $method->id) }}" 
                                       class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition-all">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </a>

                                    <form action="{{ route('admin.shipping-methods.destroy', $method->id) }}" method="POST" onsubmit="return confirm('Hapus metode pengiriman ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-box-open text-4xl text-gray-200 mb-4"></i>
                                    <p class="text-gray-500 font-bold">Belum ada metode pengiriman.</p>
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