<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);

        $cart = session()->get('cart', []);
        $id = (int) $request->product_id;
        $qty = (int) ($request->quantity ?? 1);
        $cart[$id] = ($cart[$id] ?? 0) + $qty;
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
