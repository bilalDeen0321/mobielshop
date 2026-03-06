@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<h3 class="page-title"> Dashboard
    <small>overview & statistics</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>Dashboard</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $productCount }}">0</span>
                </div>
                <div class="desc"> Products </div>
            </div>
            <a class="more" href="{{ route('admin.products.index') }}"> View more
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat green">
            <div class="visual">
                <i class="fa fa-folder"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $categoryCount }}">0</span>
                </div>
                <div class="desc"> Categories </div>
            </div>
            <a class="more" href="{{ route('admin.categories.index') }}"> View more
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>
<div class="clearfix"></div>
@if(isset($lowStockProducts) && $lowStockProducts->isNotEmpty())
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light" style="border-left: 4px solid #e73d4a;">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="fa fa-exclamation-triangle font-red"></i>
                    <span class="caption-subject bold uppercase">Low stock alert</span>
                </div>
                <div class="actions">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm red">View all products</a>
                </div>
            </div>
            <div class="portlet-body">
                <p class="text-muted">The following products are below their minimum stock level. Consider restocking.</p>
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Current stock</th>
                                <th>Min. limit</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $p)
                            <tr>
                                <td>{{ $p->name }}</td>
                                <td>{{ $p->category->name ?? '-' }}</td>
                                <td><span class="label label-danger">{{ $p->stock_quantity }}</span></td>
                                <td>{{ $p->minimum_stock_limit }}</td>
                                <td><a href="{{ route('admin.products.edit', $p) }}" class="btn btn-xs blue">Edit stock</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="{{ asset('admin/theme/assets/global/plugins/counterup/jquery.waypoints.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('admin/theme/assets/global/plugins/counterup/jquery.counterup.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        if (typeof jQuery.counterUp === 'function') {
            jQuery('[data-counter="counterup"]').counterUp({ delay: 10, time: 1000 });
        }
    });
</script>
@endpush
