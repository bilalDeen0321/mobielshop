@extends('layouts.app')

@section('title', $product->name . ' - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10">
    <div class="grid gap-8 md:grid-cols-[1.2fr,1fr]">
        <div class="bg-gray-100 rounded-lg flex items-center justify-center p-6">
            <img src="https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&h=600&fit=crop" alt="{{ $product->name }}" class="max-h-[380px] w-auto object-contain">
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">{{ $product->brand }}</p>
            <h1 class="text-2xl md:text-3xl font-display font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
            <p class="text-sm text-gray-500 mb-4">Condition: {{ $product->condition }}</p>
            <div class="flex items-baseline gap-3 mb-6">
                <span class="text-2xl font-bold text-primary">£{{ number_format((float) ($product->variants->min('price') ?? $product->base_price), 2) }}</span>
            </div>
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="quantity" value="1">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90 mb-6">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Add to Cart
                </button>
            </form>
            @if($product->variants->count() > 0)
            <div class="mt-4">
                <h2 class="text-sm font-semibold text-gray-900 mb-2">Available variants</h2>
                <ul class="space-y-1 text-xs text-gray-500">
                    @foreach($product->variants as $v)
                    <li>{{ $v->variant_name ?? $v->sku }} — £{{ number_format((float) $v->price, 2) }} ({{ $v->inventory?->quantity ?? 0 }} in stock)</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
