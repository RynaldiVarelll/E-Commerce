<x-guest-layout 
    pageTitle="{{ __('Lupa Akses?') }}"
    pageSubtitle="{{ __('Jangan khawatir, kami akan membantu memulihkan akun Anda.') }}"
>
    <div class="mb-8 text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed text-center px-4">
        {{ __('Cukup masukkan alamat email Anda dan kami akan mengirimkan tautan reset kata sandi.') }}
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 font-bold text-[11px] text-blue-600 bg-blue-50 px-6 py-4 rounded-2xl animate-fade-in text-center border border-blue-100 uppercase tracking-widest">
            <i class="fa-solid fa-paper-plane mr-2"></i> {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="custom-label">{{ __('Alamat Email') }}</label>
            <input id="email" class="custom-input" type="email" name="email" :value="old('email')" required autofocus placeholder="mail@example.com" />
            @if ($errors->has('email'))
                <p class="mt-2 text-[10px] font-black text-red-500 ml-2 italic uppercase tracking-widest">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-primary">
                {{ __('Kirim Link Reset') }}
            </button>
        </div>

        <div class="text-center mt-12 pt-6 border-t border-white/40">
            <a href="{{ route('login') }}" class="text-[10px] font-black text-blue-600 hover:text-blue-800 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </form>
</x-guest-layout>
