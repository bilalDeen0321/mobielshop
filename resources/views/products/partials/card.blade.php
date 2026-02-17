@php
    $image = 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=400&fit=crop';
    $price = $product->variants->min('price') ?? $product->base_price;
    $maxPrice = $product->variants->max('price');
    $originalPrice = $maxPrice && $maxPrice > $price ? $maxPrice : null;
@endphp
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden product-card-hover group">
    <div class="relative aspect-square bg-gray-100 p-4 flex items-center justify-center overflow-hidden">
        <img src="{{ $image }}" alt="{{ $product->name }}"
            class="max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300">
        @if($originalPrice)
            @php $discount = round((($originalPrice - $price) / $originalPrice) * 100); @endphp
            <span class="absolute top-3 right-3 bg-accent text-white text-[10px] font-bold px-2 py-1 rounded">-{{ $discount }}%</span>
        @endif
        <form action="{{ route('cart.add') }}" method="POST" class="absolute bottom-3 left-3 right-3 flex justify-center gap-2 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="h-9 px-4 rounded-full bg-primary text-white flex items-center gap-1.5 text-xs font-semibold hover:opacity-90 shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Add to Cart
            </button>
        </form>
    </div>
    <div class="p-4">
        <p class="text-xs text-gray-500 mb-1">{{ $product->brand ?? 'Brand' }}</p>
        <a href="{{ route('product.show', $product->slug) }}" class="text-sm font-semibold text-gray-900 line-clamp-2 mb-2 block hover:text-primary">{{ $product->name }}</a>
        <div class="flex items-baseline gap-2">
            <span class="text-lg font-bold text-primary">£{{ number_format((float) $price, 2) }}</span>
            @if($originalPrice)
                <span class="text-sm text-gray-400 line-through">£{{ number_format((float) $originalPrice, 2) }}</span>
            @endif
        </div>
    </div>
</div>
