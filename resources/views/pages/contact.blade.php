@extends('layouts.app')

@section('title', 'Contact - Ruislip Mobile')

@section('content')
<section class="container mx-auto px-4 py-10 space-y-8">
    <div class="max-w-3xl">
        <h1 class="text-3xl md:text-4xl font-display font-bold text-gray-900 mb-2">Contact Ruislip Mobile</h1>
        <p class="text-sm md:text-base text-gray-600">
            Have a question about an order, warranty or product? Send us a message or reach us using the details below.
        </p>
    </div>

    @if(session('success'))
        <div class="max-w-3xl rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-3xl rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid gap-10 lg:grid-cols-[1.2fr,1fr] items-start">
        <div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 max-w-xl">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Send us a message</h2>
                <form class="space-y-4" method="post" action="{{ route('contact.submit') }}">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1" for="name">Name *</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}"
                               class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"
                               required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1" for="email">Email *</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"
                               required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1" for="message">Message *</label>
                        <textarea id="message" name="message" rows="5"
                                  class="w-full px-3 py-2 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"
                                  required>{{ old('message') }}</textarea>
                    </div>
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">
                        Send message
                    </button>
                </form>
            </div>
        </div>

        <div class="space-y-5 text-sm text-gray-600">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-base font-semibold text-gray-900 mb-3">Store information</h2>
                <p class="mb-2">
                    <span class="block font-medium text-gray-900">Ruislip Mobile</span>
                    <span class="block">168 High Street</span>
                    <span class="block">Ruislip, HA4 8LJ</span>
                </p>
                <p class="mb-1"><span class="font-medium text-gray-900">Email:</span> <a href="mailto:Ruislipmobile@gmail.com" class="text-primary">Ruislipmobile@gmail.com</a></p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2494.930980131996!2d-0.423!3d51.575!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48766f8e4c9c8d7f%3A0x0000000000000000!2sHA4%208LJ!5e0!3m2!1sen!2suk!4v1700000000000"
                    width="100%"
                    height="260"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</section>
@endsection
