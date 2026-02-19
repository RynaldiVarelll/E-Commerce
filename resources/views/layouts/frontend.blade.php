<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Simple</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/e16c014aae.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>body { font-family: 'Poppins', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
    <nav class="bg-white shadow">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-xl font-bold text-blue-600">Invoify</a>
            <div>
                @auth
    <div class="flex items-center space-x-4">
        {{--  Cart --}}
        <a href="{{ route('cart.index') }}" 
           class="text-sm text-gray-600 hover:text-blue-600 flex items-center">
            <i class="fa-solid fa-cart-shopping mr-1"></i> Cart
        </a>

        {{--  Admin /  User Profile --}}
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" 
               class="text-sm text-gray-600 hover:text-blue-600 flex items-center">
                <i class="fa-solid fa-gauge mr-1"></i> Admin
            </a>
        @else
            <a href="{{ route('profile.edit', auth()->user()->id) }}" 
               class="text-sm text-gray-600 hover:text-blue-600 flex items-center">
                <i class="fa-solid fa-user mr-1"></i> {{ auth()->user()->name }}
            </a>
        @endif

        {{--  Logout --}}
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" 
                    class="text-sm text-gray-600 hover:text-red-600 flex items-center">
                <i class="fa-solid fa-right-from-bracket mr-1"></i> Logout
            </button>
        </form>
    </div>
@endauth

@guest
    {{-- Jika belum login --}}
    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-blue-600 flex items-center">
        <i class="fa-solid fa-right-to-bracket mr-1"></i> Login
    </a>
@endguest



            </div>
        </div>
    </nav>

    <main class="container mx-auto px-6 py-8">
        @yield('content')
    </main>
    
</body>
</html>