@extends('admin.layout')

@section('title', $customer->name)

@section('content')
<h3 class="page-title"> Customer: {{ $customer->name }} </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.customers.index') }}">Customers</a><i class="fa fa-angle-right"></i></li>
        <li><span>{{ $customer->name }}</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title"><div class="caption">Details</div><div class="actions"><a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm blue">Edit</a></div></div>
            <div class="portlet-body">
                <p><strong>Name:</strong> {{ $customer->name }}</p>
                <p><strong>Phone:</strong> {{ $customer->phone ?? '—' }}</p>
                <p><strong>Email:</strong> {{ $customer->email ?? '—' }}</p>
                @if($customer->address)<p><strong>Address:</strong><br>{{ nl2br(e($customer->address)) }}</p>@endif
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title"><div class="caption">Purchase history ({{ $customer->sales->count() }} sales)</div></div>
            <div class="portlet-body">
                <table class="table table-bordered">
                    <thead><tr><th>Sale #</th><th>Date</th><th>Total</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($customer->sales as $sale)
                        <tr>
                            <td>{{ $sale->sale_number }}</td>
                            <td>{{ $sale->created_at->format('d M Y H:i') }}</td>
                            <td>£{{ number_format($sale->total, 2) }}</td>
                            <td><a href="{{ route('admin.sales.show', $sale) }}" class="btn btn-xs default">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="4">No sales yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
