@extends('admin.layout')

@section('title', 'Inventory / Stock')

@section('content')
<h3 class="page-title"> Inventory <small>stock levels and low stock alert</small></h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><span>Inventory</span></li>
    </ul>
</div>
@if($lowStock > 0)
<div class="alert alert-warning"><strong>{{ $lowStock }}</strong> product(s) below minimum stock. <a href="{{ route('admin.reports.low-stock') }}">View low stock report</a></div>
@endif
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark"><i class="icon-layers font-dark"></i><span class="caption-subject bold uppercase">Stock by product</span></div>
            </div>
            <div class="portlet-body">
                <form method="GET" class="form-inline margin-bottom-15">
                    <input type="text" name="q" class="form-control" placeholder="Search product" value="{{ $q }}">
                    <button type="submit" class="btn blue">Search</button>
                </form>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr><th>Product</th><th>Stock qty</th><th>Min limit</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                        <tr class="{{ $p->isLowStock() ? 'danger' : '' }}">
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->stock_quantity }}</td>
                            <td>{{ $p->minimum_stock_limit ?: '-' }}</td>
                            <td>@if($p->isLowStock())<span class="label label-danger">Low stock</span>@else<span class="label label-success">OK</span>@endif</td>
                            <td><a href="{{ route('admin.inventory.history', $p) }}" class="btn btn-xs blue">History</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No products.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
