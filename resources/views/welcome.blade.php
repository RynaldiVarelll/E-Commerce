@extends('layouts.frontend')
@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="container mx-auto px-4 py-16">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8">
            <!-- Hero Text -->
            <div class="flex-1 text-center lg:text-left">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">
                    Selamat Datang di TokoKu
                </h1>
                
                <p class="text-xl mb-6 text-blue-100">
                    Belanja mudah, cepat, dan terpercaya. Temukan produk berkualitas dengan harga terbaik!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" 
                       class="px-8 py-4 bg-white text-blue-600 rounded-lg font-bold text-lg hover:bg-blue-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                        Daftar Sekarang
                    </a>
                    <a href="{{ route('login') }}" 
                       class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-bold text-lg hover:bg-white hover:text-blue-600 transition-all duration-200">
                        Masuk
                    </a>
                </div>
            </div>
            
            <!-- Hero Image -->
            <div class="flex-1 max-w-md">
                <img src="https://via.placeholder.com/500x400" 
                     alt="Shopping" 
                     class="w-full rounded-lg shadow-2xl">
            </div>
        </div>
    </div>
</div>

<!-- Store Information Section -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">Kenapa Belanja di TokoKu?</h2>
            <p class="text-gray-600">Kami berkomitmen memberikan pengalaman belanja terbaik untuk Anda</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-truck-fast text-3xl text-blue-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Pengiriman Cepat</h3>
                <p class="text-gray-600">Produk sampai dengan aman dan tepat waktu ke tangan Anda</p>
            </div>
            
            <!-- Feature 2 -->
            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-shield-halved text-3xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Produk Berkualitas</h3>
                <p class="text-gray-600">Semua produk dijamin original dan berkualitas tinggi</p>
            </div>
            
            <!-- Feature 3 -->
            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-headset text-3xl text-purple-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Customer Service 24/7</h3>
                <p class="text-gray-600">Tim kami siap membantu Anda kapan saja</p>
            </div>
        </div>
    </div>
</div>

<!-- Categories Preview -->
<div class="container mx-auto px-4 py-12">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-3">Kategori Produk</h2>
        <p class="text-gray-600">Jelajahi berbagai kategori produk pilihan kami</p>
    </div>
    
    <div class="flex flex-wrap justify-center gap-3 mb-6">
        @if(isset($categories))
            @foreach($categories->take(6) as $category)
                <a href="#"
                   class="px-6 py-3 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">
                    {{ $category->name }}
                </a>
            @endforeach
        @else
            <a href="#" class="px-6 py-3 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">Elektronik</a>
            <a href="#" class="px-6 py-3 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">Fashion</a>
            <a href="#" class="px-6 py-3 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">Makanan</a>
            <a href="#" class="px-6 py-3 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">Kecantikan</a>
            <a href="#" class="px-6 py-3 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">Olahraga</a>
            <a href="#" class="px-6 py-3 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-200 shadow-sm hover:shadow-md">Perabotan</a>
        @endif
    </div>
</div>

<!-- Products Preview Section -->
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Produk Pilihan</h2>
                <p class="text-gray-600">Produk terbaik dan terpopuler minggu ini</p>
            </div>
            <a href="{{ route('product.index') }}" 
               class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition-colors duration-200">
                Lihat Semua
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            @if(isset($products))
                @foreach($products->take(10) as $product)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100">
                        <!-- Product Image -->
                        <div class="relative aspect-[1/1] overflow-hidden bg-gray-100">
                            <img src="{{ $product->image_url ?: 'https://via.placeholder.com/400' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 ease-in-out">
                            <div class="absolute top-2 right-2">
                                @if ($product->quantity <= 0)
                                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">HABIS</span>
                                @else
                                    <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">READY</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 min-h-[3rem] leading-tight">
                                {{ $product->name }}
                            </h3>
                            
                            <p class="text-xl font-bold text-blue-700 mb-3">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            
                            <a href="{{ route('login') }}"
                               class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition-colors duration-200">
                                Beli Sekarang
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Sample Products -->
                @for($i = 1; $i <= 5; $i++)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100">
                        <div class="relative aspect-[1/1] overflow-hidden bg-gray-100">
                            <img src="https://via.placeholder.com/400" 
                                 alt="Product {{ $i }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 ease-in-out">
                            <div class="absolute top-2 right-2">
                                <span class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">READY</span>
                            </div>
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2 line-clamp-2 min-h-[3rem] leading-tight">
                                Produk Berkualitas {{ $i }}
                            </h3>
                            
                            <p class="text-xl font-bold text-blue-700 mb-3">
                                Rp {{ number_format(100000 * $i, 0, ',', '.') }}
                            </p>
                            
                            <a href="{{ route('login') }}"
                               class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition-colors duration-200">
                                Beli Sekarang
                            </a>
                        </div>
                    </div>
                @endfor
            @endif
        </div>
    </div>
</div>

<!-- Call to Action Section -->
<div class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Mulai Belanja Sekarang!</h2>
        <p class="text-xl mb-8 text-blue-100">Daftar sekarang dan dapatkan penawaran khusus untuk member baru</p>
        <a href="{{ route('register') }}" 
           class="inline-block px-10 py-4 bg-white text-blue-600 rounded-lg font-bold text-lg hover:bg-blue-50 transition-all duration-200 shadow-lg hover:shadow-xl">
            Daftar Gratis
        </a>
    </div>
</div>

<!-- Statistics Section -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="text-center">
            <div class="text-4xl font-bold text-blue-600 mb-2">10,000+</div>
            <div class="text-gray-600">Produk Tersedia</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-bold text-blue-600 mb-2">50,000+</div>
            <div class="text-gray-600">Pelanggan Puas</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-bold text-blue-600 mb-2">100+</div>
            <div class="text-gray-600">Kategori Produk</div>
        </div>
        <div class="text-center">
            <div class="text-4xl font-bold text-blue-600 mb-2">4.8/5</div>
            <div class="text-gray-600">Rating Toko</div>
        </div>
    </div>
</div>
@endsection