@extends('layouts.frontend')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Checkout</h1>

    <a href="{{ route('cart.index') }}" 
       class="text-sm text-gray-600 hover:text-blue-600 mb-4 inline-block">
        ← Back to Cart
    </a>
    @if (session('error'))
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
        <i class="fa-solid fa-circle-exclamation mr-2"></i>
        {{ session('error') }}
    </div>
@endif
        
    @if($cartItems->isEmpty())
        <div class="bg-white p-6 rounded-lg shadow text-center">
            <p class="text-gray-500">Your cart is empty.</p>
        </div>
    @else
    <form action="{{ route('checkout.process') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3">Product</th>
                        <th class="p-3 text-right">Price</th>
                        <th class="p-3 text-center">Quantity</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cartItems as $index => $item)
                        @php 
                            $subtotal = $item->product->price * $item->quantity; 
                            $total += $subtotal;
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">
                                <div>
                                    <span class="font-semibold">{{ $item->product->name }}</span>
                                    <input type="hidden" name="items[{{ $index }}][product_id]" value="{{ $item->product->id }}">
                                </div>
                            </td>
                            <td class="p-3 text-right text-gray-700">
                                Rp{{ number_format($item->product->price, 0, ',', '.') }}
                            </td>
                            <td class="p-3 text-center">
                                <input type="number" name="items[{{ $index }}][quantity]" 
                                       value="{{ $item->quantity }}" min="1"
                                       class="w-16 border-gray-300 rounded-lg text-center">
                            </td>
                            <td class="p-3 text-right font-semibold text-gray-800">
                                Rp{{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <div class="text-right">
                <p class="text-lg">Total:</p>
                <p class="text-2xl font-bold text-blue-600">
                    Rp{{ number_format($total, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="mt-8 text-right">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all duration-200">
                Confirm Checkout
            </button>
        </div>
    </form>
    @endif
</div>
@endsection
