@extends('layouts.frontend')

@section('content')
<div class="relative overflow-hidden bg-[#0a58ca]">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[60%] bg-blue-400 rounded-full blur-[120px] opacity-20"></div>
        <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[60%] bg-indigo-500 rounded-full blur-[120px] opacity-30"></div>
    </div>

    <div class="container relative z-10 mx-auto px-6 py-20 lg:py-32">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="flex-1 text-center lg:text-left space-y-8">
                <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-100 text-sm backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-200 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-100"></span>
                    </span>
                    <span>New Season Collection 2026</span>
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-extrabold text-white leading-tight tracking-tight">
                    Smart Shopping <br/> 
                    <span class="text-blue-300 underline decoration-blue-400/30">Starts at invoify.</span>
                </h1>
                
                <p class="text-lg text-blue-100/80 max-w-xl mx-auto lg:mx-0 leading-relaxed">
                    Belanja cerdas dengan kurasi produk terbaik. Temukan kualitas premium yang dirancang khusus untuk memenuhi gaya hidup modern Anda.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ route('register') }}" 
                       class="group px-8 py-4 bg-white text-blue-600 rounded-2xl font-bold text-lg hover:scale-105 transition-all duration-300 shadow-[0_20px_50px_rgba(8,_112,_184,_0.7)]">
                        Mulai Belanja 
                        <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="{{ route('login') }}" 
                       class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-2xl font-bold text-lg hover:bg-white/20 transition-all duration-300">
                        Masuk Akun
                    </a>
                </div>
            </div>
            
            <div class="flex-1 relative group">
                <div class="absolute inset-0 bg-blue-400 rounded-3xl blur-3xl opacity-20 group-hover:opacity-40 transition-opacity duration-500"></div>
                <img src="{{ asset('images/app_logo.png') }}"   
                     alt="Shopping" 
                     class="relative w-full max-w-md mx-auto rounded-3xl transform hover:-rotate-2 transition-transform duration-500">
            </div>
        </div>
    </div>
</div>

<div class="bg-white py-24">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            @php
                $features = [
                    ['icon' => 'fa-truck-fast', 'color' => 'blue', 'title' => 'Pengiriman Kilat', 'desc' => 'Layanan logistik prioritas untuk memastikan pesanan tiba lebih cepat.'],
                    ['icon' => 'fa-shield-halved', 'color' => 'indigo', 'title' => 'Garansi Orisinal', 'desc' => 'Kami menjamin keaslian setiap produk dengan jaminan uang kembali.'],
                    ['icon' => 'fa-headset', 'color' => 'blue', 'title' => 'Dukungan Proaktif', 'desc' => 'Butuh bantuan? Tim ahli kami tersedia setiap saat untuk solusi cepat.']
                ];
            @endphp

            @foreach($features as $f)
            <div class="group p-8 rounded-3xl border border-gray-100 hover:border-blue-100 hover:bg-blue-50/30 transition-all duration-300">
                <div class="w-14 h-14 bg-{{ $f['color'] }}-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <i class="fa-solid {{ $f['icon'] }} text-2xl text-{{ $f['color'] }}-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $f['title'] }}</h3>
                <p class="text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="bg-gray-50 py-20">
    <div class="container mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Kategori Unggulan</h2>
                <p class="text-gray-500 mt-2">Pilih kategori yang sesuai dengan kebutuhan Anda hari ini.</p>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-4">
            @php
                $fallbackCats = ['Elektronik', 'Fashion', 'Gadget', 'Kecantikan', 'Lifestyle', 'Gaming'];
            @endphp
            @forelse($categories ?? [] as $category)
                <a href="#" class="px-8 py-3 bg-white rounded-full text-sm font-semibold text-gray-600 hover:bg-blue-600 hover:text-white border border-gray-200 hover:border-blue-600 transition-all duration-300 shadow-sm">
                    {{ $category->name }}
                </a>
            @empty
                @foreach($fallbackCats as $cat)
                    <a href="#" class="px-8 py-3 bg-white rounded-full text-sm font-semibold text-gray-600 hover:bg-blue-600 hover:text-white border border-gray-200 hover:border-blue-600 transition-all duration-300 shadow-sm">
                        {{ $cat }}
                    </a>
                @endforeach
            @endforelse
        </div>
    </div>
</div>

<div class="container mx-auto px-6 -mt-10 relative z-20">
    <div class="bg-white rounded-[2rem] shadow-2xl shadow-blue-900/10 p-10 border border-gray-50">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-gray-100">
            <div class="text-center px-4">
                <div class="text-3xl font-black text-gray-900">10k+</div>
                <div class="text-xs font-bold text-blue-500 uppercase tracking-widest mt-1">Produk</div>
            </div>
            <div class="text-center px-4">
                <div class="text-3xl font-black text-gray-900">50k+</div>
                <div class="text-xs font-bold text-blue-500 uppercase tracking-widest mt-1">Pembeli</div>
            </div>
            <div class="text-center px-4">
                <div class="text-3xl font-black text-gray-900">24/7</div>
                <div class="text-xs font-bold text-blue-500 uppercase tracking-widest mt-1">Support</div>
            </div>
            <div class="text-center px-4">
                <div class="text-3xl font-black text-gray-900">4.9</div>
                <div class="text-xs font-bold text-blue-500 uppercase tracking-widest mt-1">Rating</div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-6 py-24">
    <div class="relative bg-blue-600 rounded-[3rem] p-12 lg:p-20 overflow-hidden text-center text-white">
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-blue-500 rounded-full blur-3xl opacity-50"></div>
        <div class="relative z-10">
            <h2 class="text-4xl font-bold mb-6">Siap untuk Pengalaman Baru?</h2>
            <p class="text-blue-100 mb-10 text-lg max-w-2xl mx-auto">Bergabunglah dengan ribuan pelanggan lainnya dan dapatkan akses eksklusif ke promo mingguan kami.</p>
            <a href="{{ route('register') }}" 
               class="inline-block px-12 py-5 bg-white text-blue-600 rounded-2xl font-black text-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                Daftar Sekarang — Gratis
            </a>
        </div>
    </div>
</div>
@endsection