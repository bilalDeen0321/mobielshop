@extends('admin.layout')

@section('title', $purchase->purchase_number)

@section('content')
<h3 class="page-title"> Purchase {{ $purchase->purchase_number }}</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.purchases.index') }}">Purchases</a><i class="fa fa-angle-right"></i></li>
        <li><span>{{ $purchase->purchase_number }}</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <p><strong>Date:</strong> {{ $purchase->created_at->format('d M Y H:i') }}
                    @if($purchase->supplier) | <strong>Supplier:</strong> {{ $purchase->supplier->name }} @endif
                    @if($purchase->admin) | <strong>By:</strong> {{ $purchase->admin->name }} @endif
                </p>
                @if($purchase->notes)<p><strong>Notes:</strong> {{ $purchase->notes }}</p>@endif
                <table class="table table-bordered">
                    <thead><tr><th>Product</th><th>Qty</th><th>Unit cost</th><th>Line total</th></tr></thead>
                    <tbody>
                        @foreach($purchase->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? $item->product_id }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>£{{ number_format($item->unit_cost, 2) }}</td>
                            <td>£{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-right"><strong>Total: £{{ number_format($purchase->total, 2) }}</strong></p>
                <a href="{{ route('admin.purchases.index') }}" class="btn default">Back to purchases</a>
            </div>
        </div>
    </div>
</div>
@endsection
