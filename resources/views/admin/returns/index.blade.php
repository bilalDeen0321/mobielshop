@extends('admin.layout')

@section('title', 'Returns / Refunds')

@section('content')
<h3 class="page-title"> Returns <small>refunds and stock restore</small></h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><span>Returns</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark"><i class="icon-arrow-left font-dark"></i><span class="caption-subject bold uppercase">Returns</span></div>
                <div class="actions">Create return from a sale: go to <a href="{{ route('admin.sales.index') }}">Sales</a> → View sale → Refund / Return</div>
            </div>
            <div class="portlet-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr><th>Return #</th><th>Date</th><th>Sale</th><th>Refund amount</th><th>By</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $r)
                        <tr>
                            <td>{{ $r->return_number }}</td>
                            <td>{{ $r->created_at->format('d M Y H:i') }}</td>
                            <td><a href="{{ route('admin.sales.show', $r->sale) }}">{{ $r->sale->sale_number }}</a></td>
                            <td>{{ $currency }}{{ number_format($r->total_refund, 2) }}</td>
                            <td>{{ $r->admin->name ?? '-' }}</td>
                            <td><a href="{{ route('admin.returns.show', $r) }}" class="btn btn-xs blue">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">No returns yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $returns->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
