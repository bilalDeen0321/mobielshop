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
