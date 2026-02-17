@extends('layouts.app')

@section('title', 'Contact - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10 grid gap-8 md:grid-cols-[1.2fr,1fr]">
    <div>
        <h1 class="text-3xl font-display font-bold text-gray-900 mb-4">Contact Us</h1>
        <p class="text-sm text-gray-600 mb-6">Have a question about an order, warranty or product? Send us a message.</p>
        <form class="space-y-4 max-w-lg" method="post" action="#">
            @csrf
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1" for="name">Name</label>
                <input id="name" type="text" name="name" class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1" for="email">Email</label>
                <input id="email" type="email" name="email" class="w-full h-10 px-3 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1" for="message">Message</label>
                <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 rounded-md border border-gray-200 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-primary/40"></textarea>
            </div>
            <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">
                Send message
            </button>
        </form>
    </div>
    <div class="space-y-4 text-sm text-gray-600">
        <h2 class="text-base font-semibold text-gray-900">Store information</h2>
        <p>20 Bugsby's Way, SE7 7SJ, London, UK</p>
        <p>Phone: +44 7923 464508</p>
        <p>Email: support@lowpricephones.com</p>
    </div>
</section>
@endsection
