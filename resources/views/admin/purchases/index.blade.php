@extends('admin.layout')

@section('title', 'Purchases')

@section('content')
<h3 class="page-title"> Purchases <small>stock received from suppliers</small></h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><span>Purchases</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark"><i class="icon-basket-loaded font-dark"></i><span class="caption-subject bold uppercase">Purchases</span></div>
                <div class="actions"><a href="{{ route('admin.purchases.create') }}" class="btn btn-sm green">New purchase <i class="fa fa-plus"></i></a></div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr><th>Purchase #</th><th>Date</th><th>Supplier</th><th>Total</th><th>By</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $p)
                        <tr>
                            <td>{{ $p->purchase_number }}</td>
                            <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                            <td>{{ $p->supplier->name ?? '-' }}</td>
                            <td>£{{ number_format($p->total, 2) }}</td>
                            <td>{{ $p->admin->name ?? '-' }}</td>
                            <td><a href="{{ route('admin.purchases.show', $p) }}" class="btn btn-xs blue">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">No purchases yet. <a href="{{ route('admin.purchases.create') }}">Add one</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
