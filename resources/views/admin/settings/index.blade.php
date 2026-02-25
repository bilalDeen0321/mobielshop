@extends('admin.layout')

@section('title', 'Website Settings')

@section('content')
<h3 class="page-title"> Website Settings
    <small>slider images & theme colors</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>Website Settings</span></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-picture font-dark"></i>
                    <span class="caption-subject bold uppercase">Theme Colors</span>
                </div>
            </div>
            <div class="portlet-body">
                <form action="{{ route('admin.settings.theme') }}" method="POST" class="form-horizontal">
                    @csrf
                    <div class="form-group">
                        <label class="col-md-3 control-label">Primary</label>
                        <div class="col-md-6">
                            <input type="color" name="theme_primary" value="{{ $themePrimary }}" class="form-control" style="height: 38px; padding: 2px; cursor: pointer;">
                            <span class="help-block">Main brand color (buttons, links)</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Secondary</label>
                        <div class="col-md-6">
                            <input type="color" name="theme_secondary" value="{{ $themeSecondary }}" class="form-control" style="height: 38px; padding: 2px; cursor: pointer;">
                            <span class="help-block">Nav / dark areas</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Accent</label>
                        <div class="col-md-6">
                            <input type="color" name="theme_accent" value="{{ $themeAccent }}" class="form-control" style="height: 38px; padding: 2px; cursor: pointer;">
                            <span class="help-block">Highlights, badges</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-3 col-md-6">
                            <button type="submit" class="btn green">Save theme colors</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-film font-dark"></i>
                    <span class="caption-subject bold uppercase">Home page slider</span>
                </div>
                <div class="actions">
                    <span class="caption">Upload new image (shown on home page)</span>
                </div>
            </div>
            <div class="portlet-body">
                <form action="{{ route('admin.settings.slider.upload') }}" method="POST" enctype="multipart/form-data" class="form-inline" style="margin-bottom: 20px;">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="image" accept="image/*" required class="form-control">
                    </div>
                    <div class="form-group">
                        <input type="text" name="caption" placeholder="Caption (optional)" class="form-control" style="max-width: 200px;">
                    </div>
                    <button type="submit" class="btn green">Upload slider image</button>
                </form>
                <p class="text-muted">Use at least 3 images for the home slider. Order: drag rows below (optional).</p>
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover" id="slider-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Preview</th>
                                <th>Caption</th>
                                <th style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sliderImages as $img)
                            <tr data-id="{{ $img->id }}">
                                <td>{{ $img->sort_order + 1 }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $img->path) }}" alt="" style="max-height: 60px; max-width: 120px; object-fit: cover; border-radius: 4px;">
                                </td>
                                <td>{{ $img->caption ?? '—' }}</td>
                                <td>
                                    <form action="{{ route('admin.settings.slider.delete', $img) }}" method="POST" style="display:inline;" onsubmit="return confirm('Remove this image from the slider?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs red">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No slider images yet. Upload at least 3 for the home page.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
