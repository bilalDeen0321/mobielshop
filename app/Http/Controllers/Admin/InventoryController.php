<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $query = Product::where('is_active', true)->orderBy('name');
        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }
        $products = $query->paginate(20)->withQueryString();
        $lowStock = Product::where('is_active', true)
            ->whereColumn('stock_quantity', '<', 'minimum_stock_limit')
            ->where('minimum_stock_limit', '>', 0)
            ->count();
        return view('admin.inventory.index', compact('products', 'lowStock', 'q'));
    }

    public function history(Product $product)
    {
        $movements = StockMovement::where('product_id', $product->id)->with('admin')->latest()->paginate(20);
        return view('admin.inventory.history', compact('product', 'movements'));
    }

    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer'],
            'notes' => ['nullable', 'string'],
        ]);
        $qty = (int) $request->input('quantity');
        if ($qty > 0) {
            $product->increment('stock_quantity', $qty);
            $type = 'adjustment_in';
        } else {
            $product->decrement('stock_quantity', abs($qty));
            $type = 'adjustment_out';
        }
        StockMovement::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $qty,
            'notes' => $request->input('notes'),
            'admin_id' => auth()->guard('admin')->id(),
        ]);
        return redirect()->route('admin.inventory.history', $product)->with('success', 'Stock adjusted.');
    }
}
