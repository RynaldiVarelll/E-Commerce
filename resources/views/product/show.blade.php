@extends('layouts.frontend')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
            @if($product->images->count())
                <div class="space-y-2">
                    @foreach($product->images as $img)
                        <img src="{{ $img->image_url }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded">
                    @endforeach
                </div>
            @else
                <img src="{{ $product->image_url ?: 'https://via.placeholder.com/500' }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-96 object-cover rounded">
            @endif
        </div>
        <div>
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
            <p class="text-blue-600 text-xl font-semibold mt-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            <div class="mt-4 prose">
                {!! nl2br(e($product->description)) !!}
            </div>
            <a href="https://wa.me/{{ parse_url($product->whatsapp_link, PHP_URL_HOST) }}?text=Halo, saya tertarik dengan produk {{ urlencode($product->name) }}"
               target="_blank"
               class="mt-6 inline-block bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-md font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                Chat Admin via WhatsApp
            </a>
             <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">

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
</form>
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