@extends('admin.layout')

@section('title', 'Orders')

@section('content')
<h3 class="page-title"> Orders
    <small>website order submissions</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>Orders</span></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-basket-loaded font-dark"></i>
                    <span class="caption-subject bold uppercase">Website Orders</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ optional($order->placed_at ?? $order->created_at)->format('d M Y H:i') }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>
                                        <div>{{ $order->customer_email }}</div>
                                        @if($order->customer_phone)
                                            <small class="text-muted">{{ $order->customer_phone }}</small>
                                        @endif
                                    </td>
                                    <td>{{ ucfirst($order->payment_method) }}</td>
                                    <td>
                                        <span class="label label-sm
                                            @if($order->status === 'completed') label-success
                                            @elseif($order->status === 'processing') label-info
                                            @elseif($order->status === 'rejected') label-danger
                                            @else label-warning @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $currency }}{{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-xs blue">View</a>
                                        @if($order->status === 'pending')
                                            <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="btn btn-xs green">Process</button>
                                            </form>
                                            <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Reject this order?');">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-xs red">Reject</button>
                                            </form>
                                        @elseif($order->status === 'processing')
                                            <form action="{{ route('admin.orders.update', $order) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-xs green">Complete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No website orders have been submitted yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
