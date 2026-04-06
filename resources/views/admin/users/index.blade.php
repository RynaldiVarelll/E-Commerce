@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight leading-none uppercase italic">Kelola <span class="text-blue-600 dark:text-blue-400">Pengguna.</span></h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2 font-medium">Atur hak akses Seller dan Customer dengan mudah.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" 
            class="inline-flex items-center bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-black shadow-xl shadow-blue-200 dark:shadow-none hover:bg-blue-700 transition-all active:scale-95 whitespace-nowrap uppercase tracking-widest text-xs">
            <i class="fa-solid fa-user-plus mr-2"></i> TAMBAH PENGGUNA
        </a>
    </div>

    <div class="glass-panel rounded-[2.5rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-white/30 dark:bg-gray-800/30 text-gray-500 dark:text-gray-400 text-[11px] font-black uppercase tracking-[0.2em] backdrop-blur-md">
                        <th class="px-8 py-5 text-left">Nama & Profil</th>
                        <th class="px-8 py-5 text-left">Email</th>
                        <th class="px-8 py-5 text-left">Jabatan / Role</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr> 
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($users as $user)
                    <tr class="hover:bg-white/40 dark:hover:bg-gray-800/40 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-50 dark:bg-gray-700/50 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center font-black text-xs mr-3 border border-white dark:border-transparent overflow-hidden shadow-inner">
                                    @if($user->profile_photo_path)
                                        <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <span class="font-black text-gray-900 dark:text-white">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm text-gray-500 dark:text-gray-400 font-medium">{{ $user->email }}</td>
                        <td class="px-8 py-6">
                            @if($user->isSuperAdmin())
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900">
                                    Super Admin (Owner)
                                </span>
                            @elseif($user->role === 'admin')
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                    Seller (Penjual)
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                    Customer (Pembeli)
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="w-9 h-9 flex items-center justify-center bg-white/50 dark:bg-gray-800/50 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all border border-white/60 dark:border-gray-700 shadow-sm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="w-9 h-9 flex items-center justify-center bg-red-50/50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100/40 dark:border-red-900/50 shadow-sm" 
                                            onclick="return confirm('Hapus user ini?')">
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
