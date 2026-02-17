@extends('layouts.app')

@section('title', '404 - Page Not Found')

@section('content')
<div class="flex min-h-[60vh] items-center justify-center bg-gray-100">
    <div class="text-center">
        <h1 class="mb-4 text-4xl font-bold">404</h1>
        <p class="mb-4 text-xl text-gray-600">Oops! Page not found</p>
        <a href="{{ route('home') }}" class="text-primary underline hover:no-underline">Return to Home</a>
    </div>
</div>
@endsection
