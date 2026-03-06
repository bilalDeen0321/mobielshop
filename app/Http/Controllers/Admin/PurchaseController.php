<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('supplier', 'admin')->latest()->paginate(15);
        return view('admin.purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get(['id', 'name', 'stock_quantity']);
        return view('admin.purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $items = $request->input('items');
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += (float) $item['unit_cost'] * (int) $item['quantity'];
        }

        DB::beginTransaction();
        try {
            $purchase = Purchase::create([
                'purchase_number' => Purchase::generatePurchaseNumber(),
                'supplier_id' => $request->input('supplier_id'),
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'notes' => $request->input('notes'),
                'admin_id' => auth()->guard('admin')->id(),
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                $unitCost = (float) $item['unit_cost'];
                $lineTotal = round($unitCost * $qty, 2);
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'line_total' => $lineTotal,
                ]);
                $product->increment('stock_quantity', $qty);
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'purchase',
                    'quantity' => $qty,
                    'unit_cost' => $unitCost,
                    'reference_type' => 'Purchase',
                    'reference_id' => $purchase->id,
                    'admin_id' => auth()->guard('admin')->id(),
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Purchase failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.purchases.show', $purchase)->with('success', 'Purchase recorded. Stock updated.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['items.product', 'supplier', 'admin']);
        return view('admin.purchases.show', compact('purchase'));
    }
}
