<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::where('is_active', true)
            ->where('stock_quantity', '>', 0)
            ->with([
                'category',
                'images' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])
            ->orderBy('name')
            ->limit(60)
            ->get();

        return view('admin.pos.index', compact('categories', 'products'));
    }

    public function searchProducts(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $categoryId = $request->input('category_id');
        $inStockOnly = (bool) $request->input('in_stock', false);

        $products = Product::where('is_active', true)
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($inStockOnly, function ($query) {
                $query->where('stock_quantity', '>', 0);
            })
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', '%' . $q . '%')
                        ->orWhere('brand', 'like', '%' . $q . '%')
                        ->orWhere('slug', 'like', '%' . $q . '%')
                        ->orWhere('id', $q)
                        ->orWhereHas('category', function ($catQuery) use ($q) {
                            $catQuery->where('name', 'like', '%' . $q . '%');
                        });
                });
            })
            ->with([
                'category',
                'images' => function ($query) {
                    $query->orderBy('sort_order');
                },
            ])
            ->orderBy('name')
            ->limit(40)
            ->get()
            ->map(function ($p) {
                $image = $p->images->first();

                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'brand' => $p->brand,
                    'retail_price' => (float) $p->retail_price,
                    'stock_quantity' => (int) $p->stock_quantity,
                    'category' => $p->category?->name,
                    'sku' => (string) $p->id,
                    'image_url' => $image?->url,
                ];
            });

        return response()->json($products);
    }

    public function completeSale(Request $request)
    {
        $request->merge(json_decode($request->getContent(), true) ?? []);

        $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'string', 'in:cash,card,transfer,other'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:50'],
            'customer_email' => ['nullable', 'email'],
            'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $items = $request->input('items');
        $productIds = array_column($items, 'product_id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($items as $item) {
            $product = $products->get($item['product_id']);
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Invalid product in cart.'], 422);
            }
            $qty = (int) $item['quantity'];
            if ($product->stock_quantity < $qty) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for \"{$product->name}\". Available: {$product->stock_quantity}.",
                ], 422);
            }
        }

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float) $item['unit_price'] * (int) $item['quantity'];
        }
        $taxRate = (float) ($request->input('tax_rate') ?? 0);
        $taxAmount = round($subtotal * ($taxRate / 100), 2);
        $discountAmount = (float) ($request->input('discount_amount') ?? 0);
        $total = round($subtotal + $taxAmount - $discountAmount, 2);

        DB::beginTransaction();
        try {
            $sale = new Sale();
            $sale->sale_number = Sale::generateSaleNumber();
            $sale->customer_id = $request->input('customer_id');
            $customer = $sale->customer_id ? \App\Models\Customer::find($sale->customer_id) : null;
            $sale->customer_name = $request->input('customer_name') ?: $customer?->name;
            $sale->customer_phone = $request->input('customer_phone') ?: $customer?->phone;
            $sale->customer_email = $request->input('customer_email') ?: $customer?->email;
            $sale->payment_method = $request->input('payment_method', 'cash');
            $sale->subtotal = $subtotal;
            $sale->tax_rate = $taxRate;
            $sale->tax_amount = $taxAmount;
            $sale->discount_amount = $discountAmount;
            $sale->total = $total;
            $sale->notes = $request->input('notes');
            $sale->admin_id = auth()->guard('admin')->id();
            $sale->save();

            foreach ($items as $item) {
                $product = $products->get($item['product_id']);
                $qty = (int) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $lineTotal = round($unitPrice * $qty, 2);
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'line_total' => $lineTotal,
                ]);
                $product->decrement('stock_quantity', $qty);
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity' => -$qty,
                    'unit_cost' => $unitPrice,
                    'reference_type' => 'Sale',
                    'reference_id' => $sale->id,
                    'admin_id' => auth()->guard('admin')->id(),
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Sale failed: ' . $e->getMessage()], 500);
        }

        $sale->load('items');
        return response()->json([
            'success' => true,
            'message' => 'Sale completed.',
            'sale' => [
                'id' => $sale->id,
                'sale_number' => $sale->sale_number,
                'total' => $sale->total,
                'items' => $sale->items,
            ],
        ]);
    }
}
