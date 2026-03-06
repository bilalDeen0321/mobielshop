@extends('admin.layout')

@section('title', 'Suppliers')

@section('content')
<h3 class="page-title"> Suppliers <small>list and manage</small></h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><span>Suppliers</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark"><i class="icon-briefcase font-dark"></i><span class="caption-subject bold uppercase">Suppliers</span></div>
                <div class="actions"><a href="{{ route('admin.suppliers.create') }}" class="btn btn-sm green">Add Supplier <i class="fa fa-plus"></i></a></div>
            </div>
            <div class="portlet-body">
                <form method="GET" class="form-inline margin-bottom-15">
                    <input type="text" name="q" class="form-control" placeholder="Search" value="{{ $q }}">
                    <button type="submit" class="btn blue">Search</button>
                </form>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr><th>#</th><th>Name</th><th>Phone</th><th>Email</th><th>Purchases</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->phone ?? '-' }}</td>
                            <td>{{ $s->email ?? '-' }}</td>
                            <td>{{ $s->purchases_count }}</td>
                            <td>
                                <a href="{{ route('admin.suppliers.show', $s) }}" class="btn btn-xs default">View</a>
                                <a href="{{ route('admin.suppliers.edit', $s) }}" class="btn btn-xs blue">Edit</a>
                                <form action="{{ route('admin.suppliers.destroy', $s) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs red" {{ $s->purchases_count > 0 ? 'disabled' : '' }}>Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">No suppliers yet. <a href="{{ route('admin.suppliers.create') }}">Add one</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
