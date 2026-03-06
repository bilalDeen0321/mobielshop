@extends('admin.layout')

@section('title', 'Edit Supplier')

@section('content')
<h3 class="page-title"> Edit Supplier </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.suppliers.index') }}">Suppliers</a><i class="fa fa-angle-right"></i></li>
        <li><span>Edit</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body form">
                <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="form-horizontal">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Name <span class="required">*</span></label>
                            <div class="col-md-8"><input type="text" name="name" class="form-control" value="{{ old('name', $supplier->name) }}" required /></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Phone</label>
                            <div class="col-md-8"><input type="text" name="phone" class="form-control" value="{{ old('phone', $supplier->phone) }}" /></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Email</label>
                            <div class="col-md-8"><input type="email" name="email" class="form-control" value="{{ old('email', $supplier->email) }}" /></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Address</label>
                            <div class="col-md-8"><textarea name="address" class="form-control" rows="2">{{ old('address', $supplier->address) }}</textarea></div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row"><div class="col-md-offset-2 col-md-8">
                            <button type="submit" class="btn green">Update</button>
                            <a href="{{ route('admin.suppliers.index') }}" class="btn default">Cancel</a>
                        </div></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
