@php
    $image = $product->images->first()?->url ?? 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=400&fit=crop';
    $onSale = $product->is_on_sale && $product->sale_discount_percent !== null && $product->sale_price !== null;
    if ($onSale) {
        $price = $product->sale_price;
        $originalPrice = (float) $product->retail_price;
        $discountPercent = (int) round((float) $product->sale_discount_percent);
    } else {
        $price = $product->variants->min('price') ?? $product->base_price;
        $maxPrice = $product->variants->max('price');
        $originalPrice = $maxPrice && $maxPrice > $price ? $maxPrice : null;
        $discountPercent = $originalPrice ? round((($originalPrice - $price) / $originalPrice) * 100) : null;
    }
@endphp
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden product-card-hover group relative">
    <div class="relative aspect-square bg-gray-100 p-4 flex items-center justify-center overflow-hidden">
        <a href="{{ route('product.show', $product->slug) }}" class="absolute inset-0 z-0 flex items-center justify-center p-4" aria-label="View {{ $product->name }}"></a>
        <img src="{{ $image }}" alt="{{ $product->name }}"
            class="relative z-0 max-h-full max-w-full object-contain group-hover:scale-105 transition-transform duration-300 pointer-events-none">
        @if($onSale)
            <span class="absolute top-3 left-3 bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase z-10">Sale</span>
            <span class="absolute top-3 right-3 bg-accent text-white text-[10px] font-bold px-2 py-1 rounded z-10">-{{ $discountPercent }}%</span>
        @elseif($discountPercent)
            <span class="absolute top-3 right-3 bg-accent text-white text-[10px] font-bold px-2 py-1 rounded z-10">-{{ $discountPercent }}%</span>
        @endif
        <form action="{{ route('cart.add') }}" method="POST" class="absolute bottom-3 left-3 right-3 flex justify-center gap-2 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-300 z-20">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="h-9 px-4 rounded-full bg-primary text-white flex items-center gap-1.5 text-xs font-semibold hover:opacity-90 shadow-sm">
                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Add to Cart
            </button>
        </form>
    </div>
    <a href="{{ route('product.show', $product->slug) }}" class="block p-4 hover:bg-gray-50/50 transition-colors">
        <p class="text-xs text-gray-500 mb-1">{{ $product->brand ?? 'Brand' }}</p>
        <p class="text-sm font-semibold text-gray-900 line-clamp-2 mb-2 group-hover:text-primary">{{ $product->name }}</p>
        <div class="flex items-baseline gap-2 flex-wrap">
            <span class="text-lg font-bold text-primary">£{{ number_format((float) $price, 2) }}</span>
            @if($originalPrice && (float)$originalPrice > (float)$price)
                <span class="text-sm text-gray-400 line-through">£{{ number_format((float) $originalPrice, 2) }}</span>
            @endif
        </div>
    </a>
</div>
