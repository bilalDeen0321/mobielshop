@php
    $colors = [
        'Black' => '#1a1a1a',
        'Blue' => '#2563eb',
        'Gold' => '#d4af37',
        'Silver' => '#c0c0c0',
        'White' => '#f5f5f5',
        'Red' => '#dc2626',
        'Green' => '#16a34a',
        'Natural' => '#d4c4a8',
        'Default' => '#6b7280',
    ];
    $images = $product->images->isEmpty()
        ? [['url' => 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&h=600&fit=crop', 'alt' => $product->name]]
        : $product->images->map(fn ($i) => ['url' => $i->url, 'alt' => $i->alt ?? $product->name])->all();
    $variants = $product->variants;
    $selectedVariant = $variants->first();
    $uniqueColors = $variants->pluck('color')->filter()->unique()->values();
    $uniqueStorage = $variants->pluck('storage')->filter()->unique()->values();
    $uniqueCondition = $variants->pluck('condition')->filter()->unique()->values();
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
            <h1 class="text-2xl md:text-3xl font-display font-bold text-gray-900 mb-2">{{ $product->name }}</h1>

            <div class="flex items-baseline gap-3 mb-6">
                <span id="product-price" class="text-2xl font-bold text-primary">£{{ number_format((float) ($selectedVariant->price ?? $product->base_price), 2) }}</span>
                @if($variants->max('price') > $variants->min('price'))
                    <span class="text-sm text-gray-400 line-through">£{{ number_format((float) $variants->max('price'), 2) }}</span>
                @endif
            </div>

            <form id="product-form" action="{{ route('cart.add') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="variant_id" id="variant_id" value="{{ $selectedVariant?->id }}">
                <input type="hidden" name="quantity" value="1">

                @if($uniqueColors->isNotEmpty())
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-2">Color</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($uniqueColors as $c)
                                <label class="cursor-pointer">
                                    <input type="radio" name="color" value="{{ $c }}" class="sr-only peer color-radio" {{ ($selectedVariant->color ?? '') === $c ? 'checked' : '' }}>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border-2 border-gray-200 peer-checked:border-primary peer-checked:ring-2 peer-checked:ring-primary/30">
                                        <span class="w-4 h-4 rounded-full border border-gray-300 shrink-0" style="background-color: {{ $colors[$c] ?? $colors['Default'] }}"></span>
                                        <span class="text-sm">{{ $c }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($uniqueStorage->isNotEmpty())
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-2">Storage</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($uniqueStorage as $s)
                                <label class="cursor-pointer">
                                    <input type="radio" name="storage" value="{{ $s }}" class="sr-only peer storage-radio" {{ ($selectedVariant->storage ?? '') === $s ? 'checked' : '' }}>
                                    <span class="inline-block px-4 py-2 rounded-lg border-2 border-gray-200 text-sm font-medium peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary">{{ $s }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($uniqueCondition->isNotEmpty())
                    <div>
                        <p class="text-sm font-semibold text-gray-900 mb-2">Condition</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($uniqueCondition as $cond)
                                <label class="cursor-pointer">
                                    <input type="radio" name="condition" value="{{ $cond }}" class="sr-only peer condition-radio" {{ ($selectedVariant->condition ?? '') === $cond ? 'checked' : '' }}>
                                    <span class="inline-block px-4 py-2 rounded-lg border-2 border-gray-200 text-sm peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary">{{ $cond }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

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

            <ul class="mt-6 space-y-1 text-sm text-gray-600">
                <li class="flex items-center gap-2"><span class="text-primary">✓</span> Same Day Dispatch</li>
                <li class="flex items-center gap-2"><span class="text-primary">✓</span> 12 Months Warranty</li>
                <li class="flex items-center gap-2"><span class="text-primary">✓</span> Quality Guaranteed</li>
            </ul>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="mt-12 border-t border-gray-200 pt-8">
        <div class="flex flex-wrap gap-1 border-b border-gray-200 mb-6 overflow-x-auto">
            <button type="button" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 -mb-px border-primary text-primary" data-tab="description">Description</button>
            <button type="button" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-900 -mb-px" data-tab="variant-info">Variant information</button>
            <button type="button" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-900 -mb-px" data-tab="payment">Payment</button>
            <button type="button" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-900 -mb-px" data-tab="shipping">Shipping & Delivery</button>
            <button type="button" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-900 -mb-px" data-tab="returns">Returns</button>
            <button type="button" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-900 -mb-px" data-tab="warranty">Warranty</button>
            <button type="button" class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-900 -mb-px" data-tab="other">Other Policies</button>
        </div>
        <div class="prose prose-sm max-w-none">
            <div id="tab-description" class="tab-pane">
                {!! \Illuminate\Support\Str::markdown($product->description ?? 'No description.') !!}
            </div>
            <div id="tab-variant-info" class="tab-pane hidden">
                <p class="text-gray-600">Color, storage and condition options are shown above. Each variant has its own price and stock.</p>
                @if($variants->isNotEmpty())
                    <table class="min-w-full text-sm mt-2">
                        <thead><tr class="border-b"><th class="text-left py-2">Variant</th><th class="text-left py-2">Price</th><th class="text-left py-2">Stock</th></tr></thead>
                        <tbody>
                            @foreach($variants as $v)
                                <tr class="border-b border-gray-100">
                                    <td class="py-2">{{ $v->variant_name ?? $v->color . ' / ' . $v->storage . ' / ' . $v->condition }}</td>
                                    <td class="py-2">£{{ number_format((float) $v->price, 2) }}</td>
                                    <td class="py-2">{{ $v->inventory?->quantity ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div id="tab-payment" class="tab-pane hidden">{!! \Illuminate\Support\Str::markdown($product->payment_info ?? '—') !!}</div>
            <div id="tab-shipping" class="tab-pane hidden">{!! \Illuminate\Support\Str::markdown($product->shipping_info ?? '—') !!}</div>
            <div id="tab-returns" class="tab-pane hidden">{!! \Illuminate\Support\Str::markdown($product->returns_info ?? '—') !!}</div>
            <div id="tab-warranty" class="tab-pane hidden">{!! \Illuminate\Support\Str::markdown($product->warranty_info ?? '—') !!}</div>
            <div id="tab-other" class="tab-pane hidden">{!! \Illuminate\Support\Str::markdown($product->other_policies ?? '—') !!}</div>
        </div>
    </div>

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
            'condition' => $v->condition,
            'price' => (float) $v->price,
            'stock' => $v->inventory?->quantity ?? 0,
        ];
    })->values();
@endphp
@push('scripts')
<script>
(function () {
    var images = @json($images);
    var variants = @json($variantsJson);

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
        var color = document.querySelector('input[name="color"]:checked');
        var storage = document.querySelector('input[name="storage"]:checked');
        var condition = document.querySelector('input[name="condition"]:checked');
        var c = color ? color.value : null;
        var s = storage ? storage.value : null;
        var cond = condition ? condition.value : null;
        for (var i = 0; i < variants.length; i++) {
            var v = variants[i];
            if ((!c || v.color === c) && (!s || v.storage === s) && (!cond || v.condition === cond)) return v;
        }
        return variants[0] || null;
    }

    function updateVariant() {
        var v = getSelected();
        if (!v) return;
        document.getElementById('variant_id').value = v.id;
        document.getElementById('product-price').textContent = '£' + v.price.toFixed(2);
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

    document.querySelectorAll('.color-radio, .storage-radio, .condition-radio').forEach(function (el) {
        el.addEventListener('change', updateVariant);
    });

    document.querySelectorAll('.tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tab = this.getAttribute('data-tab');
            document.querySelectorAll('.tab-btn').forEach(function (b) {
                b.classList.remove('border-primary', 'text-primary');
                b.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.add('border-primary', 'text-primary');
            this.classList.remove('border-transparent', 'text-gray-500');
            document.querySelectorAll('.tab-pane').forEach(function (p) { p.classList.add('hidden'); });
            var pane = document.getElementById('tab-' + tab);
            if (pane) pane.classList.remove('hidden');
        });
    });
})();
</script>
@endpush
@endsection
