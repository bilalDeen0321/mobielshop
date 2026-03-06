@extends('admin.layout')

@section('title', 'Low stock report')

@section('content')
<h3 class="page-title"> Low stock report </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.reports.index') }}">Reports</a><i class="fa fa-angle-right"></i></li>
        <li><span>Low stock</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <p>Products with stock below minimum limit.</p>
                <table class="table table-bordered">
                    <thead><tr><th>Product</th><th>Current stock</th><th>Min limit</th><th>Shortfall</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($products as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->stock_quantity }}</td>
                            <td>{{ $p->minimum_stock_limit }}</td>
                            <td>{{ $p->minimum_stock_limit - $p->stock_quantity }}</td>
                            <td><a href="{{ route('admin.inventory.history', $p) }}" class="btn btn-xs blue">Stock history</a> <a href="{{ route('admin.purchases.create') }}" class="btn btn-xs green">New purchase</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5">No low stock products.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
