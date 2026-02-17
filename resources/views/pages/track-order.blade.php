@extends('layouts.app')

@section('title', 'Track Order - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10 max-w-xl">
    <h1 class="text-3xl font-display font-bold text-gray-900 mb-4">Track Your Order</h1>
    <p class="text-sm text-gray-600 mb-6">Enter your order number and email address to check the latest status and tracking details of your delivery.</p>
    <form class="space-y-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="order">Order number</label>
            <input id="order" type="text" class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="order-email">Email</label>
            <input id="order-email" type="email" class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">
            Track order
        </button>
    </form>
</section>
@endsection
