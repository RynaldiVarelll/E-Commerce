<section>
    <form method="post" action="{{ route('profile.update') }}" class="space-y-8" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Hidden File Input handled by Avatar Click --}}
        <input id="profile_photo" name="profile_photo" type="file" class="hidden" accept="image/*" />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Name Input --}}
            <div class="space-y-2">
                <label for="name" class="custom-label">Full Name</label>
                <div class="relative group">
                    <i class="fa-solid fa-user absolute left-5 top-1/2 -translate-y-1/2 text-blue-500/50 group-focus-within:text-blue-500 transition-colors"></i>
                    <input id="name" name="name" type="text" class="custom-input !pl-14" :value="old('name', $user->name)" required autofocus autocomplete="name" placeholder="Enter your full name" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            {{-- Email Input --}}
            <div class="space-y-2">
                <label for="email" class="custom-label">Email Address</label>
                <div class="relative group">
                    <i class="fa-solid fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-blue-500/50 group-focus-within:text-blue-500 transition-colors"></i>
                    <input id="email" name="email" type="email" class="custom-input !pl-14" :value="old('email', $user->email)" required autocomplete="username" placeholder="name@example.com" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-2xl border border-yellow-100 dark:border-yellow-900/30">
                        <p class="text-xs font-bold text-yellow-700 dark:text-yellow-400 flex items-center gap-2">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            Email unverified.
                            <button form="send-verification" class="underline hover:text-yellow-800 dark:hover:text-yellow-300 ml-2">Click to resend</button>
                        </p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Address Input --}}
        <div class="space-y-2">
            <label for="address" class="custom-label">Shipping Address</label>
            <div class="relative group">
                <i class="fa-solid fa-map-location-dot absolute left-5 top-6 text-blue-500/50 group-focus-within:text-blue-500 transition-colors"></i>
                <textarea id="address" name="address" class="custom-input !pl-14 min-h-[120px] !pt-5" placeholder="Street name, Building, City, Province, Zip Code" required>{{ old('address', $user->address) }}</textarea>
            </div>
            <p class="text-[10px] text-gray-400 mt-2 ml-1 italic font-medium">Providing a complete address helps us deliver your orders faster.</p>
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <div class="flex items-center gap-6 pt-4">
            <button type="submit" class="px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-500/30 transition-all hover:scale-105 active:scale-95 uppercase tracking-tighter italic">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="flex items-center gap-2 text-green-600 dark:text-green-400 font-bold text-sm">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Profile Updated!</span>
                </div>
            @endif
        </div>
    </form>
</section>

