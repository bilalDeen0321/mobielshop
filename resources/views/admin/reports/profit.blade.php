@extends('admin.layout')

@section('title', 'Profit report')

@section('content')
<h3 class="page-title"> Profit report </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.reports.index') }}">Reports</a><i class="fa fa-angle-right"></i></li>
        <li><span>Profit</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <form method="GET" class="form-inline margin-bottom-15">
                    <label>From:</label>
                    <input type="date" name="from" class="form-control" value="{{ $from }}">
                    <label>To:</label>
                    <input type="date" name="to" class="form-control" value="{{ $to }}">
                    <button type="submit" class="btn blue">Show</button>
                </form>
                <p>Cost is estimated using product base/wholesale price per item sold.</p>
                <table class="table table-bordered" style="max-width:400px;">
                    <tr><th>Total revenue</th><td>{{ $currency }}{{ number_format($totalRevenue, 2) }}</td></tr>
                    <tr><th>Estimated cost</th><td>{{ $currency }}{{ number_format($totalCost, 2) }}</td></tr>
                    <tr><th>Profit</th><td class="bold">{{ $currency }}{{ number_format($profit, 2) }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
