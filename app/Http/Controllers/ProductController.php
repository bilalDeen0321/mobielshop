<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $products = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);

        if (strlen($query) >= 2) {
            $products = Product::query()
                ->where('is_active', true)
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('brand', 'like', "%{$query}%");
                })
                ->with(['category', 'variants.inventory'])
                ->orderBy('name')
                ->paginate(20)
                ->withQueryString();
        }

        return view('products.search', compact('products', 'query'));
    }

    public function show(string $slug)
    {
        $product = Product::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'variants.inventory'])
            ->firstOrFail();

        return view('products.show', compact('product'));
    }
}
