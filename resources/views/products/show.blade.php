@php
    $images = $product->images->isEmpty()
        ? [['url' => 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&h=600&fit=crop', 'alt' => $product->name]]
        : $product->images->map(fn ($i) => ['url' => $i->url, 'alt' => $i->alt ?? $product->name])->all();
    $variants = $product->variants;

    // Only show option blocks when admin has configured them and there is a real choice (2+ values)
    $optionDefinitions = $product->optionDefinitions;
    $optionBlocks = $optionDefinitions->isEmpty()
        ? []
        : collect($optionDefinitions->map(fn ($def) => [
            'key' => $def->option_key,
            'label' => $def->option_label,
            'values' => $def->values->pluck('value')->all(),
        ])->all())->filter(fn ($block) => count($block['values']) >= 2)->values()->all();

    // Default selected variant: one that matches the first value in each option group
    $selectedVariant = $variants->first();
    if ($variants->isNotEmpty() && !empty($optionBlocks)) {
        $firstValues = collect($optionBlocks)->mapWithKeys(fn ($b) => [$b['key'] => $b['values'][0] ?? null])->filter()->all();
        $match = $variants->first(function ($v) use ($firstValues) {
            foreach ($firstValues as $key => $val) {
                if (($v->$key ?? '') !== $val) return false;
            }
            return true;
        });
        if ($match) $selectedVariant = $match;
    }
@endphp
@extends('layouts.app')

@section('title', $product->name . ' - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-6 md:py-10">
    {{-- Breadcrumb --}}
    <nav class="text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
        <span class="mx-1">/</span>
        @if($product->category)
            <a href="{{ route('home') }}?category={{ $product->category->slug }}" class="hover:text-primary">{{ $product->category->name }}</a>
            <span class="mx-1">/</span>
        @endif
        <span class="text-gray-900">{{ $product->name }}</span>
    </nav>

    <div class="grid gap-8 lg:grid-cols-[1.2fr,1fr]">
        {{-- Gallery --}}
        <div class="space-y-3">
            <div class="bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden aspect-square max-h-[480px]">
                <img id="product-main-image" src="{{ $images[0]['url'] ?? '' }}" alt="{{ $images[0]['alt'] ?? $product->name }}" class="max-h-full w-auto object-contain transition-opacity duration-200">
            </div>
            @if(count($images) > 1)
                <div class="flex gap-2 overflow-x-auto pb-1">
                    @foreach($images as $i => $img)
                        <button type="button" class="gallery-thumb flex-shrink-0 w-16 h-16 rounded-lg border-2 border-transparent overflow-hidden bg-gray-100 focus:border-primary focus:ring-2 focus:ring-primary/30" data-index="{{ $i }}" aria-label="View image {{ $i + 1 }}">
                            <img src="{{ $img['url'] }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <p class="text-xs text-gray-500 mb-1">{{ $product->brand }}</p>
            @if($product->is_on_sale && $product->sale_discount_percent)
                <span class="inline-block bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded uppercase mb-2">Sale</span>
            @endif
            <h1 class="text-2xl md:text-3xl font-display font-bold text-gray-900 mb-2">{{ $product->name }}</h1>

            @if($product->description)
                <div class="prose prose-sm max-w-none text-gray-700 mb-4">
                    {!! \Illuminate\Support\Str::markdown($product->description) !!}
                </div>
            @endif

            <div class="flex items-baseline gap-3 mb-6 flex-wrap">
                @if($product->is_on_sale && $product->sale_price !== null)
                    <span id="product-price" class="text-2xl font-bold text-primary">{{ $currency }}{{ number_format((float) $product->sale_price, 2) }}</span>
                    <span class="text-sm text-gray-400 line-through">{{ $currency }}{{ number_format((float) $product->retail_price, 2) }}</span>
                    <span class="text-sm font-medium text-accent">-{{ number_format((float) $product->sale_discount_percent, 0) }}%</span>
                @else
                    <span id="product-price" class="text-2xl font-bold text-primary">{{ $currency }}{{ number_format((float) ($selectedVariant->price ?? $product->base_price), 2) }}</span>
                    @if($variants->max('price') > $variants->min('price'))
                        <span class="text-sm text-gray-400 line-through">{{ $currency }}{{ number_format((float) $variants->max('price'), 2) }}</span>
                    @endif
                @endif
            </div>

            <form id="product-form" action="{{ route('cart.add') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" id="variant_id" value="{{ $selectedVariant?->id }}">
                <input type="hidden" name="quantity" value="1">

                @foreach($optionBlocks as $block)
                    <div class="variant-option-block" data-option-key="{{ $block['key'] }}">
                        <p class="text-sm font-semibold text-gray-900 mb-2">{{ $block['label'] }} <span class="text-red-500">*</span></p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($block['values'] as $loopIdx => $val)
                                @if($block['key'] === 'color')
                                    <label class="cursor-pointer">
                                        <input type="radio" name="{{ $block['key'] }}" value="{{ $val }}" class="sr-only peer variant-option-radio" {{ $loopIdx === 0 ? 'required' : '' }} {{ (($selectedVariant->{$block['key']} ?? '') === $val) ? 'checked' : '' }}>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-gray-200 peer-checked:border-primary peer-checked:ring-2 peer-checked:ring-primary/30">
                                            <span class="w-4 h-4 rounded-full border border-gray-300 shrink-0 bg-gray-400" title="{{ $val }}"></span>
                                            <span class="text-sm">{{ $val }}</span>
                                        </span>
                                    </label>
                                @else
                                    <label class="cursor-pointer">
                                        <input type="radio" name="{{ $block['key'] }}" value="{{ $val }}" class="sr-only peer variant-option-radio" {{ $loopIdx === 0 ? 'required' : '' }} {{ (($selectedVariant->{$block['key']} ?? '') === $val) ? 'checked' : '' }}>
                                        <span class="inline-block px-4 py-2 rounded-lg border-2 border-gray-200 text-sm font-medium peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary">{{ $val }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <p id="variant-stock" class="text-sm text-gray-600">
                    @if($selectedVariant && ($qty = $selectedVariant->inventory?->quantity ?? 0) > 0)
                        Only {{ $qty }} left in stock!
                    @elseif($selectedVariant)
                        Sold out
                    @endif
                </p>

                <div class="flex items-center gap-3">
                    <button type="submit" id="add-to-cart-btn" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed" {{ $selectedVariant && ($selectedVariant->inventory?->quantity ?? 0) <= 0 ? 'disabled' : '' }}>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Add to Cart
                    </button>
                </div>
            </form>

            @php
                $highlightText = $product->shipping_info ?: $product->warranty_info ?: $product->returns_info ?: null;
            @endphp
            @if($highlightText)
                <div class="mt-4 text-xs text-gray-600">
                    {!! \Illuminate\Support\Str::markdown($highlightText) !!}
                </div>
            @endif
        </div>
    </div>

    @php 
        $hasVariantInfo = $variants->isNotEmpty();
        $hasPayment = !empty(trim($product->payment_info ?? ''));
        $hasShipping = !empty(trim($product->shipping_info ?? ''));
        $hasReturns = !empty(trim($product->returns_info ?? ''));
        $hasWarranty = !empty(trim($product->warranty_info ?? ''));
        $hasOther = !empty(trim($product->other_policies ?? ''));

        $tabs = []; 
        if ($hasVariantInfo) $tabs[] = 'variant-info';
        if ($hasPayment) $tabs[] = 'payment';
        if ($hasShipping) $tabs[] = 'shipping';
        if ($hasReturns) $tabs[] = 'returns';
        if ($hasWarranty) $tabs[] = 'warranty';
        if ($hasOther) $tabs[] = 'other';
        $activeTab = $tabs[0] ?? null;
    @endphp   

    {{-- Description & policies tabs under gallery --}}
    @if($activeTab)
    <div class="mt-10 border-t border-gray-200 pt-6">
        <div class="flex flex-wrap gap-2 mb-4">
         
            @if($hasVariantInfo)
                <button
                    type="button"
                    class="tab-btn inline-flex items-center px-3 py-1.5 text-xs md:text-sm rounded-full transition-colors {{ $activeTab === 'variant-info' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    data-tab="variant-info">
                    Variant information
                </button>
            @endif
            @if($hasPayment)
                <button
                    type="button"
                    class="tab-btn inline-flex items-center px-3 py-1.5 text-xs md:text-sm rounded-full transition-colors {{ $activeTab === 'payment' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    data-tab="payment">
                    Payment
                </button>
            @endif
            @if($hasShipping)
                <button
                    type="button"
                    class="tab-btn inline-flex items-center px-3 py-1.5 text-xs md:text-sm rounded-full transition-colors {{ $activeTab === 'shipping' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    data-tab="shipping">
                    Shipping &amp; Delivery
                </button>
            @endif
            @if($hasReturns)
                <button
                    type="button"
                    class="tab-btn inline-flex items-center px-3 py-1.5 text-xs md:text-sm rounded-full transition-colors {{ $activeTab === 'returns' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    data-tab="returns">
                    Returns
                </button>
            @endif
            @if($hasWarranty)
                <button
                    type="button"
                    class="tab-btn inline-flex items-center px-3 py-1.5 text-xs md:text-sm rounded-full transition-colors {{ $activeTab === 'warranty' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    data-tab="warranty">
                    Warranty
                </button>
            @endif
            @if($hasOther)
                <button
                    type="button"
                    class="tab-btn inline-flex items-center px-3 py-1.5 text-xs md:text-sm rounded-full transition-colors {{ $activeTab === 'other' ? 'bg-primary text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}"
                    data-tab="other">
                    Other Policies
                </button>
            @endif
        </div>
        <div class="prose prose-sm max-w-none">
             
            @if($hasVariantInfo)
                <div id="tab-variant-info" class="tab-pane {{ $activeTab === 'variant-info' ? '' : 'hidden' }}">
                    <p class="text-gray-600">Color, storage and condition options are shown above. Each variant has its own price and stock.</p>
                    @if($variants->isNotEmpty())
                        <table class="min-w-full text-sm mt-2">
                            <thead><tr class="border-b"><th class="text-left py-2">Variant</th><th class="text-left py-2">Price</th><th class="text-left py-2">Stock</th></tr></thead>
                            <tbody>
                                @foreach($variants as $v)
                                    <tr class="border-b border-gray-100">
                                        <td class="py-2">{{ $v->variant_name ?? $v->color . ' / ' . $v->storage . ' / ' . $v->condition }}</td>
                                        <td class="py-2">{{ $currency }}{{ number_format((float) $v->price, 2) }}</td>
                                        <td class="py-2">{{ $v->inventory?->quantity ?? 0 }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            @endif
            @if($hasPayment)
                <div id="tab-payment" class="tab-pane {{ $activeTab === 'payment' ? '' : 'hidden' }}">
                    {!! \Illuminate\Support\Str::markdown($product->payment_info) !!}
                </div>
            @endif
            @if($hasShipping)
                <div id="tab-shipping" class="tab-pane {{ $activeTab === 'shipping' ? '' : 'hidden' }}">
                    {!! \Illuminate\Support\Str::markdown($product->shipping_info) !!}
                </div>
            @endif
            @if($hasReturns)
                <div id="tab-returns" class="tab-pane {{ $activeTab === 'returns' ? '' : 'hidden' }}">
                    {!! \Illuminate\Support\Str::markdown($product->returns_info) !!}
                </div>
            @endif
            @if($hasWarranty)
                <div id="tab-warranty" class="tab-pane {{ $activeTab === 'warranty' ? '' : 'hidden' }}">
                    {!! \Illuminate\Support\Str::markdown($product->warranty_info) !!}
                </div>
            @endif
            @if($hasOther)
                <div id="tab-other" class="tab-pane {{ $activeTab === 'other' ? '' : 'hidden' }}">
                    {!! \Illuminate\Support\Str::markdown($product->other_policies) !!}
                </div>
            @endif
        </div>
    </div>
    @endif

    @if($youMayAlsoLike->isNotEmpty())
        <section class="mt-14">
            <h2 class="text-xl font-display font-bold text-gray-900 mb-4">You may also like</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($youMayAlsoLike as $p)
                    @include('products.partials.card', ['product' => $p])
                @endforeach
            </div>
        </section>
    @endif

    @if($recentlyViewed->isNotEmpty())
        <section class="mt-14">
            <h2 class="text-xl font-display font-bold text-gray-900 mb-4">Recently Viewed</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($recentlyViewed as $p)
                    @include('products.partials.card', ['product' => $p])
                @endforeach
            </div>
        </section>
    @endif
</section>

@php
    $variantsJson = $variants->map(function ($v) {
        return [
            'id' => $v->id,
            'color' => $v->color,
            'storage' => $v->storage,
            'size' => $v->size,
            'condition' => $v->condition,
            'price' => (float) $v->price,
            'stock' => $v->inventory?->quantity ?? 0,
        ];
    })->values();
    $productScriptConfig = [
        'images' => $images,
        'variants' => $variantsJson->all(),
        'optionBlocks' => $optionBlocks,
        'productOnSale' => $product->is_on_sale && $product->sale_price !== null,
        'productSalePrice' => $product->is_on_sale && $product->sale_price !== null ? (float) $product->sale_price : null,
    ];
@endphp
@push('scripts')
<script>
(function () {
    var config = @json($productScriptConfig);
    var images = config.images;
    var variants = config.variants;
    var optionBlocks = config.optionBlocks;
    var productOnSale = config.productOnSale;
    var productSalePrice = config.productSalePrice;

    if (images.length > 1) {
        document.querySelectorAll('.gallery-thumb').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var i = parseInt(this.getAttribute('data-index'), 10);
                document.getElementById('product-main-image').src = images[i].url;
                document.querySelectorAll('.gallery-thumb').forEach(function (b) { b.classList.remove('border-primary'); });
                this.classList.add('border-primary');
            });
        });
    }

    function getSelected() {
        var selected = {};
        (optionBlocks || []).forEach(function (block) {
            var key = block.key;
            var radio = document.querySelector('input[name="' + key + '"]:checked');
            selected[key] = radio ? radio.value : null;
        });
        for (var i = 0; i < variants.length; i++) {
            var v = variants[i];
            var match = true;
            for (var k in selected) {
                if (selected[k] != null && v[k] !== selected[k]) { match = false; break; }
            }
            if (match) return v;
        }
        return variants[0] || null;
    }

    function updateVariant() {
        var v = getSelected();
        if (!v) return;
        document.getElementById('variant_id').value = v.id;
        var displayPrice = productOnSale && productSalePrice !== null ? productSalePrice : v.price;
        document.getElementById('product-price').textContent = (window.currencySymbol || '£') + displayPrice.toFixed(2);
        var stockEl = document.getElementById('variant-stock');
        var btn = document.getElementById('add-to-cart-btn');
        if (v.stock > 0) {
            stockEl.textContent = 'Only ' + v.stock + ' left in stock!';
            btn.disabled = false;
        } else {
            stockEl.textContent = 'Sold out';
            btn.disabled = true;
        }
    }

    document.querySelectorAll('.variant-option-radio').forEach(function (el) {
        el.addEventListener('change', updateVariant);
    });

    document.querySelectorAll('.tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tab = this.getAttribute('data-tab');
            document.querySelectorAll('.tab-btn').forEach(function (b) {
                b.classList.remove('bg-primary', 'text-white', 'shadow-sm');
                b.classList.add('bg-gray-100', 'text-gray-700');
            });
            this.classList.add('bg-primary', 'text-white', 'shadow-sm');
            this.classList.remove('bg-gray-100', 'text-gray-700');
            document.querySelectorAll('.tab-pane').forEach(function (p) { p.classList.add('hidden'); });
            var pane = document.getElementById('tab-' + tab);
            if (pane) pane.classList.remove('hidden');
        });
    });
})();
</script>
@endpush
@endsection
