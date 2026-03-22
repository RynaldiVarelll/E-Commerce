<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
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
            // Success Toast
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    background: '#ffffff',
                    iconColor: '#2563eb',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-gray-100',
                    }
                });
            @endif

            // Status Notification (commonly used by Laravel Breeze)
            @if(session('status'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('status') === 'profile-updated' ? 'Profil Berhasil Diperbarui!' : session('status') }}',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    background: '#ffffff',
                    iconColor: '#2563eb',
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-gray-100',
                    }
                });
            @endif

            // Error Alert
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#2563eb',
                    customClass: {
                        popup: 'rounded-[2rem]',
                    }
                });
            @endif

            // Handle Validation Errors prominently
            @if($errors->any())
                Swal.fire({
                    icon: 'warning',
                    title: 'Format Tidak Valid!',
                    text: 'Silakan periksa kembali isian formulir Anda.',
                    confirmButtonColor: '#2563eb',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-2xl shadow-2xl border border-orange-100',
                    }
                });
            @endif
        </script>
    </body>
</html>
