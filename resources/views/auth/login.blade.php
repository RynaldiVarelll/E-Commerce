<x-guest-layout 
    pageTitle="{{ __('Halo, Selamat Datang!') }}"
    pageSubtitle="{{ __('Gunakan kredensial Anda untuk masuk ke sistem belanja cerdas.') }}"
>
    {{-- Session Status --}}
    @if (session('status'))
        <div class="mb-6 font-bold text-[11px] text-green-600 bg-green-50 px-6 py-4 rounded-2xl animate-fade-in text-center border border-green-100 uppercase tracking-widest">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="custom-label">Email Pelanggan</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" 
                   required autofocus autocomplete="username" 
                   placeholder="mail@yourcompany.com"
                   class="custom-input">
            @if ($errors->has('email'))
                <p class="mt-2 text-[10px] font-black text-red-500 ml-2 italic uppercase tracking-widest">{{ $errors->first('email') }}</p>
            @endif
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="custom-label">Kata Sandi</label>
            <div class="relative">
                <i class="fa-solid fa-key absolute left-5 top-1/2 -translate-y-1/2 text-blue-600/30"></i>
                <input id="password" type="password" name="password" 
                       required autocomplete="current-password" 
                       placeholder="••••••••"
                       class="custom-input !pl-14">
            </div>
            @if ($errors->has('password'))
                <p class="mt-2 text-[10px] font-black text-red-500 ml-2 italic uppercase tracking-widest">{{ $errors->first('password') }}</p>
            @endif
        </div>

        {{-- Extra Actions --}}
        <div class="flex items-center justify-between px-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <div class="relative flex items-center">
                    <input id="remember_me" type="checkbox" name="remember"
                           class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border border-white bg-white/50 checked:bg-blue-600 transition-all">
                    <i class="fa-solid fa-check absolute scale-0 peer-checked:scale-100 left-1 current-color text-[10px] text-white transition-transform pointer-events-none"></i>
                </div>
                <span class="ms-3 text-[10px] font-black text-gray-400 group-hover:text-blue-600 transition-colors uppercase tracking-widest">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[10px] font-black text-blue-600 hover:text-blue-800 transition-colors uppercase tracking-widest underline decoration-blue-100 underline-offset-4" href="{{ route('password.request') }}">
                    {{ __('Bantuan?') }}
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <div class="pt-4 relative">
            <button type="submit" class="btn-primary">
                {{ __('Masuk Sekarang') }}
            </button>
            <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-2 whitespace-nowrap opacity-40 group hover:opacity-100 transition-opacity">
                <i class="fa-solid fa-shield-halved text-[10px] text-blue-600"></i>
                <span class="text-[8px] font-black uppercase tracking-[0.3em] text-gray-400">AES-256 Encrypted Connection</span>
            </div>
        </div>

        {{-- Register Link --}}
        <div class="text-center mt-20 pt-8 border-t border-white/40">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] leading-loose">
                Belum Jadi Member? <br>
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 text-xs mt-3 inline-block hover:scale-105 transition-transform font-black">BUAT AKUN BARU <i class="fa-solid fa-arrow-right ml-1"></i></a>
            </p>
        </div>
    </form>
</x-guest-layout>