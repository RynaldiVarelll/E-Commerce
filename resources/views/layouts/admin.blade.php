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
        .sidebar-link.active {
            background-color: #eff6ff;
            color: #2563eb;
            border-right: 4px solid #2563eb;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="flex min-h-screen">
        
        <aside class="w-72 bg-white border-r border-gray-100 shadow-sm flex flex-col sticky top-0 h-screen">
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

                {{-- 🔥 MENU BARU: SHIPPING METHODS --}}
                <a href="{{ route('admin.shipping-methods.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.shipping-methods.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-fast mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Shipping Delivery</span>
                </a>

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

        <main class="flex-1 min-w-0 overflow-auto">
            <header class="bg-white border-b border-gray-100 px-8 py-4 flex justify-end items-center sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-gray-900">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Administrator</p>
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