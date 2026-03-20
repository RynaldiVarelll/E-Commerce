<x-guest-layout 
    pageTitle="{{ __('Bergabung Sekarang') }}"
    pageSubtitle="{{ __('Mulai pengalaman belanja cerdas Anda hari ini') }}"
>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="custom-label">{{ __('Nama Lengkap') }}</label>
            <div class="relative">
                <i class="fa-solid fa-user absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input id="name" type="text" name="name" value="{{ old('name') }}" 
                       required autofocus autocomplete="name"
                       placeholder="Nama lengkap Anda"
                       class="custom-input !pl-14">
            </div>
            @if ($errors->has('name'))
                <p class="mt-1.5 text-[10px] font-bold text-red-500 ml-2 italic uppercase tracking-wider">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <!-- Role Option -->
        <div>
            <label for="role" class="custom-label">{{ __('Daftar Sebagai') }}</label>
            <div class="relative">
                <i class="fa-solid fa-id-badge absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <select id="role" name="role" required
                        class="custom-input !pl-14 !bg-none appearance-none cursor-pointer">
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Customer (Pembeli)</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin (Penjual)</option>
                </select>
                <i class="fa-solid fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
            </div>
            @if ($errors->has('role'))
                <p class="mt-1.5 text-[10px] font-bold text-red-500 ml-2 italic uppercase tracking-wider">{{ $errors->first('role') }}</p>
            @endif
        </div>

        <!-- Profile Photo (Optional) -->
        <div>
            <label for="profile_photo" class="custom-label">{{ __('Foto Profil (Opsional)') }}</label>
            <div class="relative group">
                <input id="profile_photo" type="file" name="profile_photo" 
                       accept="image/*"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                <div class="custom-input flex items-center gap-3 bg-white/30 border-dashed border-2 border-white/60 group-hover:bg-white/50 transition-all">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-camera"></i>
                    </div>
                    <span class="text-gray-400 font-bold text-xs uppercase tracking-widest overflow-hidden text-ellipsis whitespace-nowrap">Pilih Foto Profil...</span>
                </div>
            </div>
            @if ($errors->has('profile_photo'))
                <p class="mt-1.5 text-[10px] font-bold text-red-500 ml-2 italic uppercase tracking-wider">{{ $errors->first('profile_photo') }}</p>
            @endif
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="custom-label">{{ __('Email') }}</label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                       required autocomplete="username"
                       placeholder="email@example.com"
                       class="custom-input !pl-14">
            </div>
            @if ($errors->has('email'))
                <p class="mt-1.5 text-[10px] font-bold text-red-500 ml-2 italic uppercase tracking-wider">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Password -->
            <div>
                <label for="password" class="custom-label">{{ __('Password') }}</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input id="password" type="password" name="password" 
                           required autocomplete="new-password"
                           placeholder="••••••••"
                           class="custom-input !pl-14">
                </div>
                @if ($errors->has('password'))
                    <p class="mt-1.5 text-[10px] font-bold text-red-500 ml-2 italic uppercase tracking-wider">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="custom-label">{{ __('Konfirmasi') }}</label>
                <div class="relative">
                    <i class="fa-solid fa-shield-check absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation" 
                           required autocomplete="new-password" 
                           placeholder="••••••••"
                           class="custom-input !pl-14">
                </div>
                @if ($errors->has('password_confirmation'))
                    <p class="mt-1.5 text-[10px] font-bold text-red-500 ml-2 italic uppercase tracking-wider">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>
        </div>

        <!-- Submit -->
        <div class="pt-6">
            <button type="submit" class="btn-primary">
                {{ __('Daftar Sekarang') }}
            </button>
        </div>

        <!-- Already registered -->
        <div class="text-center mt-10">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest leading-loose">
                Sudah punya akun? <br>
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-1 inline-block">Masuk ke My Account</a>
            </p>
        </div>
    </form>

    <script>
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih Foto Profil...';
            this.nextElementSibling.querySelector('span').textContent = fileName;
        });
    </script>
</x-guest-layout>