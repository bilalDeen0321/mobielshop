@extends('admin.layout')

@section('title', 'Stock history')

@section('content')
<h3 class="page-title"> Stock history: {{ $product->name }}</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.inventory.index') }}">Inventory</a><i class="fa fa-angle-right"></i></li>
        <li><span>{{ $product->name }}</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title"><div class="caption">Current stock</div></div>
            <div class="portlet-body">
                <p><strong>Quantity:</strong> {{ $product->stock_quantity }} | <strong>Min limit:</strong> {{ $product->minimum_stock_limit ?: '-' }}</p>
                <form action="{{ route('admin.inventory.adjust', $product) }}" method="POST" class="form-inline">
                    @csrf
                    <input type="number" name="quantity" class="form-control" placeholder="+/- qty" required style="width:100px;">
                    <input type="text" name="notes" class="form-control" placeholder="Notes" style="width:150px;">
                    <button type="submit" class="btn green">Adjust</button>
                </form>
                <span class="help-block">Use positive to add stock, negative to reduce.</span>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title"><div class="caption">Movement history</div></div>
            <div class="portlet-body">
                <table class="table table-bordered">
                    <thead><tr><th>Date</th><th>Type</th><th>Quantity</th><th>Unit cost</th><th>Reference</th><th>By</th><th>Notes</th></tr></thead>
                    <tbody>
                        @forelse($movements as $m)
                        <tr>
                            <td>{{ $m->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $m->type }}</td>
                            <td>{{ $m->quantity > 0 ? '+' : '' }}{{ $m->quantity }}</td>
                            <td>@if($m->unit_cost){{ $currency }}{{ number_format($m->unit_cost, 2) }}@else-@endif</td>
                            <td>{{ $m->reference_type }} #{{ $m->reference_id }}</td>
                            <td>{{ $m->admin->name ?? '-' }}</td>
                            <td>{{ $m->notes }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7">No movements yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $movements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
