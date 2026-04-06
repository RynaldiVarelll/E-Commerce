<section class="space-y-6">
    <div class="p-6 bg-red-100/50 dark:bg-red-900/20 rounded-3xl border border-red-200 dark:border-red-900/30">
        <p class="text-sm font-bold text-red-800 dark:text-red-300">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </div>

    <button type="button" 
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-black rounded-2xl shadow-xl shadow-red-500/20 transition-all hover:scale-105 active:scale-95 uppercase tracking-tighter italic">
        Delete My Account
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 md:p-12 bg-white dark:bg-gray-900 rounded-[3rem]">
            @csrf
            @method('delete')

            <h2 class="text-3xl font-black text-gray-900 dark:text-white uppercase tracking-tighter italic mb-4">
                {{ __('Are you absolutely sure?') }}
            </h2>

            <p class="text-gray-500 dark:text-gray-400 font-medium mb-8">
                {{ __('This action is permanent and cannot be undone. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="space-y-2">
                <label for="password" class="custom-label">{{ __('Password') }}</label>
                <div class="relative group">
                    <i class="fa-solid fa-triangle-exclamation absolute left-5 top-1/2 -translate-y-1/2 text-red-500/50 group-focus-within:text-red-500 transition-colors"></i>
                    <input id="password" name="password" type="password" class="custom-input !pl-14 border-red-100/50 focus:border-red-500 focus:ring-red-500/10" placeholder="{{ __('Verify your password') }}" />
                </div>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-10 flex flex-col sm:flex-row justify-end gap-4">
                <button type="button" x-on:click="$dispatch('close')" class="px-8 py-4 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 font-black rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all uppercase tracking-tighter italic">
                    {{ __('Cancel') }}
                </button>

                <button type="submit" class="px-8 py-4 bg-red-600 text-white font-black rounded-2xl hover:bg-red-700 shadow-xl shadow-red-500/20 transition-all uppercase tracking-tighter italic">
                    {{ __('Final Delete') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
