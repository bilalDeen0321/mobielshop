@extends('admin.layout')

@section('title', 'Add Brand')

@section('content')
<h3 class="page-title"> Add Brand </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><a href="{{ route('admin.brands.index') }}">Brands</a><i class="fa fa-angle-right"></i></li>
        <li><span>Add Brand</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption"><i class="icon-tag"></i> New Brand</div>
            </div>
            <div class="portlet-body form">
                <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Name <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Slug</label>
                            <div class="col-md-8">
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="Leave blank to auto-generate from name" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Image</label>
                            <div class="col-md-8">
                                <input type="file" name="image" class="form-control" accept="image/*" />
                                <span class="help-block">Optional. Shown on home page "Shop By Brand" section.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Sort order</label>
                            <div class="col-md-8">
                                <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', 0) }}" />
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8">
                                <button type="submit" class="btn green">Save</button>
                                <a href="{{ route('admin.brands.index') }}" class="btn default">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
