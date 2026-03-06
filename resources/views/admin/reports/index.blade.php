@extends('admin.layout')

@section('title', 'Reports')

@section('content')
<h3 class="page-title"> Reports </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><span>Reports</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title"><div class="caption font-dark"><i class="icon-graph font-dark"></i><span class="caption-subject bold uppercase">Reports</span></div></div>
            <div class="portlet-body">
                <ul class="list-unstyled">
                    <li class="margin-bottom-10"><a href="{{ route('admin.reports.daily') }}" class="btn blue">Daily sales report</a> &mdash; Sales for a specific date</li>
                    <li class="margin-bottom-10"><a href="{{ route('admin.reports.monthly') }}" class="btn blue">Monthly sales report</a> &mdash; Sales for a month</li>
                    <li class="margin-bottom-10"><a href="{{ route('admin.reports.top-products') }}" class="btn blue">Top selling products</a> &mdash; By quantity and revenue (date range)</li>
                    <li class="margin-bottom-10"><a href="{{ route('admin.reports.low-stock') }}" class="btn blue">Low stock report</a> &mdash; Products below minimum</li>
                    <li class="margin-bottom-10"><a href="{{ route('admin.reports.profit') }}" class="btn blue">Profit report</a> &mdash; Revenue vs cost (date range)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
