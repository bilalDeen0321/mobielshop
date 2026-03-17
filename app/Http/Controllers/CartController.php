<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
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
            'color' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'condition' => 'nullable|string|max:255',
        ]);

        $productId = (int) $request->product_id;
        $variantId = $request->variant_id ? (int) $request->variant_id : null;
        $qty = (int) ($request->quantity ?? 1);
        $sessionId = $request->session()->getId();

        $selectedOptionsJson = null;
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)->where('product_id', $productId)->first();
            if (!$variant) {
                return back()->with('error', 'Invalid variant.');
            }
            $cartKey = $variant->id;

            $selectedOptions = array_filter([
                'color' => $request->filled('color') ? trim($request->color) : null,
                'storage' => $request->filled('storage') ? trim($request->storage) : null,
                'size' => $request->filled('size') ? trim($request->size) : null,
                'condition' => $request->filled('condition') ? trim($request->condition) : null,
            ]);
            $selectedOptionsJson = $selectedOptions !== [] ? json_encode($selectedOptions) : null;
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

        $item = CartItem::firstOrNew([
            'session_id' => $sessionId,
            'cart_key' => $cartKey,
        ]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + $qty;
        if ($selectedOptionsJson !== null) {
            $item->selected_options = $selectedOptionsJson;
        }
        $item->save();

        $cart = $request->session()->get('cart', []);
        $cart[$cartKey] = ($cart[$cartKey] ?? 0) + $qty;
        $request->session()->put('cart', $cart);
        if ($selectedOptionsJson !== null) {
            $cartOptions = $request->session()->get('cart_options', []);
            $cartOptions[$cartKey] = json_decode($selectedOptionsJson, true);
            $request->session()->put('cart_options', $cartOptions);
        }

        return back()->with('success', 'Product added to cart');
    }

    public function remove(Request $request, int $id)
    {
        $sessionId = $request->session()->getId();
        CartItem::where('session_id', $sessionId)->where('cart_key', $id)->delete();

        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);

        $cartOptions = session()->get('cart_options', []);
        unset($cartOptions[$id]);
        session()->put('cart_options', $cartOptions);

        return back();
    }

    public function update(Request $request, int $id)
    {
        $qty = max(0, (int) $request->quantity);
        $sessionId = $request->session()->getId();

        $item = CartItem::where('session_id', $sessionId)->where('cart_key', $id)->first();
        if ($item) {
            if ($qty === 0) {
                $item->delete();
            } else {
                $item->update(['quantity' => $qty]);
            }
        }

        $cart = session()->get('cart', []);
        if ($qty === 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $qty;
        }
        session()->put('cart', $cart);

        return back();
    }

    public function clear(Request $request)
    {
        CartItem::where('session_id', $request->session()->getId())->delete();
        session()->forget('cart');
        session()->forget('cart_options');
        return back();
    }
}
