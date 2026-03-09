@extends('admin.layout')

@section('title', $supplier->name)

@section('content')
<h3 class="page-title"> Supplier: {{ $supplier->name }} </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.suppliers.index') }}">Suppliers</a><i class="fa fa-angle-right"></i></li>
        <li><span>{{ $supplier->name }}</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title"><div class="caption">Details</div><div class="actions"><a href="{{ route('admin.suppliers.edit', $supplier) }}" class="btn btn-sm blue">Edit</a></div></div>
            <div class="portlet-body">
                <p><strong>Name:</strong> {{ $supplier->name }}</p>
                <p><strong>Phone:</strong> {{ $supplier->phone ?? '—' }}</p>
                <p><strong>Email:</strong> {{ $supplier->email ?? '—' }}</p>
                @if($supplier->address)<p><strong>Address:</strong><br>{{ nl2br(e($supplier->address)) }}</p>@endif
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title"><div class="caption">Purchase history ({{ $supplier->purchases->count() }})</div><div class="actions"><a href="{{ route('admin.purchases.create') }}?supplier_id={{ $supplier->id }}" class="btn btn-sm green">New purchase</a></div></div>
            <div class="portlet-body">
                <table class="table table-bordered">
                    <thead><tr><th>Purchase #</th><th>Date</th><th>Total</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($supplier->purchases as $p)
                        <tr>
                            <td>{{ $p->purchase_number }}</td>
                            <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $currency }}{{ number_format($p->total, 2) }}</td>
                            <td><a href="{{ route('admin.purchases.show', $p) }}" class="btn btn-xs default">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="4">No purchases yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
