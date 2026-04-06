<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-8">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div class="space-y-2">
            <label for="update_password_current_password" class="custom-label">Current Password</label>
            <div class="relative group">
                <i class="fa-solid fa-key absolute left-5 top-1/2 -translate-y-1/2 text-indigo-500/50 group-focus-within:text-indigo-500 transition-colors"></i>
                <input id="update_password_current_password" name="current_password" type="password" class="custom-input !pl-14" autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- New Password --}}
            <div class="space-y-2">
                <label for="update_password_password" class="custom-label">New Password</label>
                <div class="relative group">
                    <i class="fa-solid fa-lock-open absolute left-5 top-1/2 -translate-y-1/2 text-indigo-500/50 group-focus-within:text-indigo-500 transition-colors"></i>
                    <input id="update_password_password" name="password" type="password" class="custom-input !pl-14" autocomplete="new-password" placeholder="Min. 8 characters" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            {{-- Confirm Password --}}
            <div class="space-y-2">
                <label for="update_password_password_confirmation" class="custom-label">Confirm Password</label>
                <div class="relative group">
                    <i class="fa-solid fa-circle-check absolute left-5 top-1/2 -translate-y-1/2 text-indigo-500/50 group-focus-within:text-indigo-500 transition-colors"></i>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="custom-input !pl-14" autocomplete="new-password" placeholder="Repeat new password" />
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center gap-6 pt-4">
            <button type="submit" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/30 transition-all hover:scale-105 active:scale-95 uppercase tracking-tighter italic">
                Update Security
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="flex items-center gap-2 text-green-600 dark:text-green-400 font-bold text-sm">
                    <i class="fa-solid fa-shield-check"></i>
                    <span>Password Secured!</span>
                </div>
            @endif
        </div>
    </form>
</section>

