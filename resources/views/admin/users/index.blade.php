@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-4">
        <div>
            <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none uppercase italic">Kelola <span class="text-blue-600">Accounts.</span></h1>
            <p class="text-gray-500 mt-2 font-medium">Atur hak akses admin dan customer dengan mudah.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" 
            class="inline-flex items-center bg-blue-600 text-white px-6 py-3.5 rounded-2xl font-black shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 whitespace-nowrap">
            <i class="fa-solid fa-user-plus mr-2"></i> TAMBAH AKUN
        </a>
    </div>

    <div class="glass-panel rounded-[2.5rem] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-white/30 text-gray-500 text-[11px] font-black uppercase tracking-[0.2em] backdrop-blur-md">
                        <th class="px-8 py-5 text-left">Nama & Profil</th>
                        <th class="px-8 py-5 text-left">Email</th>
                        <th class="px-8 py-5 text-left">Role Access</th>
                        <th class="px-8 py-5 text-center">Aksi</th>
                    </tr> 
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-white/40 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black text-xs mr-3 border border-white overflow-hidden">
                                    @if($user->profile_photo_path)
                                        <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    @endif
                                </div>
                                <span class="font-black text-gray-900">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-sm text-gray-500 font-medium">{{ $user->email }}</td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="w-9 h-9 flex items-center justify-center bg-white/50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all border border-white/60 shadow-sm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="w-9 h-9 flex items-center justify-center bg-red-50/50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100/40 shadow-sm" 
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
