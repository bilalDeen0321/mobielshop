@extends('layouts.app')

@section('title', 'Cart - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-display font-bold text-gray-900 mb-6">Your Cart</h1>
    @if($cartItems->isEmpty())
        <p class="text-sm text-gray-600">Your cart is currently empty. Browse our collections and add some great deals.</p>
    @else
        <div class="grid gap-8 lg:grid-cols-[2fr,1fr]">
            <div class="space-y-4">
                @foreach($cartItems as $item)
                <div class="flex items-center gap-4 border border-gray-200 rounded-md p-3 bg-white">
                    <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=100&h=100&fit=crop" alt="{{ $item['product']->name }}" class="w-16 h-16 object-contain bg-gray-100 rounded-md">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ $item['product']->name }}</p>
                        <p class="text-xs text-gray-500">{{ $item['product']->brand }}{!! $item['variant']->variant_name ? ' · ' . e($item['variant']->variant_name) : '' !!}</p>
                        <p class="text-sm font-medium text-primary mt-1">£{{ number_format((float) $item['variant']->price, 2) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('cart.update', $item['variant']->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="quantity" value="{{ max(0, $item['quantity'] - 1) }}">
                            <button type="submit" class="w-7 h-7 border border-gray-200 rounded-md text-sm">−</button>
                        </form>
                        <span class="w-8 text-center text-sm">{{ $item['quantity'] }}</span>
                        <form action="{{ route('cart.update', $item['variant']->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="quantity" value="{{ $item['quantity'] + 1 }}">
                            <button type="submit" class="w-7 h-7 border border-gray-200 rounded-md text-sm">+</button>
                        </form>
                    </div>
                    <form action="{{ route('cart.remove', $item['variant']->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs text-gray-500 hover:text-red-500">Remove</button>
                    </form>
                </div>
                @endforeach
            </div>
            @php $subtotal = $cartItems->sum(fn ($i) => (float) $i['variant']->price * $i['quantity']); @endphp
            <aside class="border border-gray-200 rounded-md p-4 bg-white h-fit space-y-3">
                <h2 class="text-base font-semibold text-gray-900">Summary</h2>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-semibold">£{{ number_format($subtotal, 2) }}</span>
                </div>
                <p class="text-xs text-gray-500">Shipping and discounts are calculated at checkout.</p>
                <a href="{{ route('checkout') }}" class="block w-full text-center px-4 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">Checkout</a>
                <form action="{{ route('cart.clear') }}" method="POST">
                    @csrf
                    <button type="submit" class="block w-full text-center px-4 py-2.5 rounded-md border border-gray-200 text-sm font-semibold text-gray-500 hover:bg-gray-50">Clear cart</button>
                </form>
            </aside>
        </div>
    @endif
</section>
@endsection
