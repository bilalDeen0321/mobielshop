<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount = Product::count();
        $categoryCount = Category::count();
        $lowStockProducts = Product::whereColumn('stock_quantity', '<', 'minimum_stock_limit')
            ->where('minimum_stock_limit', '>', 0)
            ->orderBy('stock_quantity')
            ->limit(15)
            ->get();

        return view('admin.dashboard', compact('productCount', 'categoryCount', 'lowStockProducts'));
    }
}
