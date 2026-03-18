@extends('layouts.app')

@section('title', 'Sell your device - Ruislip Mobile')

@section('content')
<section class="container mx-auto px-4 py-10 max-w-3xl">
    <h1 class="text-3xl font-display font-bold text-gray-900 mb-2">Sell your phone?</h1>
    <p class="text-sm md:text-base text-gray-600 mb-6">
        Share a few details and photos of your phone, laptop or tablet and we’ll email you back with a no-obligation offer.
    </p>

    @if(session('success'))
        <div class="mb-6 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Your name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email address *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone number</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Device type</label>
                <select name="device_type"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">Select...</option>
                    @foreach(['phone' => 'Phone', 'laptop' => 'Laptop', 'tablet' => 'Tablet', 'other' => 'Other'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('device_type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                <input type="text" name="brand" value="{{ old('brand') }}"
                       placeholder="e.g. Apple, Samsung, Dell"
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                <input type="text" name="model" value="{{ old('model') }}"
                       placeholder="e.g. iPhone 14 Pro, Galaxy S23"
                       class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Condition</label>
            <select name="condition"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary/40">
                <option value="">Select...</option>
                @foreach(['Like new', 'Very good', 'Good', 'Fair', 'Needs repair'] as $cond)
                    <option value="{{ $cond }}" @selected(old('condition') === $cond)>{{ $cond }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">More details</label>
            <textarea name="description" rows="4"
                      placeholder="Tell us about any marks, faults, included accessories, box, etc."
                      class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Upload photos (up to 5)</label>
            <input type="file" name="photos[]" multiple accept="image/*"
                   class="block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90">
            <p class="mt-1 text-xs text-gray-500">Add clear photos of the front, back and any damage. Max 4MB per photo.</p>
        </div>

        <div class="pt-2">
            <button type="submit"
                    class="inline-flex items-center justify-center px-6 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">
                Submit details
            </button>
        </div>
    </form>
</section>
@endsection

