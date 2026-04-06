<nav x-data="{ open: false, darkMode: document.documentElement.classList.contains('dark') }" 
     @theme-updated.window="darkMode = $event.detail.isDark"
     class="sticky top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-100/50 dark:border-gray-800/50 shadow-sm dark:shadow-none transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> 
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 group-hover:rotate-12 transition-transform duration-300">
                            {{-- Icon Petir Putih --}}
                            <svg class="w-6 h-6 text-white fill-current" viewBox="0 0 24 24">
                                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">
                            Invoify<span class="text-blue-600 dark:text-blue-400">.</span>
                        </span>
                    </a>
                </div>

                <div class="hidden space-x-2 sm:-my-px sm:ms-12 sm:flex">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-extrabold tracking-tight transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100 dark:shadow-none' : 'text-gray-500 hover:text-blue-600 hover:bg-white dark:text-gray-400 dark:hover:bg-gray-800' }}">
                        <i class="fa-solid fa-house-chimney mr-2 text-xs"></i>{{ __('Dashboard') }}
                    </a>

                    {{-- Products Menu for Admin --}}
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.products.index') }}" 
                           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-extrabold tracking-tight transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100 dark:shadow-none' : 'text-gray-500 hover:text-blue-600 hover:bg-white dark:text-gray-400 dark:hover:bg-gray-800' }}">
                            <i class="fa-solid fa-box mr-2 text-xs"></i>{{ __('Produk') }}
                        </a>
                    @endif

                    {{-- Chat Menu for Everyone --}}
                    <a href="{{ route('chat.index') }}" 
                       class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-extrabold tracking-tight transition-all duration-200 {{ request()->routeIs('chat.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-100 dark:shadow-none' : 'text-gray-500 hover:text-blue-600 hover:bg-white dark:text-gray-400 dark:hover:bg-gray-800' }}">
                        <i class="fa-solid fa-comments mr-2 text-xs"></i>{{ __('Chat') }}
                        @php
                            $unreadCount = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="ms-2 px-1.5 py-0.5 bg-red-500 text-white text-[10px] rounded-full animate-pulse shadow-sm">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                {{-- Dark Mode Toggle --}}
                <button @click="window.toggleDarkMode()" 
                        class="p-2.5 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all group border border-transparent dark:border-gray-700">
                    <template x-if="darkMode">
                        <i class="fa-solid fa-sun text-yellow-400 animate-spin-slow"></i>
                    </template>
                    <template x-if="!darkMode">
                        <i class="fa-solid fa-moon text-blue-600"></i>
                    </template>
                </button>

                <div class="h-8 w-[1px] bg-gray-100 dark:bg-gray-700 mx-4"></div>
                
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-3 px-3 py-1.5 rounded-2xl border border-transparent hover:bg-gray-50 dark:hover:bg-gray-800 hover:border-gray-100 dark:hover:border-gray-700 transition-all duration-300 focus:outline-none">
                            <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black text-sm shadow-md dark:shadow-none overflow-hidden">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                @endif
                            </div>
                            
                            <div class="text-left hidden lg:block">
                                <div class="text-sm font-black text-gray-800 dark:text-white leading-none">{{ Auth::user()->name }}</div>
                                
                                {{-- Role Label Dinamis --}}
                                <div class="text-[10px] font-bold uppercase tracking-widest mt-1 {{ Auth::user()->isAdmin() ? 'text-blue-600' : 'text-gray-400' }}">
                                    @if(Auth::user()->isSuperAdmin())
                                        Super Admin
                                    @elseif(Auth::user()->isAdmin())
                                        Administrator
                                    @else
                                        Customer
                                    @endif
                                </div>
                            </div>

                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-50 dark:border-gray-700">
                            <p class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.2em] mb-1">Manage Account</p>
                            <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <div class="p-2 space-y-1">
                            <x-dropdown-link :href="route('profile.edit')" class="flex items-center gap-2 rounded-xl font-bold text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-white hover:bg-blue-50 dark:hover:bg-blue-600 transition-all">
                                <i class="fa-solid fa-id-badge text-blue-400 w-5"></i> {{ __('Settings Profile') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="flex items-center gap-2 rounded-xl font-bold text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all">
                                    <i class="fa-solid fa-power-off w-5"></i> {{ __('Logout') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-xl text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="sm:hidden bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800 shadow-xl dark:shadow-none overflow-hidden">
        <div class="pt-4 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-2xl font-black">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            
            {{-- NEW: Mobile Link My Orders --}}
            @if(!Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="rounded-2xl font-black">
                    {{ __('My Orders') }}
                </x-responsive-nav-link>
            @endif

            {{-- Mobile Dark Mode Toggle --}}
            <div class="mt-4 px-3 py-2 bg-gray-50 dark:bg-gray-800/50 rounded-2xl flex items-center justify-between">
                <span class="text-sm font-bold text-gray-600 dark:text-gray-400">Mode Kegelapan</span>
                <button @click="window.toggleDarkMode()" 
                        class="p-2.5 bg-white dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-xl shadow-sm transition-all border border-gray-100 dark:border-gray-600">
                    <template x-if="darkMode">
                        <i class="fa-solid fa-sun text-yellow-400 animate-spin-slow"></i>
                    </template>
                    <template x-if="!darkMode">
                        <i class="fa-solid fa-moon text-blue-600"></i>
                    </template>
                </button>
            </div>
        </div>

        <div class="pt-4 pb-6 border-t border-gray-100 dark:border-gray-800 px-6">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white font-black shadow-lg overflow-hidden">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="ms-4">
                    <div class="font-black text-gray-900 dark:text-white leading-none mb-1">{{ Auth::user()->name }}</div>
                    
                    {{-- Role Label Mobile --}}
                    <div class="text-[10px] font-bold uppercase tracking-widest mb-1 {{ Auth::user()->isAdmin() ? 'text-blue-600' : 'text-gray-400' }}">
                        @if(Auth::user()->isSuperAdmin())
                            Super Admin
                        @elseif(Auth::user()->isAdmin())
                            Administrator
                        @else
                            Customer
                        @endif
                    </div>
                    
                    <div class="font-medium text-[11px] text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="space-y-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-2xl font-bold text-gray-700 dark:text-gray-300">
                    <i class="fa-solid fa-gear mr-2"></i> {{ __('Profile Settings') }}
                </x-responsive-nav-link>
            </div>
        </div>
    </div>
</nav>