<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function dailySales(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $sales = Sale::whereDate('created_at', $date)->with('items')->get();
        $total = $sales->sum('total');
        $count = $sales->count();
        return view('admin.reports.daily', compact('sales', 'total', 'count', 'date'));
    }

    public function monthlySales(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $sales = Sale::whereYear('created_at', substr($month, 0, 4))
            ->whereMonth('created_at', substr($month, 5, 2))
            ->orderBy('created_at')
            ->get();
        $total = $sales->sum('total');
        $count = $sales->count();
        return view('admin.reports.monthly', compact('sales', 'total', 'count', 'month'));
    }

    public function topProducts(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));
        $top = SaleItem::whereHas('sale', function ($q) use ($from, $to) {
            $q->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to);
        })
            ->selectRaw('product_id, product_name, sum(quantity) as total_qty, sum(line_total) as total_revenue')
            ->groupBy('product_id', 'product_name')
            ->orderByDesc('total_qty')
            ->limit(20)
            ->get();
        return view('admin.reports.top-products', compact('top', 'from', 'to'));
    }

    public function lowStock()
    {
        $products = Product::where('is_active', true)
            ->whereColumn('stock_quantity', '<', 'minimum_stock_limit')
            ->where('minimum_stock_limit', '>', 0)
            ->orderBy('stock_quantity')
            ->get();
        return view('admin.reports.low-stock', compact('products'));
    }

    public function profit(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->format('Y-m-d'));
        $to = $request->input('to', now()->format('Y-m-d'));
        $sales = Sale::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->get();
        $totalRevenue = $sales->sum('total');
        $totalCost = 0;
        foreach ($sales as $sale) {
            foreach ($sale->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $totalCost += (float) ($product->base_price ?? $product->wholesale_price ?? 0) * $item->quantity;
                }
            }
        }
        $profit = $totalRevenue - $totalCost;
        return view('admin.reports.profit', compact('totalRevenue', 'totalCost', 'profit', 'from', 'to'));
    }
}
