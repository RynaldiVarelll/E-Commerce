<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full"
                          type="email" name="email"
                          :value="old('email')"
                          required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                          type="password" name="password"
                          required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:1.5rem;">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me" style="
                display:inline;
                font-size:0.875rem;
                font-weight:400;
                color:#4a6080;
                cursor:pointer;
            ">{{ __('Remember me') }}</label>
        </div>

        <!-- Forgot password + Submit -->
        <div class="flex items-center justify-end" style="gap:1rem;">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
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
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

</x-guest-layout>