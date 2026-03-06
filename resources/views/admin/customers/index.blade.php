@extends('admin.layout')

@section('title', 'Customers')

@section('content')
<h3 class="page-title"> Customers <small>list & manage</small></h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><span>Customers</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark"><i class="icon-user font-dark"></i><span class="caption-subject bold uppercase">Customers</span></div>
                <div class="actions">
                    <a href="{{ route('admin.customers.create') }}" class="btn btn-sm green">Add Customer <i class="fa fa-plus"></i></a>
                </div>
            </div>
            <div class="portlet-body">
                <form method="GET" class="form-inline margin-bottom-15">
                    <input type="text" name="q" class="form-control" placeholder="Search name, phone, email..." value="{{ $q }}">
                    <button type="submit" class="btn blue">Search</button>
                </form>
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Sales</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $c)
                        <tr>
                            <td>{{ $c->id }}</td>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->phone ?? '—' }}</td>
                            <td>{{ $c->email ?? '—' }}</td>
                            <td>{{ $c->sales_count }}</td>
                            <td>
                                <a href="{{ route('admin.customers.show', $c) }}" class="btn btn-xs default">View</a>
                                <a href="{{ route('admin.customers.edit', $c) }}" class="btn btn-xs blue">Edit</a>
                                <form action="{{ route('admin.customers.destroy', $c) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this customer?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs red">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">No customers yet. <a href="{{ route('admin.customers.create') }}">Add one</a>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $customers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
