<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sale;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sale::with('admin')->latest()->paginate(20);
        return view('admin.sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items', 'admin']);
        return view('admin.sales.show', compact('sale'));
    }
}
