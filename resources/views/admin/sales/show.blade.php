@extends('admin.layout')

@section('title', 'Sale ' . $sale->sale_number)

@section('content')
<h3 class="page-title"> Sale {{ $sale->sale_number }}
    <small>receipt / invoice</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><a href="{{ route('admin.sales.index') }}">Sales</a><i class="fa fa-angle-right"></i></li>
        <li><span>{{ $sale->sale_number }}</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="portlet light" id="sale-receipt">
            <div class="portlet-body">
                <h4 class="bold">Sale # {{ $sale->sale_number }}</h4>
                <p class="text-muted">{{ $sale->created_at->format('d M Y, H:i') }} @if($sale->admin) &mdash; {{ $sale->admin->name }} @endif</p>
                @if($sale->customer_name || $sale->customer_phone || $sale->customer_email)
                <p>
                    @if($sale->customer_name) <strong>{{ $sale->customer_name }}</strong><br> @endif
                    @if($sale->customer_phone) {{ $sale->customer_phone }}<br> @endif
                    @if($sale->customer_email) {{ $sale->customer_email }} @endif
                </p>
                @endif
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $currency }}{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $currency }}{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-right">
                    <p>Subtotal: {{ $currency }}{{ number_format($sale->subtotal, 2) }}</p>
                    @if($sale->tax_amount > 0)
                    <p>Tax ({{ number_format($sale->tax_rate, 1) }}%): {{ $currency }}{{ number_format($sale->tax_amount, 2) }}</p>
                    @endif
                    @if($sale->discount_amount > 0)
                    <p>Discount: -{{ $currency }}{{ number_format($sale->discount_amount, 2) }}</p>
                    @endif
                    <p class="bold font-lg">Total: {{ $currency }}{{ number_format($sale->total, 2) }}</p>
                    <p class="text-muted">Payment: {{ ucfirst($sale->payment_method) }}</p>
                </div>
                @if($sale->notes)
                <p class="margin-top-10"><strong>Notes:</strong> {{ $sale->notes }}</p>
                @endif
                <hr>
                <a href="{{ route('admin.sales.index') }}" class="btn default">Back to sales</a>
                <button type="button" class="btn blue" onclick="window.print();"><i class="fa fa-print"></i> Print receipt</button>
                <a href="{{ route('admin.returns.create', ['sale_id' => $sale->id]) }}" class="btn yellow"><i class="fa fa-undo"></i> Refund / Return</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@media print {
    .page-header, .page-sidebar, .page-footer, .page-bar, .portlet-title .actions, .btn { display: none !important; }
    #sale-receipt { border: none !important; }
}
@endpush
