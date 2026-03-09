<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();
        $startOfWeek = now()->subDays(6)->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();

        // Summary stats
        $todayStats = Sale::whereDate('created_at', $today)
            ->selectRaw('COALESCE(SUM(total), 0) as total, COUNT(*) as count')
            ->first();
        $weekStats = Sale::whereDate('created_at', '>=', $startOfWeek)
            ->selectRaw('COALESCE(SUM(total), 0) as total, COUNT(*) as count')
            ->first();
        $monthStats = Sale::whereDate('created_at', '>=', $startOfMonth)
            ->selectRaw('COALESCE(SUM(total), 0) as total, COUNT(*) as count')
            ->first();

        // Last 7 days: daily totals for chart
        $dailySales = Sale::whereDate('created_at', '>=', $startOfWeek)
            ->whereDate('created_at', '<=', $today)
            ->selectRaw('DATE(created_at) as date, COALESCE(SUM(total), 0) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $last7DaysLabels = [];
        $last7DaysTotals = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->toDateString();
            $last7DaysLabels[] = now()->subDays($i)->format('D j M');
            $last7DaysTotals[] = (float) ($dailySales->get($d)->total ?? 0);
        }

        // Last 6 months: monthly totals for chart
        $monthlySales = [];
        for ($i = 5; $i >= 0; $i--) {
            $dt = now()->subMonths($i);
            $monthStart = $dt->copy()->startOfMonth()->toDateString();
            $monthEnd = $dt->copy()->endOfMonth()->toDateString();
            $sum = Sale::whereDate('created_at', '>=', $monthStart)
                ->whereDate('created_at', '<=', $monthEnd)
                ->sum('total');
            $monthlySales['labels'][] = $dt->format('M Y');
            $monthlySales['totals'][] = (float) $sum;
        }

        // Payment method breakdown (last 30 days)
        $paymentBreakdown = Sale::whereDate('created_at', '>=', now()->subDays(30)->toDateString())
            ->selectRaw('payment_method, COALESCE(SUM(total), 0) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->orderByDesc(DB::raw('SUM(total)'))
            ->get();
        $paymentChartLabels = $paymentBreakdown->map(fn ($r) => ucfirst($r->payment_method ?? 'other'))->values()->all();
        $paymentChartTotals = $paymentBreakdown->pluck('total')->map(fn ($v) => (float) $v)->values()->all();

        return view('admin.reports.index', [
            'todayTotal' => (float) ($todayStats->total ?? 0),
            'todayCount' => (int) ($todayStats->count ?? 0),
            'weekTotal' => (float) ($weekStats->total ?? 0),
            'weekCount' => (int) ($weekStats->count ?? 0),
            'monthTotal' => (float) ($monthStats->total ?? 0),
            'monthCount' => (int) ($monthStats->count ?? 0),
            'chart7DaysLabels' => $last7DaysLabels,
            'chart7DaysTotals' => $last7DaysTotals,
            'chartMonthlyLabels' => $monthlySales['labels'] ?? [],
            'chartMonthlyTotals' => $monthlySales['totals'] ?? [],
            'paymentChartLabels' => $paymentChartLabels,
            'paymentChartTotals' => $paymentChartTotals,
        ]);
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
