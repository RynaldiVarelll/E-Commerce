<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      x-data="{ darkMode: document.documentElement.classList.contains('dark') }" 
      @theme-updated.window="darkMode = $event.detail.isDark"
      :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- Icons & Fonts --}}
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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-950 text-gray-900 dark:text-gray-100 transition-colors duration-300">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow dark:shadow-gray-900 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        {{-- Global Notifications --}}
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
                        popup: 'rounded-[2rem]',
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
