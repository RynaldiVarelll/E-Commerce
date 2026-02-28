@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto px-6 py-10">
    <div class="mb-8">
        <a href="{{ route('admin.shipping-methods.index') }}" class="text-sm font-bold text-gray-400 hover:text-blue-600 transition-colors flex items-center mb-2">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Tambah Ekspedisi</h1>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.shipping-methods.store') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Nama Kurir</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold transition-all"
                           placeholder="Contoh: JNE">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Jenis Layanan</label>
                    <input type="text" name="service" value="{{ old('service') }}" required
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold transition-all"
                           placeholder="Contoh: Reguler / YES">
                </div>

                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-400 mb-2">Biaya Ongkir (Rp)</label>
                    <input type="number" name="cost" value="{{ old('cost') }}" required
                           class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none font-bold transition-all"
                           placeholder="0">
                </div>

                {{-- CHECKBOX STATUS --}}
                <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100 flex items-center justify-between">
                    <div>
                        <p class="font-bold text-blue-900 text-sm">Aktifkan Metode Ini?</p>
                        <p class="text-xs text-blue-600 font-medium">Metode yang aktif akan muncul di halaman checkout user.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black text-lg shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all transform active:scale-95">
                    Simpan Metode
                </button>
            </div>
        </form>
    </div>
</div>
@endsection