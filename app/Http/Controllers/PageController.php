<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::query()
            ->where('is_active', true)
            ->with(['category', 'variants.inventory'])
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('pages.index', compact('featuredProducts'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function faqs()
    {
        return view('pages.faqs');
    }

    public function testimonials()
    {
        return view('pages.testimonials');
    }

    public function trackOrder()
    {
        return view('pages.track-order');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function cart(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $variantIds = array_keys($cart);
        $variants = ProductVariant::with('product', 'inventory')->whereIn('id', $variantIds)->get()->keyBy('id');
        $cartItems = collect($cart)->map(function ($qty, $variantId) use ($variants) {
            $variant = $variants->get((int) $variantId);
            return $variant ? ['variant' => $variant, 'product' => $variant->product, 'quantity' => (int) $qty] : null;
        })->filter();

        return view('pages.cart', compact('cartItems'));
    }

    public function wishlist(Request $request)
    {
        $wishlistIds = $request->session()->get('wishlist', []);
        $wishlist = Product::whereIn('id', $wishlistIds)->get();

        return view('pages.wishlist', compact('wishlist'));
    }

    public function checkout(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $variantIds = array_keys($cart);
        $variants = ProductVariant::with('product', 'inventory')->whereIn('id', $variantIds)->get()->keyBy('id');
        $cartItems = collect($cart)->map(function ($qty, $variantId) use ($variants) {
            $variant = $variants->get((int) $variantId);
            return $variant ? ['variant' => $variant, 'product' => $variant->product, 'quantity' => (int) $qty] : null;
        })->filter();

        return view('pages.checkout', compact('cartItems'));
    }
}
