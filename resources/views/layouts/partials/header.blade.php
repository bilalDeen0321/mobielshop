<header class="bg-white border-b border-gray-200 py-4">
    <div class="container mx-auto px-4 flex items-center justify-between gap-4">
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div class="hidden sm:block">
                <h1 class="text-xl font-display font-bold text-gray-900 leading-tight">Low<span class="text-primary">PricePhones</span></h1>
                <p class="text-[10px] text-gray-500 -mt-0.5">Best Deals on Unlocked Phones</p>
            </div>
        </a>

        <form action="{{ route('search') }}" method="get" class="flex-1 max-w-xl">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for phones, tablets, accessories..."
                    class="w-full h-11 pl-4 pr-12 rounded-lg border border-gray-200 bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
                <button type="submit" class="absolute right-1 top-1 h-9 w-10 bg-primary rounded-md flex items-center justify-center text-white hover:opacity-90">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>
            </div>
        </form>

        <div class="flex items-center gap-2">
            @auth
                <a href="{{ route('logout') }}" method="POST" class="hidden sm:flex flex-col items-center gap-0.5 text-gray-500 hover:text-primary p-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span class="text-[10px]">Logout</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="hidden sm:flex flex-col items-center gap-0.5 text-gray-500 hover:text-primary p-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="text-[10px]">Account</span>
                </a>
            @endauth
            <a href="{{ route('cart') }}" class="flex flex-col items-center gap-0.5 text-gray-500 hover:text-primary p-2 relative">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span class="text-[10px]">Cart</span>
                @php $cartCount = array_sum(session('cart', [])); @endphp
                @if($cartCount > 0)
                    <span class="absolute top-0.5 right-0.5 h-4 min-w-4 px-0.5 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center font-semibold">{{ $cartCount }}</span>
                @endif
            </a>
        </div>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
</header>
