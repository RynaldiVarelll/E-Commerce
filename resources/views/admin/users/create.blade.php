@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6 max-w-2xl">
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none uppercase italic">
            Create <span class="text-blue-600">Account.</span>
        </h1>
        <p class="text-gray-500 mt-2 font-medium">Daftarkan admin atau customer store baru.</p>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" 
        class="glass-panel p-10 rounded-[2.5rem] relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-white/40 to-white/10 pointer-events-none"></div>
        <div class="relative z-10">
            @csrf
            
            <div class="mb-6">
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Nama Lengkap</label>
                <input type="text" name="name" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold placeholder-gray-400" value="{{ old('name') }}" placeholder="John Doe" required>
                @error('name') <span class="text-red-500 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Email Address</label>
                <input type="email" name="email" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold placeholder-gray-400" value="{{ old('email') }}" placeholder="email@example.com" required>
                @error('email') <span class="text-red-500 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Role Access</label>
                <select name="role" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold" required>
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer (Pembeli)</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin (Seller)</option>
                </select>
                @error('role') <span class="text-red-500 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Password</label>
                    <input type="password" name="password" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold" required>
                    @error('password') <span class="text-red-500 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold" required>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-white/40">
                <button type="submit" 
                    class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 leading-none">
                    <i class="fa-solid fa-user-check mr-2"></i> CREATE ACCOUNT
                </button>
                <a href="{{ route('admin.users.index') }}" 
                    class="px-8 py-4 bg-white/40 backdrop-blur-md border border-white/60 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-600 hover:bg-white/60 transition-all leading-none">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
