@extends('admin.layout')

@section('title', 'Return ' . $returnModel->return_number)

@section('content')
<h3 class="page-title"> Return {{ $returnModel->return_number }}</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.returns.index') }}">Returns</a><i class="fa fa-angle-right"></i></li>
        <li><span>{{ $returnModel->return_number }}</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <p><strong>Sale:</strong> <a href="{{ route('admin.sales.show', $returnModel->sale) }}">{{ $returnModel->sale->sale_number }}</a>
                    | <strong>Date:</strong> {{ $returnModel->created_at->format('d M Y H:i') }}
                    @if($returnModel->admin) | <strong>By:</strong> {{ $returnModel->admin->name }} @endif
                </p>
                @if($returnModel->reason)<p><strong>Reason:</strong> {{ $returnModel->reason }}</p>@endif
                <table class="table table-bordered">
                    <thead><tr><th>Product</th><th>Qty returned</th><th>Refund amount</th></tr></thead>
                    <tbody>
                        @foreach($returnModel->items as $item)
                        <tr>
                            <td>{{ $item->product->name ?? $item->product_id }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>£{{ number_format($item->refund_amount, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-right"><strong>Total refund: £{{ number_format($returnModel->total_refund, 2) }}</strong></p>
                <a href="{{ route('admin.returns.index') }}" class="btn default">Back to returns</a>
            </div>
        </div>
    </div>
</div>
@endsection
