@extends('layouts.frontend')
@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Product List</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition duration-300">
                <a href="{{ route('product.show', $product->id) }}">
                    <img src="{{ asset(optional($product->images->first())->image_url ?? 'https://via.placeholder.com/150') }}" alt="{{ $product->name }}" class="w-full h-48 object-cover mb-4 rounded">
                    <h2 class="text-lg font-semibold mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-600 mb-2">{{ Str::limit($product->description, 50) }}</p>
                    <p class="text-blue-600 font-bold">Rp{{ number_format($product->price, 2) }}</p>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection