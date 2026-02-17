@extends('layouts.app')

@section('title', 'Login - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10 max-w-md">
    <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Login</h1>
    <p class="text-sm text-gray-600 mb-6">Sign in to view your orders, wishlist and saved addresses.</p>
    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="password">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <button type="submit" class="w-full px-4 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">
            Login
        </button>
    </form>
    <p class="mt-4 text-xs text-gray-500">Don't have an account? <a href="{{ route('register') }}" class="text-primary font-medium hover:underline">Register</a></p>
</section>
@endsection
