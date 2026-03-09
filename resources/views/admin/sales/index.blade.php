@extends('admin.layout')

@section('title', 'Sales')

@section('content')
<h3 class="page-title"> Sales
    <small>transaction history</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>Sales</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-docs font-dark"></i>
                    <span class="caption-subject bold uppercase">Sales</span>
                </div>
                <div class="actions">
                    <a href="{{ route('admin.pos.index') }}" class="btn btn-sm green">New sale (POS)</a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Sale #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th>By</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                            <tr>
                                <td>{{ $sale->sale_number }}</td>
                                <td>{{ $sale->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $sale->customer?->name ?? $sale->customer_name ?: '—' }}</td>
                                <td>{{ ucfirst($sale->payment_method) }}</td>
                                <td>{{ $currency }}{{ number_format($sale->total, 2) }}</td>
                                <td>{{ $sale->admin->name ?? '—' }}</td>
                                <td><a href="{{ route('admin.sales.show', $sale) }}" class="btn btn-xs blue">View / Receipt</a></td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted">No sales yet. <a href="{{ route('admin.pos.index') }}">Create a sale</a>.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $sales->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
