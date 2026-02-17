<footer class="bg-nav text-white mt-auto">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <span class="text-lg font-display font-bold">Tech<span class="text-primary">Store</span></span>
                </div>
                <p class="text-sm opacity-70 mb-4">Your trusted source for the latest smartphones, tablets and accessories at the best prices.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4 text-sm">Quick Links</h4>
                <ul class="space-y-2">
                    @foreach([['Home', route('home')], ['Shop', route('shop')], ['About Us', route('about')], ['Contact', route('contact')], ['FAQs', route('faqs')]] as $link)
                        <li><a href="{{ $link[1] }}" class="text-sm opacity-70 hover:opacity-100 hover:text-primary">{{ $link[0] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4 text-sm">Categories</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('collections.all') }}?category=Mobile+Phones" class="text-sm opacity-70 hover:opacity-100 hover:text-primary">Mobile Phones</a></li>
                    <li><a href="{{ route('collections.all') }}?category=Tablets" class="text-sm opacity-70 hover:opacity-100 hover:text-primary">Tablets</a></li>
                    <li><a href="{{ route('collections.all') }}?category=Accessories" class="text-sm opacity-70 hover:opacity-100 hover:text-primary">Accessories</a></li>
                    <li><a href="{{ route('collections.all') }}?category=Headphones" class="text-sm opacity-70 hover:opacity-100 hover:text-primary">Headphones</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4 text-sm">Newsletter</h4>
                <p class="text-sm opacity-70 mb-3">Subscribe for exclusive deals and updates.</p>
                <form class="flex">
                    <input type="email" placeholder="Your email" class="flex-1 h-10 px-3 rounded-l-md bg-white/10 border-none text-sm placeholder:text-white/50 focus:outline-none focus:ring-1 focus:ring-primary">
                    <button type="submit" class="h-10 px-4 bg-primary text-white rounded-r-md text-sm font-semibold hover:opacity-90">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
    <div class="border-t border-white/10 py-4">
        <div class="container mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs opacity-60">
            <p>© {{ date('Y') }} TechStore. All rights reserved.</p>
            <div class="flex gap-4">
                <a href="{{ route('terms') }}" class="hover:text-primary">Terms & Conditions</a>
                <a href="{{ route('faqs') }}" class="hover:text-primary">FAQ'S</a>
                <a href="{{ route('track-order') }}" class="hover:text-primary">Track Your Order</a>
            </div>
        </div>
    </div>
</footer>
