@extends('layouts.app')

@section('title', 'My Account - Dashboard')

@section('content')
<section class="container mx-auto px-4 py-10 grid gap-8 lg:grid-cols-[1fr,3fr]">
    <aside class="space-y-4">
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Account menu</h2>
            <nav class="space-y-1 text-sm">
                <a href="{{ route('account.dashboard') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('account.dashboard') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">Dashboard</a>
                <a href="{{ route('account.orders') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('account.orders*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">Orders</a>
                <a href="{{ route('account.addresses') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('account.addresses') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">Addresses</a>
                <a href="{{ route('account.account') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('account.account') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">Account details</a>
            </nav>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-center px-4 py-2.5 rounded-md bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100">
                Logout
            </button>
        </form>
    </aside>

    <div class="space-y-6">
        <div class="bg-white border border-gray-200 rounded-md p-6">
            <h1 class="text-2xl font-display font-bold text-gray-900 mb-2">Welcome back, {{ $user->name }}!</h1>
            <p class="text-sm text-gray-600">
                This is your account dashboard. From here you can manage your account information, view your orders,
                update your addresses and securely change your password.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="bg-white border border-gray-200 rounded-md p-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Orders</h3>
                <p class="text-2xl font-semibold text-gray-900">{{ $recentOrders->count() }}</p>
                <p class="text-xs text-gray-500 mb-2">Recent orders placed with your account.</p>
                <a href="{{ route('account.orders') }}" class="text-xs font-semibold text-primary hover:underline">View all orders</a>
            </div>
            <div class="bg-white border border-gray-200 rounded-md p-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Addresses</h3>
                <p class="text-sm text-gray-900 mb-1">Manage your delivery and billing details.</p>
                <a href="{{ route('account.addresses') }}" class="text-xs font-semibold text-primary hover:underline">Manage addresses</a>
            </div>
            <div class="bg-white border border-gray-200 rounded-md p-4">
                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Account details</h3>
                <p class="text-sm text-gray-900 mb-1">Update your email, phone and password.</p>
                <a href="{{ route('account.account') }}" class="text-xs font-semibold text-primary hover:underline">Edit details</a>
            </div>
        </div>

        @if($recentOrders->isNotEmpty())
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Recent orders</h2>
            <div class="space-y-2 text-xs">
                @foreach($recentOrders as $order)
                <div class="flex items-center justify-between border border-gray-100 rounded-md px-3 py-2">
                    <div>
                        <p class="font-semibold text-gray-900">#{{ $order->order_number }}</p>
                        <p class="text-gray-500">{{ optional($order->placed_at ?? $order->created_at)->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">{{ $currency }}{{ number_format($order->total, 2) }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $order->status }}</p>
                        <a href="{{ route('account.orders.show', $order) }}" class="text-xs text-primary font-semibold hover:underline">View</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

