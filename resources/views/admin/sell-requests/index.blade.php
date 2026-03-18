@extends('admin.layout')

@section('title', 'Sell Requests')

@section('content')
<h3 class="page-title"> Sell your phone? enquiries
    <small>website submissions</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>Sell requests</span></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-call-in font-dark"></i>
                    <span class="caption-subject bold uppercase">Sell your phone? submissions</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Device</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr>
                                    <td>#{{ $req->id }}</td>
                                    <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                                    <td>{{ $req->name }}</td>
                                    <td>{{ $req->email }}</td>
                                    <td>
                                        {{ ucfirst($req->device_type ?? '') }}
                                        @if($req->brand || $req->model)
                                            <br>
                                            <span class="text-muted">
                                                {{ $req->brand }} {{ $req->model }}
                                            </span>
                                        @endif
                                    </td>
                                    <td><span class="label label-default">{{ $req->status }}</span></td>
                                    <td>
                                        <a href="{{ route('admin.sell-requests.show', $req) }}" class="btn btn-xs btn-default">View</a>
                                        <form action="{{ route('admin.sell-requests.destroy', $req) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this request? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No sell requests yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

