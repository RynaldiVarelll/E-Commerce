<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - E-Commerce</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- FontAwesome & Google Fonts --}}
    <script src="https://kit.fontawesome.com/e16c014aae.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        
        /* Glassmorphism Classes */
        .glass-panel {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }
        
        .sidebar-link.active {
            background: rgba(239, 246, 255, 0.7);
            color: #2563eb;
            border-right: 4px solid #2563eb;
            backdrop-filter: blur(10px);
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
<body class="bg-[#f0f2f5] text-gray-900 relative min-h-screen">
    {{-- MacOS-like Abstract Background --}}
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60vw] h-[60vw] rounded-full bg-gradient-to-br from-indigo-300/40 to-purple-300/40 blur-[100px] animate-blob mix-blend-multiply"></div>
        <div class="absolute top-[20%] -right-[10%] w-[50vw] h-[50vw] rounded-full bg-gradient-to-br from-blue-300/40 to-cyan-300/40 blur-[100px] animate-blob animation-delay-2000 mix-blend-multiply"></div>
        <div class="absolute -bottom-[20%] left-[20%] w-[70vw] h-[70vw] rounded-full bg-gradient-to-br from-pink-300/40 to-orange-200/40 blur-[120px] animate-blob animation-delay-4000 mix-blend-multiply"></div>
    </div>

    <div class="flex min-h-screen">
        
        <aside class="w-72 glass-panel flex flex-col sticky top-0 h-screen z-40 border-r border-white/60">
            <div class="p-8">
                <h1 class="text-2xl font-black text-blue-600 tracking-tighter flex items-center">
                    <i class="fa-solid fa-bag-shopping mr-2"></i> ADMIN<span class="text-gray-900">POS</span>
                </h1>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 mb-2">Main Menu</p>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Dashboard</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Kategori</span>
                </a>

                <a href="{{ route('admin.products.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Produk</span>
                </a>

                <a href="{{ route('admin.shipping-methods.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.shipping-methods.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-fast mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Shipping Delivery</span>
                </a>

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.users.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Kelola Admin</span>
                </a>
                @endif

                <div class="pt-6">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 mb-2">System</p>
                    <a href="{{ route('home') }}" 
                       class="flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group">
                        <i class="fa-solid fa-house-user mr-3 text-lg"></i>
                        <span class="font-bold">Lihat Toko</span>
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center w-full px-4 py-3 text-red-500 hover:bg-red-50 rounded-2xl transition-all group">
                        <i class="fa-solid fa-right-from-bracket mr-3 text-lg group-hover:translate-x-1 transition-transform"></i>
                        <span class="font-bold">Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 min-w-0 overflow-auto relative z-10">
            <header class="glass-panel px-8 py-4 flex justify-end items-center sticky top-0 z-30 border-b border-white/60">
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                            {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin Seller' }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black shadow-lg shadow-blue-200">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Script untuk Flash Message Auto-Hide (Optional) --}}
    <script>
        setTimeout(() => {
            const flash = document.getElementById('flash-message');
            if(flash) flash.style.display = 'none';
        }, 3000);
    </script>
</body>
</html>