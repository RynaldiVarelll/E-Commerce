<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoify — Smart Shopping Experience</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/e16c014aae.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop -filter: blur(12px);
            border-bottom: 1px solid rgba(229, 231, 235, 0.5);
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-gray-900 leading-relaxed">
    
    <nav class="glass-nav sticky top-0 z-[100] transition-all duration-300">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 group-hover:rotate-6 transition-transform">
                    <i class="fa-solid fa-bolt text-white text-xl"></i>
                </div>
                <span class="text-2xl font-black tracking-tighter text-gray-900 group-hover:text-blue-600 transition-colors">
                    invoify<span class="text-blue-600">.</span>
                </span>
            </a>

            {{-- Right Menu --}}
            <div class="flex items-center gap-2">
                @auth
                    <div class="flex items-center space-x-1">
                        {{-- Cart Button with Badge --}}
                        <a href="{{ route('cart.index') }}" 
                           class="relative p-3 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-2xl transition-all group">
                            <i class="fa-solid fa-cart-shopping text-lg"></i>
                            <span class="absolute top-2 right-2 flex h-4 w-4">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-blue-600 text-[10px] text-white items-center justify-center font-bold">
                                    {{-- Jika ada variabel $cartCount bisa ditaruh di sini --}}
                                    !
                                </span>
                            </span>
                        </a>

                        {{-- User Interaction --}}
                        <div class="h-8 w-[1px] bg-gray-200 mx-2 hidden md:block"></div>

                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" 
                               class="hidden md:flex items-center px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
                                <i class="fa-solid fa-gauge-high mr-2 text-blue-600"></i> Admin
                            </a>
                        @else
                            <a href="{{ route('profile.edit', auth()->user()->id) }}" 
                               class="hidden md:flex items-center px-4 py-2 text-sm font-bold text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
                                <div class="w-8 h-8 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mr-2 font-black text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                {{ explode(' ', auth()->user()->name)[0] }}
                            </a>
                        @endif

                        {{-- Logout with Styling --}}
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="p-3 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-2xl transition-all"
                                    title="Logout">
                                <i class="fa-solid fa-right-from-bracket text-lg"></i>
                            </button>
                        </form>
                    </div>
                @endauth

                @guest
                    <div class="flex items-center gap-3">
                        <a href="{{ route('login') }}" 
                           class="px-6 py-2.5 text-sm font-bold text-gray-700 hover:text-blue-600 transition-colors">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
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
                nav.classList.add('py-2', 'shadow-xl', 'shadow-gray-200/20');
                nav.classList.remove('py-4');
            } else {
                nav.classList.add('py-4');
                nav.classList.remove('py-2', 'shadow-xl', 'shadow-gray-200/20');
            }
        };
    </script>
</body>
</html>