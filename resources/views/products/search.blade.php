@extends('layouts.app')

@section('title', $query ? "Search: {$query}" : 'Search - LowPricePhones')

@section('content')
<div class="relative h-40 md:h-52 bg-cover bg-center flex items-center justify-center" style="background-image: url('https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1200&h=400&fit=crop')">
    <div class="absolute inset-0 bg-gray-900/40"></div>
    <h1 class="relative text-2xl md:text-3xl font-display font-bold text-white z-10 text-center px-4">
        {{ $query ? "Search: {$products->total()} Results For \"{$query}\"" : 'Search Products' }}
    </h1>
</div>

<div class="container mx-auto px-4 py-8">
    <form action="{{ route('search') }}" method="get" class="max-w-lg mx-auto mb-8 relative">
        <input type="text" name="q" value="{{ $query }}" placeholder="Search products..."
            class="w-full h-12 pl-4 pr-20 rounded-lg border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        <button type="submit" class="absolute right-1 top-1 h-10 w-10 bg-primary rounded-md flex items-center justify-center text-white hover:opacity-90">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>
    </form>

    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Left sidebar filters --}}
        <aside class="lg:w-56 shrink-0">
            <form action="{{ route('search') }}" method="get" id="search-filters-form" class="space-y-6 bg-white border border-gray-200 rounded-lg p-4 sticky top-4">
                @if($query)
                    <input type="hidden" name="q" value="{{ $query }}">
                @endif

                {{-- Price range --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Price range</h3>
                    <div class="flex items-center gap-2">
                        <label class="sr-only">Min price</label>
                        <input type="number" name="min_price" id="filter-min-price" min="{{ (int) $priceMin }}" max="{{ (int) $priceMax }}" step="1"
                            value="{{ $minPrice !== null ? (int) $minPrice : (int) $priceMin }}"
                            class="w-full h-9 px-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder="Min">
                        <span class="text-gray-400">–</span>
                        <label class="sr-only">Max price</label>
                        <input type="number" name="max_price" id="filter-max-price" min="{{ (int) $priceMin }}" max="{{ (int) $priceMax }}" step="1"
                            value="{{ $maxPrice !== null ? (int) $maxPrice : (int) $priceMax }}"
                            class="w-full h-9 px-2 rounded-md border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30" placeholder="Max">
                    </div>
                    <div class="mt-2">
                        <input type="range" id="price-slider-min" min="{{ (int) $priceMin }}" max="{{ (int) $priceMax }}" value="{{ $minPrice !== null ? (int) $minPrice : (int) $priceMin }}" class="w-full h-2 accent-primary">
                        <input type="range" id="price-slider-max" min="{{ (int) $priceMin }}" max="{{ (int) $priceMax }}" value="{{ $maxPrice !== null ? (int) $maxPrice : (int) $priceMax }}" class="w-full h-2 accent-primary mt-1">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">£<span id="price-display-min">{{ $minPrice !== null ? (int) $minPrice : (int) $priceMin }}</span> – £<span id="price-display-max">{{ $maxPrice !== null ? (int) $maxPrice : (int) $priceMax }}</span></p>
                </div>

                {{-- Category --}}
                @if($filterCategories->isNotEmpty())
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Category</h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('search', array_filter(['q' => $query ?: null, 'min_price' => $minPrice > 0 ? $minPrice : null, 'max_price' => $maxPrice > 0 && $maxPrice < 9999 ? $maxPrice : null, 'brand' => $brand ?: null])) }}"
                                    class="text-sm {{ !$categorySlug ? 'font-medium text-primary' : 'text-gray-600 hover:text-primary' }}">All categories</a>
                            </li>
                            @foreach($filterCategories as $cat)
                                <li>
                                    <a href="{{ route('search', array_filter(['q' => $query ?: null, 'category' => $cat->slug, 'min_price' => $minPrice > 0 ? $minPrice : null, 'max_price' => $maxPrice > 0 && $maxPrice < 9999 ? $maxPrice : null, 'brand' => $brand ?: null])) }}"
                                        class="text-sm {{ $categorySlug === $cat->slug ? 'font-medium text-primary' : 'text-gray-600 hover:text-primary' }}">{{ $cat->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Brand --}}
                @if($filterBrands->isNotEmpty())
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Brand</h3>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('search', array_filter(['q' => $query ?: null, 'category' => $categorySlug ?: null, 'min_price' => $minPrice > 0 ? $minPrice : null, 'max_price' => $maxPrice > 0 && $maxPrice < 9999 ? $maxPrice : null])) }}"
                                    class="text-sm {{ !$brand ? 'font-medium text-primary' : 'text-gray-600 hover:text-primary' }}">All brands</a>
                            </li>
                            @foreach($filterBrands as $b)
                                <li>
                                    <a href="{{ route('search', array_filter(['q' => $query ?: null, 'category' => $categorySlug ?: null, 'brand' => $b, 'min_price' => $minPrice > 0 ? $minPrice : null, 'max_price' => $maxPrice > 0 && $maxPrice < 9999 ? $maxPrice : null])) }}"
                                        class="text-sm {{ $brand === $b ? 'font-medium text-primary' : 'text-gray-600 hover:text-primary' }}">{{ $b }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <button type="submit" class="w-full py-2 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">Apply filters</button>
            </form>
        </aside>

        {{-- Results --}}
        <div class="flex-1 min-w-0">
            @if($query || $minPrice !== null || $maxPrice !== null || $categorySlug || $brand)
                <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                    @forelse($products as $product)
                        @include('products.partials.card', ['product' => $product])
                    @empty
                        <p class="col-span-full text-center text-gray-500 py-16">
                            No results found.
                            @if($query) Try a different search or filters. @endif
                        </p>
                    @endforelse
                </div>
                {{ $products->withQueryString()->links() }}
            @else
                <p class="text-center text-gray-500 py-16">Enter a search term (e.g. <strong>iphone</strong>) or use the filters to browse.</p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var form = document.getElementById('search-filters-form');
    var minInput = document.getElementById('filter-min-price');
    var maxInput = document.getElementById('filter-max-price');
    var minSlider = document.getElementById('price-slider-min');
    var maxSlider = document.getElementById('price-slider-max');
    var minDisplay = document.getElementById('price-display-min');
    var maxDisplay = document.getElementById('price-display-max');
    var absMin = parseInt(minSlider.min, 10);
    var absMax = parseInt(minSlider.max, 10);

    function updateMin(val) {
        val = Math.min(Math.max(parseInt(val, 10) || absMin, absMin), maxInput.value ? Math.min(parseInt(maxInput.value, 10), absMax) : absMax);
        minInput.value = val;
        minSlider.value = val;
        minDisplay.textContent = val;
    }
    function updateMax(val) {
        val = Math.max(Math.min(parseInt(val, 10) || absMax, absMax), minInput.value ? Math.max(parseInt(minInput.value, 10), absMin) : absMin);
        maxInput.value = val;
        maxSlider.value = val;
        maxDisplay.textContent = val;
    }

    if (minSlider) minSlider.addEventListener('input', function () { updateMin(this.value); });
    if (maxSlider) maxSlider.addEventListener('input', function () { updateMax(this.value); });
    if (minInput) minInput.addEventListener('change', function () { updateMin(this.value); });
    if (maxInput) maxInput.addEventListener('change', function () { updateMax(this.value); });
})();
</script>
@endpush
@endsection
