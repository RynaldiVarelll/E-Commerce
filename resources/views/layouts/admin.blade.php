<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">
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

        .dark .glass-panel {
            background: rgba(17, 24, 39, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }
        
        .sidebar-link.active {
            background: rgba(239, 246, 255, 0.7);
            color: #2563eb;
            border-right: 4px solid #2563eb;
            backdrop-filter: blur(10px);
        }

        .dark .sidebar-link.active {
            background: rgba(30, 41, 59, 0.7);
            color: #60a5fa;
            border-right-color: #60a5fa;
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
<body class="bg-[#f0f2f5] dark:bg-gray-950 text-gray-900 dark:text-gray-100 relative min-h-screen transition-colors duration-300">
    {{-- MacOS-like Abstract Background --}}
    <div class="fixed inset-0 z-[-1] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[60vw] h-[60vw] rounded-full bg-blue-200/40 dark:bg-blue-900/20 blur-[100px] animate-blob mix-blend-multiply"></div>
        <div class="absolute top-[20%] -right-[10%] w-[50vw] h-[50vw] rounded-full bg-blue-300/40 dark:bg-indigo-900/20 blur-[100px] animate-blob animation-delay-2000 mix-blend-multiply"></div>
        <div class="absolute -bottom-[20%] left-[20%] w-[70vw] h-[70vw] rounded-full bg-blue-100/40 dark:bg-blue-800/10 blur-[120px] animate-blob animation-delay-4000 mix-blend-multiply"></div>
    </div>

    <div class="flex min-h-screen">
        
        <aside class="w-72 glass-panel flex flex-col sticky top-0 h-screen z-40 border-r border-white/60 dark:border-white/5">
            <div class="p-8">
                <h1 class="text-2xl font-black text-blue-600 tracking-tighter flex items-center">
                    <i class="fa-solid fa-bag-shopping mr-2"></i> ADMIN<span class="text-gray-900 dark:text-white">POS</span>
                </h1>
            </div>

            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 mb-2">Main Menu</p>
                
                <a href="{{ route('admin.dashboard') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-blue-600 dark:hover:text-blue-400 rounded-2xl transition-all group {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-gauge mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Dashboard</span>
                </a>

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.categories.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Kategori</span>
                </a>
                @endif

                <a href="{{ route('admin.products.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-box mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Produk</span>
                </a>

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.shipping-methods.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.shipping-methods.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-truck-fast mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Shipping Delivery</span>
                </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.users.index') }}" 
                   class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-blue-600 rounded-2xl transition-all group {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                    <span class="font-bold">Data Pengguna</span>
                </a>
                @endif

                <div class="pt-6">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 px-4 mb-2">System</p>
                    <a href="{{ route('profile.edit') }}" 
                       class="sidebar-link flex items-center px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-blue-600 dark:hover:text-blue-400 rounded-2xl transition-all group {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-gear mr-3 text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="font-bold">Profil Saya</span>
                    </a>
                    <a href="{{ route('home') }}" 
                       class="flex items-center px-4 py-3 text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800/50 hover:text-blue-600 dark:hover:text-blue-400 rounded-2xl transition-all group">
                        <i class="fa-solid fa-house-user mr-3 text-lg"></i>
                        <span class="font-bold">Lihat Toko</span>
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-gray-100 dark:border-gray-800">
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
            <header class="glass-panel px-8 py-4 flex justify-between items-center sticky top-0 z-30 border-b border-white/60 dark:border-white/5">
                <div class="flex items-center">
                    {{-- Dark Mode Toggle --}}
                    <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)" 
                            class="p-2.5 bg-gray-50 dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all group">
                        <i class="fa-solid" :class="darkMode ? 'fa-sun text-yellow-400' : 'fa-moon'"></i>
                    </button>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-black text-gray-900 dark:text-white leading-none mb-1">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-none">
                            @if(auth()->user()->isSuperAdmin())
                                Super Admin
                            @elseif(auth()->user()->isAdmin())
                                Seller (Admin)
                            @else
                                Customer
                            @endif
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white font-black shadow-lg shadow-blue-200 dark:shadow-none overflow-hidden">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                </div>
            </header>

            <div class="p-8">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- Script untuk Flash Message Auto-Hide & SweetAlert2 --}}
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