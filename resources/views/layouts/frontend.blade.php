<!DOCTYPE html>
<html lang="en" class="scroll-smooth" 
      x-data="{ darkMode: document.documentElement.classList.contains('dark') }" 
      @theme-updated.window="darkMode = $event.detail.isDark"
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoify — Smart Shopping Experience</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/e16c014aae.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- Global Theme Switcher & Flicker Prevention --}}
    <script>
        (function() {
            const applyTheme = (isDark) => {
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                // Sync Alpine if initialized
                window.dispatchEvent(new CustomEvent('theme-updated', { detail: { isDark } }));
            };

            const isDarkMode = localStorage.getItem('darkMode') === 'true' || 
                             (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            
            applyTheme(isDarkMode);

            window.toggleDarkMode = function() {
                const isCurrentlyDark = document.documentElement.classList.contains('dark');
                const nextDark = !isCurrentlyDark;
                applyTheme(nextDark);
                localStorage.setItem('darkMode', nextDark);
            };

            // Sync across tabs
            window.addEventListener('storage', (e) => {
                if (e.key === 'darkMode') {
                    applyTheme(e.newValue === 'true');
                }
            });
        })();
    </script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }

        .dark .glass-panel {
            background: rgba(17, 24, 39, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
        }

        .dark .glass-nav {
            background: rgba(17, 24, 39, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Abstract blobs animations */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 15s infinite alternate ease-in-out; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-[#f0f2f5] dark:bg-gray-950 text-gray-900 dark:text-gray-100 leading-relaxed selection:bg-blue-200 dark:selection:bg-blue-900 min-h-screen relative transition-colors duration-300">
    
    {{-- MacOS-like Abstract Background --}}
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60vw] h-[60vw] rounded-full bg-blue-400/20 dark:bg-blue-600/10 blur-[100px] animate-blob"></div>
        <div class="absolute top-[20%] -right-[10%] w-[50vw] h-[50vw] rounded-full bg-indigo-400/20 dark:bg-indigo-600/10 blur-[100px] animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-[20%] left-[20%] w-[70vw] h-[70vw] rounded-full bg-sky-400/20 dark:bg-sky-600/10 blur-[120px] animate-blob animation-delay-4000"></div>
    </div>
    
    <nav class="glass-nav sticky top-0 z-[100] transition-all duration-300">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-none group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-bolt text-white text-xl"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">
                    invoify<span class="text-blue-600">.</span>
                </span>
            </a>

            {{-- Right Menu --}}
            <div class="flex items-center gap-2">
                {{-- Dark Mode Toggle --}}
                <button @click="window.toggleDarkMode()" 
                        class="p-3 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-2xl transition-all"
                        title="Alihkan Tema">
                    <template x-if="darkMode">
                        <i class="fa-solid fa-sun text-yellow-400 animate-spin-slow"></i>
                    </template>
                    <template x-if="!darkMode">
                        <i class="fa-solid fa-moon text-blue-600"></i>
                    </template>
                </button>
                @auth
                    <div class="flex items-center space-x-1">
                        {{-- Shopping Features (Only for Customers) --}}
                        @if(!auth()->user()->isAdmin())
                            {{-- Cart Button with Badge --}}
                            @php
                                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                            @endphp
                            <a href="{{ route('cart.index') }}" 
                               class="relative p-3 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-2xl transition-all group"
                               title="Keranjang Belanja">
                                <i class="fa-solid fa-cart-shopping text-lg"></i>
                                @if($cartCount > 0)
                                    <span class="absolute top-2 right-2 flex h-4 w-4">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-600 text-[10px] text-white items-center justify-center font-bold">
                                            !
                                        </span>
                                    </span>
                                @endif
                            </a>

                            {{-- Chat Button --}}
                            <a href="{{ route('chat.index') }}" 
                               class="relative p-3 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-2xl transition-all group"
                               title="Pesan Saya">
                                <i class="fa-solid fa-comment-dots text-lg"></i>
                                @php
                                    $unreadTotal = \App\Models\Message::where('receiver_id', auth()->id())->where('is_read', false)->count();
                                @endphp
                                @if($unreadTotal > 0)
                                    <span class="absolute top-2 right-2 flex h-4 w-4">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-4 w-4 bg-red-600 text-[10px] text-white items-center justify-center font-bold">
                                            {{ $unreadTotal }}
                                        </span>
                                    </span>
                                @endif
                            </a>

                            {{-- My Orders Button --}}
                            <a href="{{ route('orders.index') }}" 
                               class="p-3 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-2xl transition-all group"
                               title="Pesanan Saya">
                                <i class="fa-solid fa-receipt text-lg"></i>
                            </a>
                        @endif

                        {{-- User Interaction --}}
                        <div class="h-8 w-[1px] bg-gray-200 dark:bg-gray-800 mx-2 hidden md:block"></div>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                               class="hidden md:flex items-center px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
                                <i class="fa-solid fa-gauge-high mr-2 text-blue-600"></i> Admin
                            </a>
                        @else
                            <a href="{{ route('profile.edit', auth()->user()->id) }}" 
                               class="hidden md:block px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-xl transition-all">
                                {{ explode(' ', auth()->user()->name)[0] }}
                            </a>
                        @endif

                        {{-- Logout with Styling --}}
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="p-3 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-2xl transition-all"
                                    title="Logout">
                                <i class="fa-solid fa-right-from-bracket text-lg"></i>
                            </button>
                        </form>
                    </div>
                @endauth

                @guest
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" 
                           class="px-6 py-2.5 text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 dark:shadow-none hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                            Daftar
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </nav>

    <main class="relative">
        @yield('content')
    </main>

    {{-- Script for Navbar Scroll Effect --}}
    <script>
        window.onscroll = function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('py-2', 'shadow-xl', 'shadow-gray-200/20', 'dark:shadow-gray-900/50');
                nav.classList.remove('py-4');
            } else {
                nav.classList.add('py-4');
                nav.classList.remove('py-2', 'shadow-xl', 'shadow-gray-200/20', 'dark:shadow-gray-900/50');
            }
        };
    </script>    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark') || localStorage.getItem('darkMode') === 'true';
            
            const toastConfig = {
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                showCloseButton: true,
                timer: 4000,
                timerProgressBar: true,
                background: isDark ? '#111827' : '#ffffff',
                color: isDark ? '#ffffff' : '#111827',
                customClass: {
                    popup: isDark ? 'rounded-2xl border border-gray-800 shadow-none mt-20' : 'rounded-2xl shadow-2xl border border-gray-100 mt-20',
                }
            };

            const alertConfig = {
                background: isDark ? '#111827' : '#ffffff',
                color: isDark ? '#ffffff' : '#111827',
                confirmButtonColor: '#2563eb',
                customClass: {
                    popup: 'rounded-[1.5rem]',
                }
            };

            @if(session('success'))
                Swal.fire({
                    ...toastConfig,
                    icon: 'success',
                    iconColor: '#2563eb',
                    title: '{{ session('success') }}'
                });
            @endif

            @if(session('status'))
                Swal.fire({
                    ...toastConfig,
                    icon: 'success',
                    iconColor: '#2563eb',
                    title: '{{ session('status') === 'profile-updated' ? 'Profil Berhasil Diperbarui!' : session('status') }}'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    ...alertConfig,
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}'
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    ...toastConfig,
                    icon: 'warning',
                    iconColor: '#f97316',
                    title: 'Ada Kesalahan!',
                    text: 'Silakan periksa kembali isian formulir Anda.',
                    timer: 5000
                });
            @endif
        });
    </script>
</body>
</html>