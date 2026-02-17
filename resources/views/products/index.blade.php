@extends('layouts.app')

@section('title', ($brand ?? 'Products') . ' - LowPricePhones')

@section('content')
<div class="relative h-40 md:h-52 bg-cover bg-center flex items-center justify-center" style="background-image: url('https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1200&h=400&fit=crop')">
    <div class="absolute inset-0 bg-gray-900/40"></div>
    <h1 class="relative text-3xl md:text-4xl font-display font-bold text-white z-10">
        {{ $brand ? $brand . ' Phones' : 'Products' }}
    </h1>
</div>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-end gap-4 mb-6">
        <form action="{{ url()->current() }}" method="get" class="flex items-center gap-2">
            @foreach(request()->except('sort') as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <label class="text-sm text-gray-500">Sort:</label>
            <select name="sort" onchange="this.form.submit()" class="text-sm border border-gray-200 rounded px-2 py-1">
                <option value="az" {{ request('sort') == 'az' ? 'selected' : '' }}>Alphabetically, A-Z</option>
                <option value="za" {{ request('sort') == 'za' ? 'selected' : '' }}>Alphabetically, Z-A</option>
                <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price, low to high</option>
                <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price, high to low</option>
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Date, new to old</option>
            </select>
        </form>
        <span class="text-sm text-gray-500">{{ $products->total() }} products</span>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($products as $product)
            @include('products.partials.card', ['product' => $product])
        @empty
            <p class="col-span-full text-center text-gray-500 py-16">No products match your filters.</p>
        @endforelse
    </div>
    {{ $products->links() }}
</div>
@endsection
