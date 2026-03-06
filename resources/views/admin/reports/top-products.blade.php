@extends('admin.layout')

@section('title', 'Top selling products')

@section('content')
<h3 class="page-title"> Top selling products </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.reports.index') }}">Reports</a><i class="fa fa-angle-right"></i></li>
        <li><span>Top products</span></li>
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
                <table class="table table-bordered">
                    <thead><tr><th>#</th><th>Product</th><th>Qty sold</th><th>Revenue</th></tr></thead>
                    <tbody>
                        @forelse($top as $i => $t)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $t->product_name }}</td>
                            <td>{{ $t->total_qty }}</td>
                            <td>£{{ number_format($t->total_revenue, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4">No data for this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
