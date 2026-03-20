<x-guest-layout 
    pageTitle="{{ __('Reset Kata Sandi') }}"
    pageSubtitle="{{ __('Langkah terakhir untuk mengamankan kembali akun Anda.') }}"
>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="custom-label">{{ __('Alamat Email') }}</label>
            <input id="email" class="custom-input bg-gray-50/50" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
            @if ($errors->has('email'))
                <p class="mt-2 text-[10px] font-black text-red-500 ml-2 italic uppercase tracking-widest">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="custom-label">{{ __('Kata Sandi Baru') }}</label>
            <input id="password" class="custom-input" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
            @if ($errors->has('password'))
                <p class="mt-2 text-[10px] font-black text-red-500 ml-2 italic uppercase tracking-widest">{{ $errors->first('password') }}</p>
            @endif
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="custom-label">{{ __('Konfirmasi Kata Sandi') }}</label>
            <input id="password_confirmation" class="custom-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
            @if ($errors->has('password_confirmation'))
                <p class="mt-2 text-[10px] font-black text-red-500 ml-2 italic uppercase tracking-widest">{{ $errors->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-primary">
                {{ __('Update Kata Sandi') }}
            </button>
        </div>
    </form>
</x-guest-layout>
