@extends('layouts.app')

@section('title', $query ? "Search: {$query}" : 'Search - LowPricePhones')

@section('content')
<div class="relative h-40 md:h-52 bg-cover bg-center flex items-center justify-center" style="background-image: url('https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1200&h=400&fit=crop')">
    <div class="absolute inset-0 bg-gray-900/40"></div>
    <h1 class="relative text-2xl md:text-3xl font-display font-bold text-white z-10 text-center px-4">
        {{ $query ? "Search: {$products->total()} Results For \"{$query}\"" : 'Search Products' }}
    </h1>
</div>

<div class="container mx-auto px-4 py-8">
    <form action="{{ route('search') }}" method="get" class="max-w-lg mx-auto mb-8 relative">
        <input type="text" name="q" value="{{ $query }}" placeholder="Search products..."
            class="w-full h-12 pl-4 pr-20 rounded-lg border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        <button type="submit" class="absolute right-1 top-1 h-10 w-10 bg-primary rounded-md flex items-center justify-center text-white hover:opacity-90">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </button>
    </form>

    @if($query)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($products as $product)
            @include('products.partials.card', ['product' => $product])
        @empty
            <p class="col-span-full text-center text-gray-500 py-16">No results found for "{{ $query }}".</p>
        @endforelse
    </div>
    {{ $products->appends(['q' => $query])->links() }}
    @endif
</div>
@endsection
