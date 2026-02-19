<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\RecentlyViewed;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->where('is_active', true)
            ->with(['category', 'variants.inventory']);

        if ($brand = $request->get('brand') ?? $request->route('brand')) {
            $query->where('brand', $brand);
        }

        if ($category = $request->get('category')) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $sort = $request->get('sort', 'az');
        match ($sort) {
            'price-asc' => $query->orderBy('base_price', 'asc'),
            'price-desc' => $query->orderBy('base_price', 'desc'),
            'za' => $query->orderBy('name', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('name', 'asc'),
        };

        $products = $query->paginate(20)->withQueryString();
        $brand = $request->route('brand');

        return view('products.index', compact('products', 'brand'));
    }

    public function suggest(Request $request)
    {
        $q = $request->get('q', '');
        $q = trim($q);
        if (strlen($q) < 2) {
            return response()->json(['data' => []]);
        }

        $products = Product::query()
            ->where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('brand', 'like', "%{$q}%");
            })
            ->with(['variants'])
            ->orderBy('name')
            ->limit(8)
            ->get();

        $data = $products->map(function ($p) {
            $price = $p->variants->min('price') ?? $p->base_price;
            return [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'brand' => $p->brand,
                'price' => (float) $price,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));
        $minPrice = $request->has('min_price') ? (float) $request->get('min_price') : null;
        $maxPrice = $request->has('max_price') ? (float) $request->get('max_price') : null;
        $categorySlug = $request->get('category', '');
        $brand = $request->get('brand', '');

        $baseQuery = Product::query()->where('is_active', true);

        if (strlen($query) >= 2) {
            $baseQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('brand', 'like', "%{$query}%");
            });
        }

        $priceRange = (clone $baseQuery)->selectRaw('MIN(base_price) as min_price, MAX(base_price) as max_price')->first();
        $priceMin = $priceRange && $priceRange->min_price !== null ? (float) $priceRange->min_price : 0;
        $priceMax = $priceRange && $priceRange->max_price !== null ? (float) $priceRange->max_price : 9999;

        if ($minPrice !== null && $minPrice > 0) {
            $baseQuery->where('base_price', '>=', $minPrice);
        }
        if ($maxPrice !== null && $maxPrice > 0) {
            $baseQuery->where('base_price', '<=', $maxPrice);
        }
        if ($categorySlug !== '') {
            $baseQuery->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
        }
        if ($brand !== '') {
            $baseQuery->where('brand', $brand);
        }

        $products = $baseQuery->with(['category', 'variants.inventory'])
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $filterCategories = \App\Models\Category::query()
            ->whereHas('products', fn ($q) => $q->where('is_active', true))
            ->orderBy('name')
            ->get();

        $filterBrands = Product::query()
            ->where('is_active', true)
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        return view('products.search', compact(
            'products', 'query', 'priceMin', 'priceMax',
            'minPrice', 'maxPrice', 'categorySlug', 'brand',
            'filterCategories', 'filterBrands'
        ));
    }

    public function show(Request $request, string $slug)
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'variants.inventory', 'images'])
            ->firstOrFail();

        $sessionId = $request->session()->getId();
        RecentlyViewed::updateOrCreate(
            [
                'session_id' => $sessionId,
                'product_id' => $product->id,
            ],
            [
                'user_id' => $request->user()?->id,
                'viewed_at' => now(),
            ]
        );

        $youMayAlsoLike = Product::query()
            ->where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                $q->where('category_id', $product->category_id)
                  ->orWhere('brand', $product->brand);
            })
            ->with(['variants.inventory', 'images'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $recentlyViewedIds = RecentlyViewed::query()
            ->where('session_id', $sessionId)
            ->where('product_id', '!=', $product->id)
            ->orderByDesc('viewed_at')
            ->limit(20)
            ->get()
            ->unique('product_id')
            ->take(4)
            ->pluck('product_id');

        $recentlyViewed = $recentlyViewedIds->isEmpty()
            ? collect()
            : Product::query()
                ->whereIn('id', $recentlyViewedIds)
                ->where('is_active', true)
                ->with(['variants.inventory', 'images'])
                ->get()
                ->sortBy(fn ($p) => array_search($p->id, $recentlyViewedIds->all()));

        return view('products.show', compact('product', 'youMayAlsoLike', 'recentlyViewed'));
    }
}
