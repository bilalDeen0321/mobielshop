@extends('layouts.app')

@section('title', 'Admin Dashboard - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-display font-bold text-gray-900">Admin Dashboard</h1>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-600 hover:text-primary">Logout</button>
        </form>
    </div>
    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 max-w-2xl">
        <div class="border border-gray-200 rounded-lg p-4 bg-white">
            <p class="text-sm text-gray-500">Products</p>
            <p class="text-2xl font-bold text-primary">{{ $productCount }}</p>
        </div>
        <div class="border border-gray-200 rounded-lg p-4 bg-white">
            <p class="text-sm text-gray-500">Categories</p>
            <p class="text-2xl font-bold text-primary">{{ $categoryCount }}</p>
        </div>
    </div>
    <p class="mt-6 text-sm text-gray-500">You are logged in as admin. Use the links above to manage the store.</p>
</section>
@endsection
