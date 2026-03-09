@extends('admin.layout')

@section('title', 'Monthly sales')

@section('content')
<h3 class="page-title"> Monthly sales report </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.reports.index') }}">Reports</a><i class="fa fa-angle-right"></i></li>
        <li><span>Monthly</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <form method="GET" class="form-inline margin-bottom-15">
                    <label>Month:</label>
                    <input type="month" name="month" class="form-control" value="{{ $month }}">
                    <button type="submit" class="btn blue">Show</button>
                </form>
                <p><strong>Total sales: {{ $count }}</strong> | <strong>Total amount: {{ $currency }}{{ number_format($total, 2) }}</strong></p>
                <table class="table table-bordered">
                    <thead><tr><th>Sale #</th><th>Date</th><th>Customer</th><th>Total</th><th></th></tr></thead>
                    <tbody>
                        @forelse($sales as $s)
                        <tr>
                            <td>{{ $s->sale_number }}</td>
                            <td>{{ $s->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $s->customer_name ?: '-' }}</td>
                            <td>{{ $currency }}{{ number_format($s->total, 2) }}</td>
                            <td><a href="{{ route('admin.sales.show', $s) }}" class="btn btn-xs default">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5">No sales for this month.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
