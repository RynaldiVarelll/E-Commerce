<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-[10px] font-black text-blue-900/40 uppercase tracking-[0.2em] ml-2 mb-2">Email Address</label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@company.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <label class="block text-[10px] font-black text-blue-900/40 uppercase tracking-[0.2em] ml-2 mb-2">Password</label>
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between px-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="rounded-lg border-white/80 text-blue-600 focus:ring-blue-500/20 bg-white/50 w-5 h-5 transition-all" name="remember">
                <span class="ms-3 text-xs font-bold text-blue-900/50 group-hover:text-blue-600 transition-colors">{{ __('Stay logged in') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-bold text-blue-500 hover:text-blue-700 transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        {{-- Submit Button --}}
        <div class="pt-2">
            <x-primary-button>
                {{ __('Sign In to Dashboard') }}
            </x-primary-button>
        </div>

        {{-- Register Link --}}
        <p class="text-center text-xs font-bold text-blue-900/30 uppercase tracking-tighter mt-8">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 ml-1">Create One</a>
        </p>
    </form>
</x-guest-layout>