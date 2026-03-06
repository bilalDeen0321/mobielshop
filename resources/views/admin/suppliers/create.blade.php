@extends('admin.layout')

@section('title', 'Add Supplier')

@section('content')
<h3 class="page-title"> Add Supplier </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li><i class="icon-home"></i><a href="{{ route('admin.dashboard') }}">Home</a><i class="fa fa-angle-right"></i></li>
        <li><a href="{{ route('admin.suppliers.index') }}">Suppliers</a><i class="fa fa-angle-right"></i></li>
        <li><span>Add</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-body form">
                <form action="{{ route('admin.suppliers.store') }}" method="POST" class="form-horizontal">
                    @csrf
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Name <span class="required">*</span></label>
                            <div class="col-md-8"><input type="text" name="name" class="form-control" value="{{ old('name') }}" required /></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Phone</label>
                            <div class="col-md-8"><input type="text" name="phone" class="form-control" value="{{ old('phone') }}" /></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Email</label>
                            <div class="col-md-8"><input type="email" name="email" class="form-control" value="{{ old('email') }}" /></div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Address</label>
                            <div class="col-md-8"><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row"><div class="col-md-offset-2 col-md-8">
                            <button type="submit" class="btn green">Save</button>
                            <a href="{{ route('admin.suppliers.index') }}" class="btn default">Cancel</a>
                        </div></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
