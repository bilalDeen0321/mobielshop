@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<h3 class="page-title"> Dashboard
    <small>quick actions & key stats</small>
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

{{-- Primary POS shortcut --}}
<div class="row margin-top-10">
    <div class="col-md-12">
        <a href="{{ route('admin.pos.index') }}" class="btn btn-lg green-meadow btn-block" style="padding: 18px 24px; text-align: left; position: relative;">
            <span class="badge badge-danger" style="position:absolute; top:8px; right:16px; background:#e7505a;">POS</span>
            <i class="icon-handbag" style="font-size:24px; margin-right:12px;"></i>
            <span style="font-size:18px; font-weight:600;">Open Point of Sale</span>
            <br>
            <span style="opacity:0.9;">Start a new sale quickly for walk‑in customers.</span>
        </a>
    </div>
</div>

@if(isset($pendingWebsiteOrdersCount) && $pendingWebsiteOrdersCount > 0)
<div class="row margin-top-20">
    <div class="col-md-12">
        <div class="portlet light" style="border-left: 4px solid #f1c40f;">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="fa fa-bell font-yellow-gold"></i>
                    <span class="caption-subject bold uppercase">New Website Orders</span>
                </div>
                <div class="actions">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm yellow-gold">Open orders module</a>
                </div>
            </div>
            <div class="portlet-body">
                <p class="text-muted">
                    You have <strong>{{ $pendingWebsiteOrdersCount }}</strong> pending website order{{ $pendingWebsiteOrdersCount !== 1 ? 's' : '' }} waiting for review.
                </p>
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPendingWebsiteOrders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ optional($order->placed_at ?? $order->created_at)->format('d M Y H:i') }}</td>
                                <td>{{ $currency }}{{ number_format($order->total, 2) }}</td>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-xs blue">Review</a></td>
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

{{-- Summary cards --}}
<div class="row margin-top-20">
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
            <a class="more" href="{{ route('admin.products.index') }}"> Manage products
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat green">
            <div class="visual">
                <i class="fa fa-folder-open"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $categoryCount }}">0</span>
                </div>
                <div class="desc"> Categories </div>
            </div>
            <a class="more" href="{{ route('admin.categories.index') }}"> Manage categories
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat purple">
            <div class="visual">
                <i class="fa fa-tags"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $brandCount }}">0</span>
                </div>
                <div class="desc"> Brands </div>
            </div>
            <a class="more" href="{{ route('admin.brands.index') }}"> Manage brands
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat yellow">
            <div class="visual">
                <i class="fa fa-users"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $userCount }}">0</span>
                </div>
                <div class="desc"> Users </div>
            </div>
            <a class="more" href="javascript:;"> Website users
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>

{{-- Sales / activity summary --}}
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat grey-mint">
            <div class="visual">
                <i class="icon-docs"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $totalSalesCount }}">0</span>
                </div>
                <div class="desc"> Total sales (POS) </div>
            </div>
            <a class="more" href="{{ route('admin.sales.index') }}"> View sales history
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat green-jungle">
            <div class="visual">
                <i class="icon-calendar"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value="{{ $todaySalesCount }}">0</span>
                </div>
                <div class="desc"> Sales today </div>
            </div>
            <a class="more" href="{{ route('admin.reports.daily', ['date' => now()->toDateString()]) }}"> Today's report
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="dashboard-stat red-intense">
            <div class="visual">
                <i class="icon-wallet"></i>
            </div>
            <div class="details">
                <div class="number">
                    {{ $currency }}{{ number_format($todaySalesTotal, 2) }}
                </div>
                <div class="desc"> Revenue today </div>
            </div>
            <a class="more" href="{{ route('admin.reports.daily', ['date' => now()->toDateString()]) }}"> Daily sales report
                <i class="m-icon-swapright m-icon-white"></i>
            </a>
        </div>
    </div>
</div>

<div class="clearfix"></div>

{{-- Low stock alert --}}
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
                    <a href="{{ route('admin.reports.low-stock') }}" class="btn btn-sm red">Low stock report</a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm default">View all products</a>
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
