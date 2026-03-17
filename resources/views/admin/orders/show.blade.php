@extends('admin.layout')

@section('title', 'Order ' . $order->order_number)

@section('content')
<h3 class="page-title"> Order {{ $order->order_number }}
    <small>website checkout details</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><a href="{{ route('admin.orders.index') }}">Orders</a><i class="fa fa-angle-right"></i></li>
        <li><span>{{ $order->order_number }}</span></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-basket-loaded font-dark"></i>
                    <span class="caption-subject bold uppercase">Order Items</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Selected options</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td><strong>{{ $item->product_name }}</strong></td>
                                <td>
                                    @if(!empty(trim($item->selected_options ?? '')))
                                        @php
                                            $opts = array_map('trim', explode(',', $item->selected_options));
                                        @endphp
                                        <ul class="list-unstyled mb-0 text-muted" style="font-size: 0.9em;">
                                            @foreach($opts as $opt)
                                                @if($opt)
                                                    <li>{{ $opt }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @elseif(!empty(trim($item->variant_name ?? '')))
                                        {{ $item->variant_name }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $currency }}{{ number_format($item->unit_price, 2) }}</td>
                                <td>{{ $currency }}{{ number_format($item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-right">
                    <p>Subtotal: {{ $currency }}{{ number_format($order->subtotal, 2) }}</p>
                    @if($order->tax_amount > 0)
                        <p>Tax: {{ $currency }}{{ number_format($order->tax_amount, 2) }}</p>
                    @endif
                    @if($order->shipping_cost > 0)
                        <p>Shipping: {{ $currency }}{{ number_format($order->shipping_cost, 2) }}</p>
                    @endif
                    <p class="bold font-lg">Total: {{ $currency }}{{ number_format($order->total, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-user font-dark"></i>
                    <span class="caption-subject bold uppercase">Customer</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="margin-bottom-20">
                    <p><strong>Order actions</strong></p>
                    <div class="btn-group btn-group-sm">
                        @if($order->status !== 'processing')
                            <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="processing">
                                <button type="submit" class="btn blue">Process</button>
                            </form>
                        @endif
                        @if($order->status !== 'completed')
                            <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn green">Complete</button>
                            </form>
                        @endif
                        @if($order->status !== 'rejected')
                            <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Reject this order?');">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn red">Reject</button>
                            </form>
                        @endif
                    </div>
                </div>

                <p><strong>Customer details</strong></p>
                <table class="table table-condensed margin-bottom-15" style="background: transparent;">
                    <tr><td style="border: none; padding: 4px 8px 4px 0;"><strong>Name</strong></td><td style="border: none; padding: 4px 0;">{{ $order->customer_name }}</td></tr>
                    <tr><td style="border: none; padding: 4px 8px 4px 0;"><strong>Email</strong></td><td style="border: none; padding: 4px 0;">{{ $order->customer_email }}</td></tr>
                    <tr><td style="border: none; padding: 4px 8px 4px 0;"><strong>Phone</strong></td><td style="border: none; padding: 4px 0;">{{ $order->customer_phone ?: '—' }}</td></tr>
                    <tr><td style="border: none; padding: 4px 8px 4px 0;"><strong>Order date</strong></td><td style="border: none; padding: 4px 0;">{{ optional($order->placed_at ?? $order->created_at)->format('d M Y, H:i') }}</td></tr>
                    <tr><td style="border: none; padding: 4px 8px 4px 0;"><strong>Status</strong></td><td style="border: none; padding: 4px 0;">{{ ucfirst($order->status) }}</td></tr>
                    <tr><td style="border: none; padding: 4px 8px 4px 0;"><strong>Payment</strong></td><td style="border: none; padding: 4px 0;">{{ ucfirst($order->payment_method) }} / {{ ucfirst($order->payment_status) }}</td></tr>
                    @if($order->processed_at)
                        <tr><td style="border: none; padding: 4px 8px 4px 0;"><strong>Processed at</strong></td><td style="border: none; padding: 4px 0;">{{ $order->processed_at->format('d M Y, H:i') }}</td></tr>
                    @endif
                </table>

                <p><strong>Postal / shipping address</strong></p>
                <p class="text-muted" style="white-space: pre-line; margin-bottom: 15px;">{{ $order->shipping_address }}</p>

                <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="margin-bottom-15">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label><strong>Tracking number</strong></label>
                        <input type="text" name="tracking_number" class="form-control" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Enter tracking number">
                    </div>
                    <button type="submit" class="btn btn-sm blue">Save tracking</button>
                </form>
                <hr>
                <p><strong>Card details</strong></p>
                <p><strong>Name on card:</strong> {{ $order->card_name ?: '—' }}</p>
                <p><strong>Card:</strong> @if($order->card_last_four) **** **** **** {{ $order->card_last_four }} @else — @endif</p>
                <p><strong>Expiry:</strong> @if($order->card_expiry_month && $order->card_expiry_year) {{ $order->card_expiry_month }}/{{ $order->card_expiry_year }} @else — @endif</p>
                @if($order->tracking_number)
                    <hr>
                    <p><strong>Tracking #:</strong> {{ $order->tracking_number }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
