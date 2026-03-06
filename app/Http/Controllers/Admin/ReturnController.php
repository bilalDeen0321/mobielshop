<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ReturnModel;
use App\Models\ReturnItem;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnModel::with('sale', 'admin')->latest()->paginate(15);
        return view('admin.returns.index', compact('returns'));
    }

    public function create(Request $request)
    {
        $saleId = $request->input('sale_id');
        $sale = $saleId ? Sale::with('items.product')->find($saleId) : null;
        if (!$sale) {
            return redirect()->route('admin.returns.index')->with('error', 'Sale not found.');
        }
        return view('admin.returns.create', compact('sale'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sale_id' => ['required', 'exists:sales,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.refund_amount' => ['required', 'numeric', 'min:0'],
            'reason' => ['nullable', 'string'],
        ]);

        $sale = Sale::with('items')->findOrFail($request->input('sale_id'));
        $items = array_filter($request->input('items', []), function ($item) {
            return !empty($item['quantity']) && (int) $item['quantity'] > 0;
        });
        if (empty($items)) {
            return redirect()->back()->withInput()->with('error', 'Select at least one item with quantity > 0.');
        }
        $totalRefund = 0;
        foreach ($items as $item) {
            $totalRefund += (float) ($item['refund_amount'] ?? 0) * (int) $item['quantity'];
        }

        DB::beginTransaction();
        try {
            $return = ReturnModel::create([
                'return_number' => ReturnModel::generateReturnNumber(),
                'sale_id' => $sale->id,
                'total_refund' => $totalRefund,
                'reason' => $request->input('reason'),
                'admin_id' => auth()->guard('admin')->id(),
            ]);

            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                $refundAmount = (float) $item['refund_amount'] * $qty;
                ReturnItem::create([
                    'return_id' => $return->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'refund_amount' => $refundAmount,
                ]);
                $product->increment('stock_quantity', $qty);
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'return',
                    'quantity' => $qty,
                    'reference_type' => 'Return',
                    'reference_id' => $return->id,
                    'notes' => $request->input('reason'),
                    'admin_id' => auth()->guard('admin')->id(),
                ]);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Return failed: ' . $e->getMessage());
        }

        return redirect()->route('admin.returns.show', $return)->with('success', 'Return processed. Stock restored.');
    }

    public function show(ReturnModel $returnModel)
    {
        $returnModel->load(['items.product', 'sale.items', 'admin']);
        return view('admin.returns.show', compact('returnModel'));
    }
}
