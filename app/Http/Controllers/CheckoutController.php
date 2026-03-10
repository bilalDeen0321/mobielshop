<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $cartItems = $this->getCartItems($request);
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $checkoutDefaults = [
            'customer_name' => '',
            'customer_email' => '',
            'customer_phone' => '',
            'shipping_address' => '',
            'shipping_city' => '',
            'shipping_postal_code' => '',
            'shipping_country' => 'United Kingdom',
            'card_name' => '',
            'card_expiry_month' => '',
            'card_expiry_year' => '',
        ];

        if (Auth::check()) {
            $user = Auth::user();
            $shipping = UserAddress::firstOrNew(['user_id' => $user->id, 'type' => 'shipping']);

            $checkoutDefaults = [
                'customer_name' => $shipping->full_name ?: $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $shipping->phone ?: ($user->phone ?? ''),
                'shipping_address' => $shipping->street ?? '',
                'shipping_city' => $shipping->city ?? '',
                'shipping_postal_code' => $shipping->postal_code ?? '',
                'shipping_country' => $shipping->country ?: 'United Kingdom',
                'card_name' => $shipping->full_name ?: $user->name,
                'card_expiry_month' => '',
                'card_expiry_year' => '',
            ];
        }

        return view('pages.checkout', compact('cartItems', 'checkoutDefaults'));
    }

    public function store(Request $request)
    {
        $cartItems = $this->getCartItems($request);
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'shipping_address' => ['required', 'string', 'max:255'],
            'shipping_city' => ['required', 'string', 'max:120'],
            'shipping_postal_code' => ['required', 'string', 'max:20'],
            'shipping_country' => ['required', 'string', 'max:120'],
            'card_name' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'regex:/^[0-9\s]{12,23}$/'],
            'card_expiry_month' => ['required', 'integer', 'between:1,12'],
            'card_expiry_year' => ['required', 'integer', 'min:' . now()->year, 'max:' . (now()->year + 20)],
            'card_cvv' => ['required', 'digits_between:3,4'],
        ], [
            'card_number.regex' => 'Please enter a valid card number.',
        ]);

        foreach ($cartItems as $item) {
            if ($item['quantity'] > $item['stock_available']) {
                return back()
                    ->withInput($request->except(['card_number', 'card_cvv']))
                    ->withErrors([
                        'checkout' => 'Insufficient stock for "' . $item['product']->name . '". Only ' . $item['stock_available'] . ' left.',
                    ]);
            }
        }

        $subtotal = (float) $cartItems->sum(fn ($item) => $item['line_total']);
        $taxAmount = 0.0;
        $shippingCost = 0.0;
        $total = $subtotal + $taxAmount + $shippingCost;
        $cardDigits = preg_replace('/\D/', '', $validated['card_number']);
        $shippingAddress = implode("\n", array_filter([
            $validated['shipping_address'],
            $validated['shipping_city'],
            $validated['shipping_postal_code'],
            $validated['shipping_country'],
        ]));

        $order = null;

        DB::transaction(function () use ($validated, $cartItems, $subtotal, $taxAmount, $shippingCost, $total, $cardDigits, $shippingAddress, $request, &$order) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $shippingAddress,
                'status' => 'pending',
                'payment_method' => 'card',
                'payment_status' => 'paid',
                'card_name' => $validated['card_name'],
                'card_last_four' => substr($cardDigits, -4),
                'card_expiry_month' => str_pad((string) $validated['card_expiry_month'], 2, '0', STR_PAD_LEFT),
                'card_expiry_year' => (string) $validated['card_expiry_year'],
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'placed_at' => now(),
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'variant_id' => $item['variant_id'],
                    'product_name' => $item['product']->name,
                    'variant_name' => $item['variant_name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total'],
                ]);
            }

            if (Auth::check()) {
                UserAddress::updateOrCreate(
                    ['user_id' => Auth::id(), 'type' => 'shipping'],
                    [
                        'full_name' => $validated['customer_name'],
                        'phone' => $validated['customer_phone'],
                        'street' => $validated['shipping_address'],
                        'city' => $validated['shipping_city'],
                        'postal_code' => $validated['shipping_postal_code'],
                        'country' => $validated['shipping_country'],
                    ]
                );
            }

            $request->session()->forget('cart');
        });

        if (Auth::check()) {
            return redirect()
                ->route('account.orders.show', $order)
                ->with('success', 'Your order ' . $order->order_number . ' was placed successfully.');
        }

        return redirect()->route('home')->with('success', 'Your order ' . $order->order_number . ' was placed successfully.');
    }

    private function getCartItems(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        $cartKeys = array_keys($cart);
        $variantIds = array_filter($cartKeys, fn ($key) => (int) $key > 0);
        $productOnlyIds = array_map(fn ($key) => -(int) $key, array_filter($cartKeys, fn ($key) => (int) $key < 0));

        $variants = $variantIds
            ? ProductVariant::with('product', 'inventory')->whereIn('id', $variantIds)->get()->keyBy('id')
            : collect();
        $products = $productOnlyIds
            ? Product::whereIn('id', $productOnlyIds)->get()->keyBy('id')
            : collect();

        return collect($cart)->map(function ($quantity, $key) use ($variants, $products) {
            $key = (int) $key;
            $quantity = (int) $quantity;

            if ($key > 0) {
                $variant = $variants->get($key);
                if (!$variant || !$variant->product || !$variant->product->is_active) {
                    return null;
                }

                return [
                    'product' => $variant->product,
                    'variant_id' => $variant->id,
                    'variant_name' => $variant->variant_name,
                    'quantity' => $quantity,
                    'unit_price' => (float) $variant->price,
                    'line_total' => (float) $variant->price * $quantity,
                    'stock_available' => (int) $variant->stock,
                ];
            }

            $product = $products->get(-$key);
            if (!$product || !$product->is_active) {
                return null;
            }

            $price = (float) ($product->sale_price ?? $product->retail_price ?? $product->base_price);

            return [
                'product' => $product,
                'variant_id' => null,
                'variant_name' => null,
                'quantity' => $quantity,
                'unit_price' => $price,
                'line_total' => $price * $quantity,
                'stock_available' => (int) $product->stock_quantity,
            ];
        })->filter()->values();
    }
}
