@extends('admin.layout')

@section('title', 'Brands')

@section('content')
<h3 class="page-title"> Brands
    <small>list & manage</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>Brands</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-tag font-dark"></i>
                    <span class="caption-subject bold uppercase">Brands</span>
                </div>
                <div class="actions">
                    <a href="{{ route('admin.brands.create') }}" class="btn btn-sm green"> Add New
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 70px;">Image</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Products</th>
                                <th>Order</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                            <tr>
                                <td>
                                    @if($brand->image_url)
                                        <img src="{{ $brand->image_url }}" alt="" class="img-responsive" style="max-height: 50px; max-width: 60px; object-fit: contain;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->slug }}</td>
                                <td>{{ $brand->products_count }}</td>
                                <td>{{ $brand->sort_order }}</td>
                                <td>
                                    <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-xs blue">Edit</a>
                                    <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this brand? Products will be unlinked.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs red">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No brands yet. <a href="{{ route('admin.brands.create') }}">Add one</a>.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $brands->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
