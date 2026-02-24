@extends('layouts.frontend')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-12">
        
        <div class="mb-12">
            <div class="flex items-center space-x-2 mb-6">
                <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Eksplor Kategori</h2>
            </div>
            
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('product.index') }}"
                   class="px-6 py-2.5 {{ !request('category') ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-gray-600 border border-gray-200' }} rounded-xl text-sm font-bold hover:scale-105 transition-all duration-200">
                    ✨ Semua Produk
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('product.index', ['category' => $category->id]) }}"
                       class="px-6 py-2.5 {{ request('category') == $category->id ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-gray-600 border border-gray-200' }} rounded-xl text-sm font-bold hover:border-blue-500 hover:text-blue-600 transition-all duration-200">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mb-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-900 tracking-tight">Semua Produk</h2>
                    <p class="text-gray-500 mt-1">Menampilkan <span class="text-blue-600 font-bold">{{ $products->count() }}</span> koleksi terbaik untukmu</p>
                </div>
                
                <div class="flex items-center bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm text-gray-600 shadow-sm">
                    <i class="fa-solid fa-sliders mr-2"></i>
                    <span class="font-medium">Urutkan: Terpopuler</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-8">
                @foreach($products as $product)
                    <div class="group bg-white rounded-[2rem] shadow-sm hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-500 overflow-hidden border border-gray-100 flex flex-col h-full">
                        
                        <div class="relative aspect-square overflow-hidden bg-gray-50">
                            <img src="{{ $product->image_url ?: 'https://via.placeholder.com/400' }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                            
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if ($product->quantity <= 0)
                                    <span class="backdrop-blur-md bg-red-500/80 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Sold Out</span>
                                @else
                                    <span class="backdrop-blur-md bg-blue-600/80 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-lg">Ready Stock</span>
                                @endif
                            </div>

                            <div class="absolute inset-0 bg-blue-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <a href="{{ route('product.show', $product->id) }}" 
                                   class="bg-white text-blue-600 p-4 rounded-full shadow-xl transform translate-y-10 group-hover:translate-y-0 transition-transform duration-500">
                                    <i class="fa-solid fa-magnifying-glass text-xl"></i>
                                </a>
                            </div>
                        </div>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="mb-auto">
                                <h3 class="font-bold text-gray-900 mb-1 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                                    {{ $product->name }}
                                </h3>
                                <div class="flex items-center text-[11px] text-gray-400 font-bold uppercase tracking-tighter mb-4">
                                    <i class="fa-solid fa-box-open mr-1"></i>
                                    Stok: <span class="{{ $product->quantity < 5 ? 'text-orange-500' : 'text-gray-500' }} ml-1">{{ $product->quantity }} pcs</span>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-2xl font-black text-blue-600 mb-4">
                                    <span class="text-sm font-medium">Rp</span>{{ number_format($product->price, 0, ',', '.') }}
                                </p>

                                <form action="{{ route('cart.add') }}" method="POST" class="space-y-3">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    @if ($product->quantity <= 0)
                                        <button type="button" disabled
                                                class="w-full bg-gray-100 text-gray-400 py-3 rounded-xl font-bold flex items-center justify-center cursor-not-allowed">
                                            <i class="fa-solid fa-circle-xmark mr-2"></i> Habis
                                        </button>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <div class="flex items-center bg-gray-100 rounded-xl p-1 border border-transparent focus-within:border-blue-200 transition-all">
                                                <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-blue-600 font-bold transition-colors" onclick="changeQuantity(this, -1)">−</button>
                                                <input type="number" name="quantity" value="1" min="1"
                                                       class="w-10 bg-transparent text-center text-sm font-bold text-gray-800 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                                <button type="button" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-blue-600 font-bold transition-colors" onclick="changeQuantity(this, 1)">+</button>
                                            </div>

                                            <button type="submit"
                                                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition-all duration-300 shadow-[0_10px_20px_rgba(37,_99,_235,_0.2)] hover:shadow-blue-300 active:scale-95">
                                                <i class="fa-solid fa-cart-plus"></i>
                                            </button>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- SweetAlert2 Library --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Fungsi untuk mengubah kuantitas
    function changeQuantity(btn, delta) {
        const input = btn.parentElement.querySelector('input[name="quantity"]');
        let value = parseInt(input.value || 1);
        value = Math.max(1, value + delta);
        input.value = value;
    }

    // Logic Notifikasi Berhasil (Toast)
    @if(session('success'))
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            background: '#ffffff',
            iconColor: '#2563eb',
            customClass: {
                popup: 'rounded-2xl shadow-xl border border-gray-100'
            }
        });
    @endif

    // Logic Notifikasi Error (Alert)
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '{{ session('error') }}',
            confirmButtonColor: '#2563eb',
            customClass: {
                popup: 'rounded-[2rem]'
            }
        });
    @endif
</script>

<style>
    /* Styling tambahan untuk membersihkan input number */
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
      -webkit-appearance: none; 
      margin: 0; 
    }
</style>
@endsection