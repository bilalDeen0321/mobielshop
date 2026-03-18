@extends('layouts.app')

@section('title', 'Checkout - Ruislip Mobile')

@section('content')
@php
    $total = $cartItems->sum(fn ($i) => (float) $i['unit_price'] * $i['quantity']);
    $fieldClass = 'w-full h-10 px-3 rounded-md border text-sm focus:outline-none focus:ring-2 focus:ring-primary/40';
    $errorClass = 'border-red-300 bg-red-50';
    $normalClass = 'border-gray-200';
@endphp
<form action="{{ route('checkout.store') }}" method="POST">
    @csrf
    <section class="container mx-auto px-4 py-10 grid gap-8 lg:grid-cols-[2fr,1fr]">
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-display font-bold text-gray-900">Checkout</h1>
                <p class="mt-2 text-sm text-gray-500">Please complete all fields before placing your order.</p>
            </div>

            @if($errors->has('checkout'))
                <div class="rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first('checkout') }}
                </div>
            @endif

            <div class="grid gap-4 md:grid-cols-2">
                <div class="space-y-4 border border-gray-200 rounded-md p-5 bg-white">
                    <h2 class="text-sm font-semibold text-gray-900">Shipping details</h2>
                    <div class="space-y-3">
                        <div>
                            <input type="text" name="customer_name" placeholder="Full name" value="{{ old('customer_name', $checkoutDefaults['customer_name'] ?? '') }}" class="{{ $fieldClass }} {{ $errors->has('customer_name') ? $errorClass : $normalClass }}">
                            @error('customer_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <input type="email" name="customer_email" placeholder="Email address" value="{{ old('customer_email', $checkoutDefaults['customer_email'] ?? '') }}" class="{{ $fieldClass }} {{ $errors->has('customer_email') ? $errorClass : $normalClass }}">
                            @error('customer_email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <input type="text" name="customer_phone" placeholder="Phone number" value="{{ old('customer_phone', $checkoutDefaults['customer_phone'] ?? '') }}" class="{{ $fieldClass }} {{ $errors->has('customer_phone') ? $errorClass : $normalClass }}">
                            @error('customer_phone')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <input type="text" name="shipping_address" placeholder="Address" value="{{ old('shipping_address', $checkoutDefaults['shipping_address'] ?? '') }}" class="{{ $fieldClass }} {{ $errors->has('shipping_address') ? $errorClass : $normalClass }}">
                            @error('shipping_address')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <input type="text" name="shipping_city" placeholder="City" value="{{ old('shipping_city', $checkoutDefaults['shipping_city'] ?? '') }}" class="{{ $fieldClass }} {{ $errors->has('shipping_city') ? $errorClass : $normalClass }}">
                                @error('shipping_city')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <input type="text" name="shipping_postal_code" placeholder="Postcode" value="{{ old('shipping_postal_code', $checkoutDefaults['shipping_postal_code'] ?? '') }}" class="{{ $fieldClass }} {{ $errors->has('shipping_postal_code') ? $errorClass : $normalClass }}">
                                @error('shipping_postal_code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <input type="text" name="shipping_country" placeholder="Country" value="{{ old('shipping_country', $checkoutDefaults['shipping_country'] ?? 'United Kingdom') }}" class="{{ $fieldClass }} {{ $errors->has('shipping_country') ? $errorClass : $normalClass }}">
                            @error('shipping_country')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <div class="space-y-4 border border-gray-200 rounded-md p-5 bg-white">
                    <h2 class="text-sm font-semibold text-gray-900">Card details</h2>
                    <p class="text-xs text-gray-500">Your card is only validated for checkout flow. CVV is never stored.</p>
                    <div class="space-y-3">
                        <div>
                            <input type="text" name="card_name" placeholder="Name on card" value="{{ old('card_name', $checkoutDefaults['card_name'] ?? '') }}" class="{{ $fieldClass }} {{ $errors->has('card_name') ? $errorClass : $normalClass }}">
                            @error('card_name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <input type="text" name="card_number" placeholder="Card number" inputmode="numeric" autocomplete="cc-number" class="{{ $fieldClass }} {{ $errors->has('card_number') ? $errorClass : $normalClass }}">
                            @error('card_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div class="grid grid-cols-[1fr,1fr,0.9fr] gap-3">
                            <div>
                                <input type="number" name="card_expiry_month" placeholder="MM" min="1" max="12" value="{{ old('card_expiry_month', $checkoutDefaults['card_expiry_month'] ?? '') }}" class="{{ $fieldClass }} {{ $errors->has('card_expiry_month') ? $errorClass : $normalClass }}">
                                @error('card_expiry_month')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <input type="number" name="card_expiry_year" placeholder="YYYY" min="{{ now()->year }}" max="{{ now()->year + 20 }}" value="{{ old('card_expiry_year', $checkoutDefaults['card_expiry_year'] ?? '') }}" class="{{ $fieldClass }} {{ $errors->has('card_expiry_year') ? $errorClass : $normalClass }}">
                                @error('card_expiry_year')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <input type="password" name="card_cvv" placeholder="CVV" inputmode="numeric" autocomplete="off" class="{{ $fieldClass }} {{ $errors->has('card_cvv') ? $errorClass : $normalClass }}">
                                @error('card_cvv')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="border border-gray-200 rounded-md p-4 bg-white h-fit space-y-3">
            <h2 class="text-base font-semibold text-gray-900">Order summary</h2>
            <div class="space-y-2 text-xs text-gray-500 max-h-52 overflow-auto">
                @foreach($cartItems as $item)
                    <div class="flex justify-between gap-3">
                        <span class="truncate">{{ $item['quantity'] }} × {{ $item['product']->name }}{!! $item['variant_name'] ? ' (' . e($item['variant_name']) . ')' : '' !!}</span>
                        <span class="whitespace-nowrap">{{ $currency }}{{ number_format((float) $item['unit_price'] * $item['quantity'], 2) }}</span>
                    </div>
                @endforeach
            </div>
            <div class="flex items-center justify-between text-sm pt-2 border-t border-gray-200">
                <span class="text-gray-500">Total</span>
                <span class="font-semibold">{{ $currency }}{{ number_format($total, 2) }}</span>
            </div>
            <button type="submit" class="w-full px-4 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">Pay now</button>
        </aside>
    </section>
</form>
@endsection
