@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-6 max-w-2xl">
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-black text-gray-900 tracking-tight leading-none uppercase italic">
            Edit <span class="text-blue-600">Pengguna.</span>
        </h1>
        <p class="text-gray-500 mt-2 font-medium">Ubah informasi akun Seller atau Customer.</p>
    </div>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" 
        class="glass-panel p-10 rounded-[2.5rem] relative overflow-hidden">
        <div class="absolute inset-0 bg-white/40 pointer-events-none"></div>
        <div class="relative z-10">
            @csrf @method('PUT')
            
            {{-- Photo Upload --}}
            <div class="mb-10 flex flex-col items-center">
                <div class="w-24 h-24 bg-blue-100 rounded-3xl flex items-center justify-center mb-4 border-2 border-dashed border-blue-300 relative overflow-hidden group">
                    <i class="fa-solid fa-camera text-blue-400 text-2xl group-hover:scale-110 transition-transform"></i>
                    <input type="file" name="profile_photo_path" onchange="previewImage(event)" 
                           class="absolute inset-0 opacity-0 cursor-pointer z-20">
                    
                    @if($user->profile_photo_path)
                        <img id="preview" src="{{ Storage::url($user->profile_photo_path) }}" 
                             class="absolute inset-0 w-full h-full object-cover z-10">
                    @else
                        <img id="preview" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                    @endif
                </div>
                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-blue-600">Ubah Foto Profil</label>
                @error('profile_photo_path') <span class="text-red-500 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Nama Lengkap</label>
                <input type="text" name="name" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold placeholder-gray-400" value="{{ old('name', $user->name) }}" placeholder="John Doe" required>
                @error('name') <span class="text-red-500 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Email Address</label>
                <input type="email" name="email" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold placeholder-gray-400" value="{{ old('email', $user->email) }}" placeholder="email@example.com" required>
                @error('email') <span class="text-red-500 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Role Akses</label>
                <select name="role" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold" required>
                    <option value="customer" {{ old('role', $user->role) == 'customer' ? 'selected' : '' }}>Customer (Pembeli)</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Seller (Penjual)</option>
                </select>
                @error('role') <span class="text-red-500 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
            </div>

            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Biarkan password kosong jika tidak ingin diubah.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Password Baru</label>
                    <input type="password" name="password" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold">
                    @error('password') <span class="text-red-500 text-[10px] font-black uppercase mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-gray-500 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full bg-white/50 backdrop-blur-md border border-white/60 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-400/50 transition-all font-bold">
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 border-t border-white/40">
                <button type="submit" 
                    class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95 leading-none">
                    <i class="fa-solid fa-save mr-2"></i> SIMPAN PERUBAHAN
                </button>
                <a href="{{ route('admin.users.index') }}" 
                    class="px-8 py-4 bg-white/40 backdrop-blur-md border border-white/60 rounded-2xl text-xs font-black uppercase tracking-widest text-gray-600 hover:bg-white/60 transition-all leading-none">
                    Batal
                </a>
            </div>
        </div>
    </form>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const file = event.target.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.classList.remove('hidden');
            }
        }
    </script>
</div>
@endsection
