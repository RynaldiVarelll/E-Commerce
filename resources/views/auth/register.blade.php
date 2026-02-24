<x-guest-layout
    pageTitle="{{ __('Buat Akun Baru') }}"
    pageSubtitle="{{ __('Bergabung dan mulai belanja sekarang') }}"
    pageIcon="user-plus"
>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="block mt-1 w-full"
                          type="text" name="name"
                          :value="old('name')"
                          required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email" name="email"
                          :value="old('email')"
                          required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password" name="password"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                          type="password" name="password_confirmation"
                          required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Already registered + Submit -->
        <div class="flex items-center justify-end" style="gap:1rem; margin-top:0.5rem;">
            <a href="{{ route('login') }}"
               style="
                   font-size:0.8125rem;
                   font-weight:500;
                   color:#4a90d9;
                   text-decoration:underline;
                   text-underline-offset:3px;
                   transition:color 0.18s;
               "
               onmouseover="this.style.color='#0a2463'"
               onmouseout="this.style.color='#4a90d9'">
                {{ __('Sudah punya akun?') }}
            </a>

            <x-primary-button>
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>