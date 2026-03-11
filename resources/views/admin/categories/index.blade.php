@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none uppercase italic">Kelola <span class="text-blue-600">Kategori.</span></h1>
            <p class="text-gray-500 mt-2 font-medium">Atur kategori produk secara terorganisir.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" 
            class="inline-flex items-center bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-black shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 whitespace-nowrap">
            <i class="fa-solid fa-plus mr-2"></i> TAMBAH KATEGORI
        </a>
    </div>

    <div class="glass-panel rounded-[2.5rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-white/30 text-gray-500 text-[11px] font-black uppercase tracking-[0.2em] backdrop-blur-md">
                        <th class="px-8 py-5 text-left">Nama</th>
                        <th class="px-8 py-5 text-left">Slug</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr> 
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($categories as $cat)
                    <tr class="hover:bg-white/40 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <span class="font-black text-gray-900">{{ $cat->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm text-gray-500 font-medium">/{{ $cat->slug }}</td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.categories.edit', $cat) }}" 
                                   class="w-9 h-9 flex items-center justify-center bg-white/50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all border border-white/60 shadow-sm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="w-9 h-9 flex items-center justify-center bg-red-50/50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100/40 shadow-sm" 
                                            onclick="return confirm('Hapus kategori ini?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection