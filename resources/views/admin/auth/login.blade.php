@extends('layouts.app')

@section('title', 'Admin Login - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10 max-w-md">
    <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Admin Login</h1>
    <p class="text-sm text-gray-600 mb-6">Sign in to access the admin area.</p>
    <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="admin-email">Email</label>
            <input id="admin-email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1" for="admin-password">Password</label>
            <input id="admin-password" type="password" name="password" required
                class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
        </div>
        <div class="flex items-center">
            <input id="admin-remember" type="checkbox" name="remember"
                class="rounded border-gray-200 text-primary focus:ring-primary/40">
            <label for="admin-remember" class="ml-2 text-sm text-gray-600">Remember me</label>
        </div>
        <button type="submit" class="w-full px-4 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">
            Admin Login
        </button>
    </form>
    <p class="mt-4 text-xs text-gray-500"><a href="{{ route('home') }}" class="text-primary font-medium hover:underline">Back to store</a></p>
</section>
@endsection
