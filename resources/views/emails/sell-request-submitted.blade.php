@php
    $r = $sellRequest;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New sell request</title>
</head>
<body>
    <h2>New sell-your-device enquiry</h2>
    <p>You received a new enquiry from the website Sell your phone? form.</p>

    <h3>Customer details</h3>
    <ul>
        <li><strong>Name:</strong> {{ $r->name }}</li>
        <li><strong>Email:</strong> {{ $r->email }}</li>
        @if($r->phone)
            <li><strong>Phone:</strong> {{ $r->phone }}</li>
        @endif
    </ul>

    <h3>Device details</h3>
    <ul>
        @if($r->device_type)<li><strong>Device type:</strong> {{ ucfirst($r->device_type) }}</li>@endif
        @if($r->brand)<li><strong>Brand:</strong> {{ $r->brand }}</li>@endif
        @if($r->model)<li><strong>Model:</strong> {{ $r->model }}</li>@endif
        @if($r->condition)<li><strong>Condition:</strong> {{ $r->condition }}</li>@endif
    </ul>

    @if($r->description)
        <p><strong>Description:</strong></p>
        <p>{{ $r->description }}</p>
    @endif

    @if(is_array($r->images) && count($r->images))
        <h3>Uploaded photos</h3>
        <ul>
            @foreach($r->images as $path)
                <li><a href="{{ asset('storage/'.$path) }}">{{ asset('storage/'.$path) }}</a></li>
            @endforeach
        </ul>
    @endif

    <p>You can reply directly to this email to offer a price.</p>
</body>
</html>

