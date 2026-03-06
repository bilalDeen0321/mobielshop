<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index()
    {
        return view('admin.pos.index');
    }

    public function searchProducts(Request $request)
    {
        $q = $request->input('q', '');
        $products = Product::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                    ->orWhere('brand', 'like', '%' . $q . '%')
                    ->orWhere('slug', 'like', '%' . $q . '%');
            })
            ->with('category')
            ->orderBy('name')
            ->limit(30)
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'brand' => $p->brand,
                    'retail_price' => (float) $p->retail_price,
                    'stock_quantity' => (int) $p->stock_quantity,
                    'category' => $p->category?->name,
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
            $sale->customer_name = $request->input('customer_name');
            $sale->customer_phone = $request->input('customer_phone');
            $sale->customer_email = $request->input('customer_email');
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
