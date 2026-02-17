@extends('layouts.app')

@section('title', 'LowPricePhones - Best Deals on Unlocked Phones')

@section('content')
<section class="relative overflow-hidden">
    <div class="relative">
        <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1200&h=600&fit=crop" alt="Latest smartphones"
            class="w-full h-[300px] sm:h-[400px] md:h-[480px] object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-gray-900/70 via-gray-900/30 to-transparent"></div>
        <div class="absolute inset-0 flex items-center">
            <div class="container mx-auto px-4">
                <p class="text-sm sm:text-base font-medium mb-2 text-accent">New Arrivals 2025</p>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-display font-extrabold leading-tight mb-3 text-white">
                    Latest Smartphones <br><span class="text-primary">Best Prices</span>
                </h2>
                <p class="text-sm sm:text-base text-white/90 mb-6 max-w-sm">
                    Discover amazing deals on the latest phones, tablets and accessories. Free shipping on orders over £250.
                </p>
                <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 bg-primary text-white px-7 py-3 rounded-lg font-semibold text-sm hover:opacity-90">
                    Shop Now
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-10 bg-gray-100">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-display font-bold text-gray-900 mb-6">Shop By Brand</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
            @foreach(['Apple', 'Samsung', 'Google', 'Huawei', 'OnePlus', 'Sony'] as $brand)
            <a href="{{ route('collections.brand', $brand) }}" class="bg-white rounded-lg p-5 flex flex-col items-center gap-3 border border-gray-200 product-card-hover group">
                <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center group-hover:bg-primary/10">
                    <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <p class="font-semibold text-gray-900 text-sm">{{ $brand }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<section id="products" class="py-10">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-display font-bold text-gray-900">Featured Products</h2>
            <a href="{{ route('collections.all') }}" class="text-sm font-medium text-primary hover:underline">View All →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($featuredProducts as $product)
                @include('products.partials.card', ['product' => $product])
            @empty
                <p class="col-span-full text-gray-500">No products yet. Run migrations and seed the database.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="py-8 border-t border-gray-200">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Free Delivery</p>
                    <p class="text-xs text-gray-500">On orders over £250</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Warranty</p>
                    <p class="text-xs text-gray-500">12 month guarantee</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Easy Returns</p>
                    <p class="text-xs text-gray-500">30 day return policy</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">24/7 Support</p>
                    <p class="text-xs text-gray-500">Dedicated support team</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
