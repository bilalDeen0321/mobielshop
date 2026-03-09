@extends('admin.layout')

@section('title', 'Create return - Sale ' . $sale->sale_number)

@section('content')
<h3 class="page-title"> Create return for sale {{ $sale->sale_number }}</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.returns.index') }}">Returns</a><i class="fa fa-angle-right"></i></li>
        <li><span>New return</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body">
                <form action="{{ route('admin.returns.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                    <div class="form-group">
                        <label>Reason (optional)</label>
                        <textarea name="reason" class="form-control" rows="2" placeholder="e.g. Customer return, defective"></textarea>
                    </div>
                    <p class="bold">Select items to return (stock will be restored, refund amount will be recorded):</p>
                    <table class="table table-bordered">
                        <thead><tr><th>Return</th><th>Product</th><th>Sold qty</th><th>Unit price</th><th>Return qty</th><th>Refund per unit</th></tr></thead>
                        <tbody>
                            @foreach($sale->items as $idx => $item)
                            <tr>
                                <td><input type="checkbox" name="items[{{ $idx }}][include]" value="1" class="include-item"></td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $currency }}{{ number_format($item->unit_price, 2) }}</td>
                                <td>
                                    <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item->product_id }}">
                                    <input type="number" name="items[{{ $idx }}][quantity]" class="form-control return-qty" min="0" max="{{ $item->quantity }}" value="0" style="width:80px;">
                                </td>
                                <td><input type="number" step="0.01" name="items[{{ $idx }}][refund_amount]" class="form-control" value="{{ $item->unit_price }}" style="width:90px;"></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="text-muted">Set return qty to 0 to skip an item. Refund amount is per unit.</p>
                    <button type="submit" class="btn green">Process return & restore stock</button>
                    <a href="{{ route('admin.sales.show', $sale) }}" class="btn default">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
