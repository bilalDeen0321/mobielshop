@extends('admin.layout')

@section('title', 'Sell Request #'.$sellRequest->id)

@section('content')
<h3 class="page-title"> Sell request #{{ $sellRequest->id }}
    <small>Sell your phone? enquiry</small>
</h3>
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="{{ route('admin.dashboard') }}">Home</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <a href="{{ route('admin.sell-requests.index') }}">Sell requests</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li><span>#{{ $sellRequest->id }}</span></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-user font-dark"></i>
                    <span class="caption-subject bold uppercase">Customer &amp; device details</span>
                </div>
            </div>
            <div class="portlet-body">
                <h4>Customer</h4>
                <p>
                    <strong>Name:</strong> {{ $sellRequest->name }}<br>
                    <strong>Email:</strong> <a href="mailto:{{ $sellRequest->email }}">{{ $sellRequest->email }}</a><br>
                    @if($sellRequest->phone)
                        <strong>Phone:</strong> {{ $sellRequest->phone }}<br>
                    @endif
                </p>

                <h4>Device</h4>
                <p>
                    @if($sellRequest->device_type)<strong>Type:</strong> {{ ucfirst($sellRequest->device_type) }}<br>@endif
                    @if($sellRequest->brand)<strong>Brand:</strong> {{ $sellRequest->brand }}<br>@endif
                    @if($sellRequest->model)<strong>Model:</strong> {{ $sellRequest->model }}<br>@endif
                    @if($sellRequest->condition)<strong>Condition:</strong> {{ $sellRequest->condition }}<br>@endif
                </p>

                @if($sellRequest->description)
                    <h4>Description</h4>
                    <p>{{ $sellRequest->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-picture font-dark"></i>
                    <span class="caption-subject bold uppercase">Photos</span>
                </div>
            </div>
            <div class="portlet-body">
                @php $images = $sellRequest->images ?? []; @endphp
                @if(is_array($images) && count($images))
                    <div class="row">
                        @foreach($images as $path)
                            <div class="col-xs-6" style="margin-bottom:10px;">
                                <a href="{{ asset('storage/'.$path) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$path) }}" alt="Photo" class="img-responsive img-thumbnail">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No photos uploaded.</p>
                @endif
            </div>
        </div>

        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption font-dark">
                    <i class="icon-envelope-open font-dark"></i>
                    <span class="caption-subject bold uppercase">Respond</span>
                </div>
            </div>
            <div class="portlet-body">
                <p class="text-muted">
                    Click the button below to email the customer with your offer.
                </p>
                <a href="mailto:{{ $sellRequest->email }}?subject={{ rawurlencode('Offer for your device from Ruislip Mobile') }}"
                   class="btn btn-primary btn-block">
                    Email customer
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

