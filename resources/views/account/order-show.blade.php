@extends('layouts.app')

@section('title', 'Order '.$order->order_number)

@section('content')
<section class="container mx-auto px-4 py-10 grid gap-8 lg:grid-cols-[1fr,3fr]">
    @include('account.partials.sidebar')

    <div class="space-y-4">
        <div class="bg-white border border-gray-200 rounded-md p-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-display font-bold text-gray-900 mb-1">Order #{{ $order->order_number }}</h1>
                <p class="text-sm text-gray-600">
                    Placed on {{ optional($order->placed_at ?? $order->created_at)->format('d M Y, H:i') }}
                </p>
            </div>
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold capitalize
                @if($order->status === 'completed') bg-green-50 text-green-700
                @elseif($order->status === 'processing') bg-blue-50 text-blue-700
                @elseif($order->status === 'cancelled') bg-red-50 text-red-700
                @else bg-yellow-50 text-yellow-800 @endif">
                {{ $order->status }}
            </span>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="bg-white border border-gray-200 rounded-md p-4">
                <h2 class="text-sm font-semibold text-gray-900 mb-2">Delivery address</h2>
                <p class="text-xs text-gray-700 whitespace-pre-line">{{ $order->shipping_address }}</p>
            </div>
            <div class="bg-white border border-gray-200 rounded-md p-4">
                <h2 class="text-sm font-semibold text-gray-900 mb-2">Order summary</h2>
                <dl class="text-xs text-gray-700 space-y-1">
                    <div class="flex justify-between">
                        <dt>Subtotal</dt>
                        <dd>{{ $currency }}{{ number_format($order->subtotal, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Tax</dt>
                        <dd>{{ $currency }}{{ number_format($order->tax_amount, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Shipping</dt>
                        <dd>{{ $currency }}{{ number_format($order->shipping_cost, 2) }}</dd>
                    </div>
                    <div class="flex justify-between font-semibold pt-1 border-t border-gray-100 mt-1">
                        <dt>Total</dt>
                        <dd>{{ $currency }}{{ number_format($order->total, 2) }}</dd>
                    </div>
                    @if($order->tracking_number)
                    <div class="pt-2">
                        <dt class="font-semibold">Tracking number</dt>
                        <dd class="text-gray-900">{{ $order->tracking_number }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-md p-4">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Items</h2>
            <div class="space-y-3 text-sm">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between border border-gray-100 rounded-md px-3 py-2">
                    <div>
                        <p class="font-semibold text-gray-900">{{ $item->product_name }}</p>
                        @if($item->variant_name)
                            <p class="text-xs text-gray-500">{{ $item->variant_name }}</p>
                        @endif
                    </div>
                    <div class="text-right text-xs">
                        <p class="text-gray-600">Qty: {{ $item->quantity }}</p>
                        <p class="text-gray-900 font-semibold">{{ $currency }}{{ number_format($item->unit_price, 2) }}</p>
                        <p class="text-gray-600">Line total: {{ $currency }}{{ number_format($item->line_total, 2) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <a href="{{ route('account.orders') }}" class="inline-flex items-center text-xs text-gray-600 hover:text-primary">
            ← Back to orders
        </a>
    </div>
</section>
@endsection

