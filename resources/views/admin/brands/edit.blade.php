@extends('admin.layout')

@section('title', 'Edit Brand')

@section('content')
<h3 class="page-title"> Edit Brand </h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><a href="{{ route('admin.brands.index') }}">Brands</a><i class="fa fa-angle-right"></i></li>
        <li><span>Edit</span></li>
    </ul>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption"><i class="icon-tag"></i> Edit Brand</div>
            </div>
            <div class="portlet-body form">
                <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    @method('PUT')
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-2 control-label">Name <span class="required">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control" value="{{ old('name', $brand->name) }}" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Slug</label>
                            <div class="col-md-8">
                                <input type="text" name="slug" class="form-control" value="{{ old('slug', $brand->slug) }}" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Image</label>
                            <div class="col-md-8">
                                @if($brand->image_url)
                                    <p class="form-control-static"><img src="{{ $brand->image_url }}" alt="" style="max-height: 60px; max-width: 80px; object-fit: contain;"></p>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*" />
                                <span class="help-block">Leave empty to keep current image.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Sort order</label>
                            <div class="col-md-8">
                                <input type="number" name="sort_order" class="form-control" min="0" value="{{ old('sort_order', $brand->sort_order) }}" />
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <div class="row">
                            <div class="col-md-offset-2 col-md-8">
                                <button type="submit" class="btn green">Update</button>
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
