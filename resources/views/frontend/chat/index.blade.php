@extends('layouts.frontend')

@section('content')
<div class="min-h-screen bg-gray-50/50 py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('product.index') }}" class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-gray-500 hover:text-blue-600 shadow-sm border border-gray-100 transition-all active:scale-95">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Pesan Saya</h1>
                    <p class="text-gray-500 font-medium mt-1">Kelola percakapan Anda dengan toko</p>
                </div>
            </div>
            <div class="bg-blue-600 text-white px-5 py-2.5 rounded-2xl font-bold text-sm shadow-lg shadow-blue-200 self-start md:self-center">
                {{ $users->count() }} Percakapan
            </div>
        </div>

        <div class="glass-panel rounded-[2.5rem] overflow-hidden bg-white/80 backdrop-blur-xl border border-white shadow-2xl shadow-blue-900/5">
            @if($users->isEmpty())
                <div class="flex flex-col items-center justify-center py-32 px-6 text-center">
                    <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-comments text-4xl text-blue-200"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Belum Ada Chat</h3>
                    <p class="text-gray-500 mt-2 max-w-xs mx-auto">Anda belum pernah memulai percakapan dengan toko manapun. Mulailah belanja dan hubungi penjual!</p>
                    <a href="{{ route('product.index') }}" class="mt-8 bg-blue-600 text-white px-8 py-3 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-blue-700 transition-all shadow-xl shadow-blue-100 active:scale-95">
                        Jelajahi Produk
                    </a>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($users as $user)
                        <div class="flex items-center group relative border-l-4 border-transparent hover:border-blue-600 transition-all">
                            <a href="{{ route('chat.show', $user->id) }}" class="flex-1 flex items-center gap-5 p-6 hover:bg-blue-50/50 transition-all min-w-0">
                                <div class="relative">
                                    <div class="w-16 h-16 rounded-[1.5rem] overflow-hidden bg-gray-100 border-2 border-white shadow-md group-hover:scale-105 transition-transform">
                                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    </div>
                                    @if($user->unread_count > 0)
                                        <div class="absolute -top-2 -right-2 bg-blue-600 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-lg animate-bounce">
                                            {{ $user->unread_count }}
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="font-black text-gray-900 uppercase tracking-tight truncate group-hover:text-blue-600 transition-colors">
                                            {{ $user->name }}
                                            @if($user->role === 'admin' || $user->role === 'super_admin')
                                                <span class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full border border-blue-200 font-black">SELLER</span>
                                            @endif
                                        </h4>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            {{ $user->last_message ? $user->last_message->created_at->diffForHumans() : '' }}
                                        </span>
                                    </div>
                                    <p class="text-sm {{ $user->unread_count > 0 ? 'text-gray-900 font-bold' : 'text-gray-500 font-medium' }} truncate">
                                        @if($user->last_message && $user->last_message->sender_id === auth()->id())
                                            <span class="text-blue-500 mr-1">Anda:</span>
                                        @endif
                                        {{ $user->last_message ? $user->last_message->message : 'Mulai percakapan...' }}
                                    </p>
                                </div>
                            </a>

                            {{-- Delete Conversation --}}
                            <div class="px-6">
                                <form action="{{ route('chat.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Hapus seluruh percakapan dengan toko ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-xl flex items-center justify-center text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
