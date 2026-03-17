<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CartItem;
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

        $brands = Brand::orderBy('sort_order')->orderBy('name')->get();

        return view('pages.index', compact('featuredProducts', 'slides', 'brands'));
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
        $sessionId = $request->session()->getId();
        $dbCartItems = CartItem::where('session_id', $sessionId)->get();

        if ($dbCartItems->isNotEmpty()) {
            $cartItems = $this->buildCartItemsFromDatabase($dbCartItems);
        } else {
            $cart = $request->session()->get('cart', []);
            $cartOptions = $request->session()->get('cart_options', []);
            if (! empty($cart)) {
                $this->syncSessionCartToDatabase($sessionId, $cart, $cartOptions);
                $dbCartItems = CartItem::where('session_id', $sessionId)->get();
                $cartItems = $this->buildCartItemsFromDatabase($dbCartItems);
            } else {
                $cartItems = $this->buildCartItemsFromSession($request);
            }
        }

        return view('pages.cart', compact('cartItems'));
    }

    /** Sync session cart and cart_options into cart_items table. */
    private function syncSessionCartToDatabase(string $sessionId, array $cart, array $cartOptions): void
    {
        foreach ($cart as $cartKey => $qty) {
            $cartKey = (int) $cartKey;
            $qty = (int) $qty;
            $options = $cartOptions[$cartKey] ?? null;
            CartItem::updateOrCreate(
                [
                    'session_id' => $sessionId,
                    'cart_key' => $cartKey,
                ],
                [
                    'quantity' => $qty,
                    'selected_options' => $options !== null && $options !== [] ? json_encode($options) : null,
                ]
            );
        }
    }

    /**
     * Build cart items from cart_items table (database).
     *
     * @param \Illuminate\Support\Collection<int, \App\Models\CartItem> $dbCartItems
     * @return \Illuminate\Support\Collection
     */
    private function buildCartItemsFromDatabase($dbCartItems)
    {
        $cartKeys = $dbCartItems->pluck('cart_key')->all();
        $variantIds = array_values(array_unique(array_filter($cartKeys, fn ($k) => (int) $k > 0)));
        $productOnlyIds = array_values(array_unique(array_map(fn ($k) => (int) abs((int) $k), array_filter($cartKeys, fn ($k) => (int) $k < 0))));
        $variants = $variantIds !== [] ? ProductVariant::with('product.images', 'product.optionDefinitions', 'inventory')->whereIn('id', $variantIds)->get()->keyBy('id') : collect();
        $products = $productOnlyIds ? Product::with('images')->whereIn('id', $productOnlyIds)->get()->keyBy('id') : collect();

        return $dbCartItems->map(function ($row) use ($variants, $products) {
            $key = (int) $row->cart_key;
            $qty = (int) $row->quantity;
            $storedOptions = $row->selected_options_array;

            if ($key > 0) {
                $variant = $variants->get($key);
                if (!$variant) return null;
                $selectedOptions = $this->buildSelectedOptionsForCartItem($variant, $variant->product, $storedOptions);
                return [
                    'variant' => $variant,
                    'product' => $variant->product,
                    'quantity' => $qty,
                    'selected_options' => $selectedOptions,
                ];
            }
            $product = $products->get(-$key);
            if (!$product) return null;
            $price = $product->sale_price ?? $product->retail_price ?? $product->base_price;
            $virtualVariant = (object) ['id' => $key, 'price' => $price, 'variant_name' => ''];
            return [
                'variant' => $virtualVariant,
                'product' => $product,
                'quantity' => $qty,
                'selected_options' => null,
            ];
        })->filter()->values();
    }

    /**
     * Build cart items from session (fallback when no cart_items in DB).
     */
    private function buildCartItemsFromSession(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $cartOptions = $request->session()->get('cart_options', []);
        $cartKeys = array_keys($cart);
        $variantIds = array_values(array_unique(array_map('intval', array_filter($cartKeys, fn ($k) => (int) $k > 0))));
        $productOnlyIds = array_values(array_unique(array_map(fn ($k) => (int) abs((int) $k), array_filter($cartKeys, fn ($k) => (int) $k < 0))));
        $variants = $variantIds !== [] ? ProductVariant::with('product.images', 'product.optionDefinitions', 'inventory')->whereIn('id', $variantIds)->get()->keyBy('id') : collect();
        $products = $productOnlyIds ? Product::with('images')->whereIn('id', $productOnlyIds)->get()->keyBy('id') : collect();

        return collect($cart)->map(function ($qty, $key) use ($variants, $products, $cartOptions) {
            $key = (int) $key;
            if ($key > 0) {
                $variant = $variants->get($key);
                if (!$variant) return null;
                $selectedOptions = $this->buildSelectedOptionsForCartItem($variant, $variant->product, $cartOptions[$key] ?? null);
                return [
                    'variant' => $variant,
                    'product' => $variant->product,
                    'quantity' => (int) $qty,
                    'selected_options' => $selectedOptions,
                ];
            }
            $product = $products->get(-$key);
            if (!$product) return null;
            $price = $product->sale_price ?? $product->retail_price ?? $product->base_price;
            $virtualVariant = (object) ['id' => $key, 'price' => $price, 'variant_name' => ''];
            return [
                'variant' => $virtualVariant,
                'product' => $product,
                'quantity' => (int) $qty,
                'selected_options' => null,
            ];
        })->filter()->values();
    }

    /**
     * Build selected options for a cart item: prefer options stored when adding to cart (from product detail page), else from variant.
     *
     * @param array<string, string>|null $storedOptions Options saved in session when user added to cart (e.g. ['color' => 'Black', 'storage' => '128GB'])
     * @return array<int, array{label: string, value: string}>|null
     */
    private function buildSelectedOptionsForCartItem($variant, $product = null, ?array $storedOptions = null): ?array
    {
        if ($storedOptions !== null && $storedOptions !== []) {
            $parts = $this->buildSelectedOptionsFromStored($storedOptions, $product);
            if ($parts !== []) {
                return $parts;
            }
        }
        return $this->buildSelectedOptionsFromVariant($variant, $product);
    }

    /**
     * Build selected options array from stored option keys/values (e.g. from session). Uses product option definitions for labels.
     *
     * @param array<string, string> $stored e.g. ['color' => 'Black', 'storage' => '128GB']
     * @return array<int, array{label: string, value: string}>
     */
    private function buildSelectedOptionsFromStored(array $stored, $product = null): array
    {
        $optionLabels = [
            'color' => 'Color',
            'storage' => 'Storage',
            'size' => 'Size',
            'condition' => 'Condition',
        ];
        if ($product && $product->relationLoaded('optionDefinitions') && $product->optionDefinitions->isNotEmpty()) {
            foreach ($product->optionDefinitions as $def) {
                $key = is_string($def->option_key) ? strtolower(trim($def->option_key)) : (string) $def->option_key;
                if ($key !== '' && array_key_exists($key, $optionLabels)) {
                    $optionLabels[$key] = $def->option_label;
                }
            }
        }
        $order = ['color', 'storage', 'size', 'condition'];
        $parts = [];
        foreach ($order as $attr) {
            $val = $stored[$attr] ?? $stored[ucfirst($attr)] ?? null;
            if ($val !== null && (string) $val !== '') {
                $label = $optionLabels[$attr] ?? ucfirst($attr);
                $parts[] = ['label' => $label, 'value' => (string) $val];
            }
        }
        return $parts;
    }

    /**
     * Build a list of selected option label/value pairs from the variant.
     * Always reads color, storage, size, condition from the variant; uses product option definitions for labels when available.
     *
     * @return array<int, array{label: string, value: string}>|null
     */
    private function buildSelectedOptionsFromVariant($variant, $product = null): ?array
    {
        $optionLabels = [
            'color' => 'Color',
            'storage' => 'Storage',
            'size' => 'Size',
            'condition' => 'Condition',
        ];
        if ($product && $product->relationLoaded('optionDefinitions') && $product->optionDefinitions->isNotEmpty()) {
            foreach ($product->optionDefinitions as $def) {
                $key = is_string($def->option_key) ? strtolower(trim($def->option_key)) : (string) $def->option_key;
                if ($key !== '' && array_key_exists($key, $optionLabels)) {
                    $optionLabels[$key] = $def->option_label;
                }
            }
        }

        $parts = [];
        foreach (['color' => 'Color', 'storage' => 'Storage', 'size' => 'Size', 'condition' => 'Condition'] as $attr => $defaultLabel) {
            $val = $variant->getAttribute($attr);
            if ($val !== null && (string) $val !== '') {
                $label = $optionLabels[$attr] ?? $defaultLabel;
                $parts[] = ['label' => $label, 'value' => (string) $val];
            }
        }
        if ($parts === [] && !empty($variant->variant_name)) {
            $parts[] = ['label' => 'Variant', 'value' => (string) $variant->variant_name];
        }
        if ($parts === [] && !empty($variant->sku)) {
            $parts[] = ['label' => 'SKU', 'value' => (string) $variant->sku];
        }
        return $parts === [] ? null : $parts;
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
        $cartKeys = array_keys($cart);
        $variantIds = array_filter($cartKeys, fn ($k) => (int) $k > 0);
        $productOnlyIds = array_map(fn ($k) => -(int) $k, array_filter($cartKeys, fn ($k) => (int) $k < 0));
        $variants = $variantIds ? ProductVariant::with('product.images', 'inventory')->whereIn('id', $variantIds)->get()->keyBy('id') : collect();
        $products = $productOnlyIds ? Product::with('images')->whereIn('id', $productOnlyIds)->get()->keyBy('id') : collect();
        $cartItems = collect($cart)->map(function ($qty, $key) use ($variants, $products) {
            $key = (int) $key;
            if ($key > 0) {
                $variant = $variants->get($key);
                return $variant ? ['variant' => $variant, 'product' => $variant->product, 'quantity' => (int) $qty] : null;
            }
            $product = $products->get(-$key);
            if (!$product) return null;
            $price = $product->sale_price ?? $product->retail_price ?? $product->base_price;
            $virtualVariant = (object) ['id' => $key, 'price' => $price, 'variant_name' => ''];
            return ['variant' => $virtualVariant, 'product' => $product, 'quantity' => (int) $qty];
        })->filter();

        return view('pages.checkout', compact('cartItems'));
    }
}
