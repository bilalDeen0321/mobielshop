@extends('layouts.app')

@section('title', 'Wishlist - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-display font-bold text-gray-900 mb-6">Wishlist</h1>
    @if($wishlist->isEmpty())
        <p class="text-sm text-gray-600">Your wishlist is empty. Tap the heart icon on any product to save it for later.</p>
    @else
        <p class="text-sm text-gray-600">You have {{ $wishlist->count() }} items saved in your wishlist.</p>
    @endif
</section>
@endsection
