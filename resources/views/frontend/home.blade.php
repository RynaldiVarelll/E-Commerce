@extends('layouts.frontend')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Categories Section -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4 text-gray-800">Kategori Produk</h2>
        <div class="flex flex-wrap gap-3">
            @foreach($categories as $category)
                <a href= "{{ route('product.index', ['category' => $category->id]) }}"
                   class="px-4 py-2 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-200">
                    {{ $category->name }}
                </a>
            @endforeach
            <a href="{{ route('product.index') }}"
               class="px-4 py-2 bg-white border-2 border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all duration-200">
                Semua Kategori
            </a>
        </div>
    </div>

    <!-- Products Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Semua Produk</h2>
            <p class="text-gray-600">{{ $products->count() }} produk tersedia</p>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100">
                    <!-- Product Image -->
                    <div class="relative aspect-[1/1] overflow-hidden bg-gray-100">
                        <img src="{{ $product->image_url ?: 'https://via.placeholder.com/400' }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out">
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

                        <i class="text-sm text-gray-600 mb-2">
                            Stok tersisa {{ $product->quantity }}
                        </i>
                        
                        <p class="text-xl font-bold text-blue-700 mb-3">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        
                        <a href="{{ route('product.show', $product->id) }}"
                           class="block w-full text-center mt-3 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2.5 rounded-lg font-semibold transition-colors duration-200">
                            Detail Produk
                        </a>
                       <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    @if ($product->quantity <= 0)
        <button type="button"
                onclick="alert('Produk ini sedang habis dan tidak dapat ditambahkan ke keranjang.')"
                class="w-full bg-gray-400 text-white py-2.5 rounded-lg font-semibold cursor-not-allowed">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> Stok Habis
        </button>
    @else
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center border rounded-lg">
                <button type="button" class="px-3 py-2 text-gray-600 font-bold" onclick="changeQuantity(this, -1)">−</button>
                <input type="number" name="quantity" value="1" min="1"
                       class="w-16 text-center border-l border-r focus:outline-none">
                <button type="button" class="px-3 py-2 text-gray-600 font-bold" onclick="changeQuantity(this, 1)">+</button>
            </div>

            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-semibold transition-colors duration-200">
                <i class="fa-solid fa-cart-plus"></i>
            </button>
        </div>
    @endif
</form>



                    
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>


<script>
function changeQuantity(btn, delta) {
    const input = btn.parentElement.querySelector('input[name="quantity"]');
    let value = parseInt(input.value || 1);
    value = Math.max(1, value + delta);
    input.value = value;
}
</script>

@endsection
