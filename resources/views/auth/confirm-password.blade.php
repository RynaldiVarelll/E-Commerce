<x-guest-layout 
    pageTitle="{{ __('Konfirmasi Akses') }}"
    pageSubtitle="{{ __('Keamanan ekstra untuk area sensitif.') }}"
>
    <div class="mb-8 text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed text-center px-4">
        {{ __('Ini adalah area aman aplikasi. Harap konfirmasi kata sandi Anda sebelum melanjutkan.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        <!-- Password -->
        <div>
            <label for="password" class="custom-label">{{ __('Kata Sandi') }}</label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input id="password" class="custom-input !pl-14" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>
            @if ($errors->has('password'))
                <p class="mt-2 text-[10px] font-black text-red-500 ml-2 italic uppercase tracking-widest">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-primary">
                {{ __('Konfirmasi') }}
            </button>
        </div>
    </form>
</x-guest-layout>
