<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $categoryCount = Category::count();
        $brandCount = Brand::count();
        $userCount = User::count();
        $totalSalesCount = Sale::count();
        $today = now()->toDateString();
        $todaySalesCount = Sale::whereDate('created_at', $today)->count();
        $todaySalesTotal = Sale::whereDate('created_at', $today)->sum('total');
        $pendingWebsiteOrdersCount = Order::where('status', 'pending')->count();
        $recentPendingWebsiteOrders = Order::where('status', 'pending')
            ->latest('placed_at')
            ->latest()
            ->limit(5)
            ->get();

        $lowStockProducts = Product::whereColumn('stock_quantity', '<', 'minimum_stock_limit')
            ->where('minimum_stock_limit', '>', 0)
            ->orderBy('stock_quantity')
            ->limit(15)
            ->get();

        return view('admin.dashboard', compact(
            'productCount',
            'categoryCount',
            'brandCount',
            'userCount',
            'totalSalesCount',
            'todaySalesCount',
            'todaySalesTotal',
            'pendingWebsiteOrdersCount',
            'recentPendingWebsiteOrders',
            'lowStockProducts'
        ));
    }
}
