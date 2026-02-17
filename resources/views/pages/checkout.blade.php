@extends('layouts.app')

@section('title', 'Checkout - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10 grid gap-8 lg:grid-cols-[2fr,1fr]">
    <div class="space-y-6">
        <h1 class="text-3xl font-display font-bold text-gray-900">Checkout</h1>
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-3 border border-gray-200 rounded-md p-4 bg-white">
                <h2 class="text-sm font-semibold text-gray-900">Shipping details</h2>
                <div class="space-y-2">
                    <input type="text" placeholder="Full name" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <input type="text" placeholder="Address" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" placeholder="City" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <input type="text" placeholder="Postcode" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                    </div>
                    <input type="text" placeholder="Country" value="United Kingdom" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
            </div>
            <div class="space-y-3 border border-gray-200 rounded-md p-4 bg-white">
                <h2 class="text-sm font-semibold text-gray-900">Card details</h2>
                <div class="space-y-2">
                    <input type="text" placeholder="Name on card" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <input type="text" placeholder="Card number" inputmode="numeric" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <div class="grid grid-cols-[1fr,1fr,0.8fr] gap-2">
                        <input type="text" placeholder="MM" inputmode="numeric" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <input type="text" placeholder="YY" inputmode="numeric" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <input type="password" placeholder="CVV" inputmode="numeric" class="w-full h-9 px-2 rounded-md border border-gray-200 text-xs focus:outline-none focus:ring-2 focus:ring-primary/40">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php $total = $cartItems->sum(fn ($i) => $i['product']->base_price * $i['quantity']); @endphp
    <aside class="border border-gray-200 rounded-md p-4 bg-white h-fit space-y-3">
        <h2 class="text-base font-semibold text-gray-900">Order summary</h2>
        <div class="space-y-1 text-xs text-gray-500 max-h-40 overflow-auto">
            @foreach($cartItems as $item)
            <div class="flex justify-between gap-2">
                <span class="truncate">{{ $item['quantity'] }} × {{ $item['product']->name }}</span>
                <span class="whitespace-nowrap">£{{ number_format($item['product']->base_price * $item['quantity'], 2) }}</span>
            </div>
            @endforeach
        </div>
        <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-200">
            <span class="text-gray-500">Total</span>
            <span class="font-semibold">£{{ number_format($total, 2) }}</span>
        </div>
        <button type="button" class="w-full px-4 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">Pay now</button>
    </aside>
</section>
@endsection
