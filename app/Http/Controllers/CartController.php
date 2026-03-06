<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /** Cart key for product-without-variant: negative product id. */
    public static function productOnlyCartKey(int $productId): int
    {
        return -$productId;
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
        ]);

        $productId = (int) $request->product_id;
        $variantId = $request->variant_id ? (int) $request->variant_id : null;

        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)->where('product_id', $productId)->first();
            if (!$variant) {
                return back()->with('error', 'Invalid variant.');
            }
            $cartKey = $variant->id;
        } else {
            $variant = ProductVariant::where('product_id', $productId)->first();
            if ($variant) {
                $cartKey = $variant->id;
            } else {
                $product = Product::find($productId);
                if (!$product || !$product->is_active) {
                    return back()->with('error', 'Product is not available.');
                }
                $cartKey = self::productOnlyCartKey($productId);
            }
        }

        $cart = session()->get('cart', []);
        $qty = (int) ($request->quantity ?? 1);
        $cart[$cartKey] = ($cart[$cartKey] ?? 0) + $qty;
        session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart');
    }

    public function remove(Request $request, int $id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        return back();
    }

    public function update(Request $request, int $id)
    {
        $qty = max(0, (int) $request->quantity);
        $cart = session()->get('cart', []);
        if ($qty === 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $qty;
        }
        session()->put('cart', $cart);

        return back();
    }

    public function clear()
    {
        session()->forget('cart');
        return back();
    }
}
