<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? config('app.name', 'Invoify') }} — Secure Portal</title>

    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            overflow-x: hidden;
        }
        
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-[#f0f2f5] text-gray-900 antialiased min-h-screen relative flex items-center justify-center py-12 px-4">
    
    <div class="noise"></div>

    {{-- MacOS-like Abstract Background --}}
    <div class="fixed inset-0 z-[-2] overflow-hidden pointer-events-none">
        <div class="absolute -top-[10%] -left-[10%] w-[70vw] h-[70vw] rounded-full bg-blue-200/40 blur-[120px] animate-blob mix-blend-multiply"></div>
        <div class="absolute top-[10%] -right-[15%] w-[60vw] h-[60vw] rounded-full bg-purple-200/40 blur-[120px] animate-blob animation-delay-2000 mix-blend-multiply"></div>
        <div class="absolute -bottom-[20%] left-[10%] w-[80vw] h-[80vw] rounded-full bg-indigo-100/40 blur-[140px] animate-blob animation-delay-4000 mix-blend-multiply"></div>
        <div class="absolute top-[40%] left-[30%] w-[40vw] h-[40vw] rounded-full bg-blue-50/40 blur-[100px] animate-blob animation-delay-2000 opacity-50"></div>
    </div>
    
    <div class="w-full max-w-[520px] animate-fade-in opacity-0">
        <div class="glass-card rounded-[4rem] p-12 md:p-16 relative overflow-hidden border border-white/80 shadow-2xl shadow-blue-900/5">
            {{-- Visual Accents --}}
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-500 opacity-80"></div>
            <div class="absolute -top-32 -left-32 w-64 h-64 bg-blue-100/30 rounded-full blur-3xl pointer-events-none"></div>
            
            {{-- Back Button --}}
            <div class="relative z-20 mb-10">
                <a href="{{ route('home') }}" class="group inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-white/40 hover:bg-white/80 border border-white/60 hover:border-blue-200 transition-all duration-300 shadow-sm">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-arrow-left-long text-xs transition-transform group-hover:-translate-x-1"></i>
                    </div>
                    <span class="text-[10px] font-black text-gray-500 group-hover:text-blue-600 uppercase tracking-widest leading-none">Kembali ke Beranda</span>
                </a>
            </div>

            <div class="mb-14 relative z-10 text-left">
                {{-- Header Header --}}
                <div class="flex items-center justify-between mb-8">
                    <a href="{{ route('home') }}" class="inline-flex items-center space-x-3 group">
                        <div class="w-12 h-12 logo-gradient rounded-2xl flex items-center justify-center shadow-2xl shadow-blue-200 group-hover:rotate-12 transition-all duration-500">
                            <i class="fa-solid fa-bolt text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-gray-900">
                            invoify<span class="text-blue-600">.</span>
                        </span>
                    </a>
                    <div class="hidden sm:block">
                        <span class="text-[9px] font-black text-blue-600 bg-blue-50 px-3 py-1.5 rounded-full uppercase tracking-widest border border-blue-100">Portal Keamanan</span>
                    </div>
                </div>

                <h1 class="text-4xl font-black tracking-tight text-gray-900 leading-none mb-3">
                    {{ $pageTitle ?? 'Secure Gateway' }}
                </h1>
                
                @if(isset($pageSubtitle))
                    <p class="text-sm font-bold text-gray-400 leading-relaxed max-w-[280px]">{{ $pageSubtitle }}</p>
                @endif
            </div>

            <div class="relative z-10">
                {{ $slot }}
            </div>
        </div>

        {{-- Footer Credit --}}
        <div class="text-center mt-10 space-y-4">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">
                &copy; {{ date('Y') }} Invoify International
            </p>
            <div class="flex justify-center gap-6 text-center">
                <a href="#" class="text-[9px] font-bold text-gray-400 hover:text-blue-600 transition-colors uppercase tracking-widest">Privacy</a>
                <a href="#" class="text-[9px] font-bold text-gray-400 hover:text-blue-600 transition-colors uppercase tracking-widest">Terms</a>
                <a href="#" class="text-[9px] font-bold text-gray-400 hover:text-blue-600 transition-colors uppercase tracking-widest">Help</a>
            </div>
        </div>
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

        // Status Notification
        @if(session('status'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session('status') }}',
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

        // Validation Errors
        @if($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Format Salah!',
                text: 'Periksa kembali data Anda.',
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