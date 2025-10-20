@extends('layouts.frontend')

@section('content')
<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">🛒 Shopping Cart</h1>

    <a href="{{ route('product.index') }}" 
       class="text-sm text-gray-600 hover:text-blue-600 mb-6 inline-block transition">
       ← Back to Products
    </a>

    @if($cartItems->isEmpty())
        <div class="text-center py-16 bg-gray-50 rounded-lg shadow-sm">
            <p class="text-gray-500 text-lg">Your cart is empty.</p>
            <a href="{{ route('product.index') }}" 
               class="mt-4 inline-block bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                Browse Products
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Cart Items --}}
            <div class="lg:col-span-2 space-y-4">
               @foreach($cartItems as $item)
<div class="flex flex-col sm:flex-row items-center justify-between bg-white shadow rounded-xl p-4 border border-gray-100">
    <div class="flex items-center space-x-4 w-full sm:w-auto">
        <img src="{{ $item->product->image_url ?? '/images/default.png' }}" 
             alt="{{ $item->product->name }}" 
             class="w-20 h-20 object-cover rounded-md border">
        <div>
            <h2 class="font-semibold text-gray-800">{{ $item->product->name }}</h2>
            <p class="text-sm text-gray-500">Rp{{ number_format($item->product->price, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="flex items-center justify-between sm:justify-end space-x-4 mt-4 sm:mt-0 w-full sm:w-auto">
        {{-- Quantity Update Form --}}
        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="flex items-center border rounded-lg overflow-hidden">
    @csrf
    @method('PATCH')
    <input type="hidden" name="quantity" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}">

    <button type="button" 
            onclick="updateQuantity({{ $item->id }}, -1)"
            class="px-3 py-1 bg-gray-100 hover:bg-gray-200">−</button>
    
    <span class="px-3 py-1 text-gray-800" id="display-quantity-{{ $item->id }}">{{ $item->quantity }}</span>
    
    <button type="button" 
            onclick="updateQuantity({{ $item->id }}, 1)"
            class="px-3 py-1 bg-gray-100 hover:bg-gray-200">+</button>
</form>

        {{-- Total --}}
        <div class="text-right">
            <p class="text-lg font-semibold text-gray-800">
                Rp{{ number_format($item->total_price, 0, ',', '.') }}
            </p>
        </div>

        {{-- Delete Button --}}
        <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 text-xl">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </div>
</div>
@endforeach

            </div>

            {{-- Cart Summary --}}
            <div class="bg-white shadow rounded-xl p-6 border border-gray-100 h-fit">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Order Summary</h2>
                
                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Subtotal</span>
                    <span>Rp{{ number_format($cartItems->sum('total_price'), 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-gray-600 mb-2">
                    <span>Shipping</span>
                    <span>Rp0</span>
                </div>
                <div class="border-t border-gray-200 my-3"></div>
                <div class="flex justify-between text-lg font-semibold text-gray-800">
                    <span>Total</span>
                    <span>Rp{{ number_format($cartItems->sum('total_price'), 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('checkout.page') }}" 
                   class="mt-6 block text-center bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @endif
</div>


<script>
function updateQuantity(itemId, delta) {
    const input = document.getElementById('quantity-' + itemId);
    const display = document.getElementById('display-quantity-' + itemId);
    let value = parseInt(input.value);

    // Hitung nilai baru
    let newValue = value + delta;

    // Minimal 1
    if (newValue < 1) return;

    // Update tampilan & input
    input.value = newValue;
    display.textContent = newValue;

    // Submit form otomatis
    input.form.submit();
}
</script>
@endsection
