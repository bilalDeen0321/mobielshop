<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SliderImage;
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

        $sliderImages = SliderImage::orderBy('sort_order')->get();
        $defaultSlides = [
            ['url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1200&h=600&fit=crop', 'caption' => 'Latest smartphones'],
            ['url' => 'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=1200&h=600&fit=crop', 'caption' => 'Best Prices'],
            ['url' => 'https://images.unsplash.com/photo-1592286927505-d6d9c2d4b2c1?w=1200&h=600&fit=crop', 'caption' => 'New Arrivals 2025'],
        ];
        if ($sliderImages->count() >= 3) {
            $slides = $sliderImages->map(fn ($img) => ['url' => $img->url, 'caption' => $img->caption])->all();
        } else {
            $fromDb = $sliderImages->map(fn ($img) => ['url' => $img->url, 'caption' => $img->caption])->all();
            $slides = array_merge($fromDb, array_slice($defaultSlides, 0, 3 - count($fromDb)));
        }

        return view('pages.index', compact('featuredProducts', 'slides'));
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
