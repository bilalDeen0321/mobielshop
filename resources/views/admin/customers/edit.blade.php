@extends('admin.layout')

@section('title', 'Edit Customer')

@section('content')
<h3 class="page-title"> Edit Customer </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.customers.index') }}">Customers</a><i class="fa fa-angle-right"></i></li>
        <li><span>Edit</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body form">
                <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="form-horizontal">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Name <span class="required">*</span></label>
                            <div class="col-md-8"><input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required /></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Phone</label>
                            <div class="col-md-8"><input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" /></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Email</label>
                            <div class="col-md-8"><input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" /></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Address</label>
                            <div class="col-md-8"><textarea name="address" class="form-control" rows="2">{{ old('address', $customer->address) }}</textarea></div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row"><div class="col-md-offset-2 col-md-8">
                            <button type="submit" class="btn green">Update</button>
                            <a href="{{ route('admin.customers.index') }}" class="btn default">Cancel</a>
                        </div></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
