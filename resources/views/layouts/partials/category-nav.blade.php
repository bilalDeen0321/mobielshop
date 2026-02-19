<nav class="bg-nav text-white">
    <div class="container mx-auto px-4 flex items-center">
        <div class="relative group">
            <span class="flex items-center gap-2 bg-primary text-white px-5 py-3.5 font-semibold text-sm cursor-default">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                SHOP BY CATEGORIES
            </span>
            @if(isset($navCategories) && $navCategories->isNotEmpty())
            <div class="absolute left-0 top-full mt-0.5 bg-white text-gray-900 border border-gray-200 rounded-md shadow-lg z-30 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150">
                @foreach($navCategories as $category)
                <a href="{{ route('shop') }}?category={{ urlencode($category->slug) }}" class="block px-4 py-2.5 text-sm hover:bg-gray-100 first:rounded-t-md last:rounded-b-md {{ $loop->first ? 'rounded-t-md' : '' }} {{ $loop->last ? 'rounded-b-md' : '' }}">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
        <div class="hidden md:flex items-center gap-1 ml-2">
            <a href="{{ route('home') }}" class="px-4 py-3.5 text-sm font-medium hover:text-primary {{ request()->routeIs('home') ? 'text-primary' : '' }}">Home</a>
            <a href="{{ route('shop') }}" class="px-4 py-3.5 text-sm font-medium hover:text-primary">Shop</a>
            <a href="{{ route('collections.all') }}" class="px-4 py-3.5 text-sm font-medium hover:text-primary">Collections</a>
            <a href="{{ route('shop') }}?sale=true" class="px-4 py-3.5 text-sm font-bold text-accent">Sale</a>
            <a href="{{ route('about') }}" class="px-4 py-3.5 text-sm font-medium hover:text-primary">About Us</a>
            <a href="{{ route('contact') }}" class="px-4 py-3.5 text-sm font-medium hover:text-primary">Contact</a>
            <a href="{{ route('testimonials') }}" class="px-4 py-3.5 text-sm font-medium hover:text-primary">Testimonial</a>
            <a href="{{ route('faqs') }}" class="px-4 py-3.5 text-sm font-medium hover:text-primary">FAQ'S</a>
            <a href="{{ route('track-order') }}" class="px-4 py-3.5 text-sm font-medium hover:text-primary">Track Your Order</a>
        </div>
    </div>
</nav>
