<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? config('app.name', 'Invoify') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Instrument+Sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --blue-deep: #0a2463;
            --blue-mid: #1e5fb4;
            --blue-soft: #4a90d9;
            --blue-pale: #daeafc;
            --glass-bg: rgba(255, 255, 255, 0.75);
            --glass-border: rgba(255, 255, 255, 0.8);
            --glass-shadow: 0 25px 50px -12px rgba(10, 36, 99, 0.1);
            --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--font-sans);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: #daeafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(74, 144, 217, 0.3) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(200, 223, 248, 0.5) 0px, transparent 50%);
            background-attachment: fixed;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: var(--glass-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--glass-border);
            border-radius: 3rem;
            padding: 3.5rem 2.5rem;
            box-shadow: var(--glass-shadow);
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .auth-logo-wrap {
            width: 4.5rem;
            height: 4.5rem;
            border-radius: 1.5rem;
            background: linear-gradient(135deg, var(--blue-soft), var(--blue-deep));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 15px 30px rgba(30, 95, 180, 0.2);
            transform: rotate(-6deg);
            transition: transform 0.3s ease;
        }

        .auth-logo-wrap:hover { transform: rotate(0deg) scale(1.05); }

        .brand-name {
            font-size: 1.75rem;
            font-weight: 900;
            color: var(--blue-deep);
            letter-spacing: -0.05em;
            text-transform: uppercase;
            font-style: italic;
        }

        .brand-dot { color: var(--blue-soft); }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Merapikan Input Breeze agar konsisten dengan Glassmorphism */
        input:not([type="checkbox"]) {
            @apply block w-full bg-white/50 border-white/80 rounded-2xl px-5 py-4 text-sm font-bold text-blue-900 placeholder-blue-300 transition-all duration-300 shadow-sm !important;
        }
        
        input:focus {
            @apply ring-4 ring-blue-500/10 border-blue-400 bg-white outline-none !important;
        }

        button[type="submit"] {
            @apply w-full bg-blue-900 hover:bg-blue-800 text-white font-black py-5 rounded-[1.5rem] shadow-xl shadow-blue-900/10 active:scale-[0.97] transition-all duration-200 tracking-widest uppercase text-xs !important;
        }
    </style>
</head>
<body>

    <main class="auth-card">
        <div class="text-center mb-10">
            {{-- Logo Petir Sentral --}}
            <div class="auth-logo-wrap">
                <svg class="w-9 h-9 text-white fill-current" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>

            <div class="brand-name">
                Invoify<span class="brand-dot">.</span>
            </div>

            <h1 class="text-gray-400 text-[10px] font-black uppercase tracking-[0.3em] mt-3">
                {{ $pageTitle ?? 'Secure Login Portal' }}
            </h1>
        </div>

        {{-- Isi Form (Login.blade.php / Register.blade.php) --}}
        <div class="auth-content">
            {{ $slot }}
        </div>
    </main>

</body>
</html>