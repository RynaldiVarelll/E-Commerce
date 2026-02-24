<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? config('app.name', 'Invoify') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Instrument+Sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --blue-deep: #0a2463;
            --blue-mid: #1e5fb4;
            --blue-soft: #4a90d9;
            --blue-pale: #c8dff8;
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-border: rgba(255, 255, 255, 0.8);
            --glass-shadow: 0 20px 50px rgba(10, 36, 99, 0.15);
            --glass-blur: blur(20px);
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
            /* Latar belakang gradasi yang lebih hidup */
            background-image: 
                radial-gradient(at 0% 0%, rgba(74, 144, 217, 0.4) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(30, 95, 180, 0.3) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(200, 223, 248, 0.5) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(125, 182, 243, 0.3) 0px, transparent 50%);
            background-attachment: fixed;
        }

        /* Card Container */
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: var(--glass-bg);
            backdrop-filter: var(--glass-blur);
            -webkit-backdrop-filter: var(--glass-blur);
            border: 1px solid var(--glass-border);
            border-radius: 2rem;
            padding: 3rem 2.25rem;
            box-shadow: var(--glass-shadow);
            animation: fadeIn 0.6s ease-out;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .auth-logo-wrap {
            width: 4rem;
            height: 4rem;
            border-radius: 1.25rem;
            background: linear-gradient(135deg, var(--blue-soft), var(--blue-deep));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
            box-shadow: 0 10px 20px rgba(30, 95, 180, 0.2);
            transform: rotate(-5deg);
        }

        .auth-logo-wrap svg {
            width: 2rem;
            height: 2rem;
            color: white;
        }

        .auth-app-name {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--blue-mid);
            margin-bottom: 0.5rem;
        }

        .auth-card-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--blue-deep);
            letter-spacing: -0.02em;
        }

        /* Otomatis Merapikan Form Breeze/Laravel di dalam Slot */
        .auth-card label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--blue-deep);
            margin-bottom: 0.5rem;
            display: block;
        }

        .auth-card input[type="email"],
        .auth-card input[type="password"],
        .auth-card input[type="text"] {
            width: 100%;
            padding: 0.85rem 1rem;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(10, 36, 99, 0.1);
            border-radius: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .auth-card input:focus {
            outline: none;
            border-color: var(--blue-soft);
            background: white;
            box-shadow: 0 0 0 4px rgba(74, 144, 217, 0.15);
        }

        .auth-card button[type="submit"] {
            width: 100%;
            background: var(--blue-deep);
            color: white;
            padding: 1rem;
            border-radius: 1rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .auth-card button[type="submit"]:hover {
            background: var(--blue-mid);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(10, 36, 99, 0.2);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>

    <main class="auth-card">
        <div class="auth-header">
            <div class="auth-logo-wrap">
                {{-- Icon Bolt/Invoify --}}
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>

            <div class="auth-app-name">
                {{ config('app.name', 'Invoify') }}
            </div>

            <h1 class="auth-card-title">
                {{ $pageTitle ?? 'Welcome Back' }}
            </h1>

            <p style="color: #64748b; font-size: 0.9rem; margin-top: 0.5rem;">
                {{ $pageSubtitle ?? 'Please enter your details' }}
            </p>
        </div>

        {{-- Konten Form (Login/Register) akan masuk ke sini --}}
        <div class="auth-content">
            {{ $slot }}
        </div>
    </main>

</body>
</html>