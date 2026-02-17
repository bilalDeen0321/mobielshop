@extends('layouts.app')

@section('title', 'Register - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10 max-w-md">
    <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Create account</h1>
    <p class="text-sm text-gray-600 mb-6">Create an account to checkout faster, track orders and manage your wishlist.</p>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="name">Full name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="password">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <button type="submit" class="w-full px-4 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">
            Register
        </button>
    </form>
    <p class="mt-4 text-xs text-gray-500">Already have an account? <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Login</a></p>
</section>
@endsection
