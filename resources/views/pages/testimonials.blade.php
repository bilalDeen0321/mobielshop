@extends('layouts.app')

@section('title', 'Testimonials - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10 max-w-4xl">
    <h1 class="text-3xl font-display font-bold text-gray-900 mb-6">Testimonials</h1>
    <div class="grid gap-6 md:grid-cols-3">
        @foreach([
            ['quote' => 'Fast delivery, exactly as described i.e. sealed and box unopened, well pleased, 5* seller thanks.', 'name' => 'n***n', 'product' => 'Samsung Galaxy A20e'],
            ['quote' => 'I would like to thank you very much for the phone, my daughter is very satisfied. Highly recommended.', 'name' => 'O***O', 'product' => 'Huawei P30 Lite'],
            ['quote' => 'A very good item at an excellent price. Communication with the vendor was also top notch.', 'name' => '9***l', 'product' => 'Example product'],
        ] as $t)
        <div class="bg-white border border-gray-200 rounded-md p-4 text-sm">
            <p class="text-gray-600 mb-3">"{{ $t['quote'] }}"</p>
            <p class="font-semibold text-gray-900 text-xs">{{ $t['name'] }}</p>
            <p class="text-[11px] text-gray-500">{{ $t['product'] }}</p>
        </div>
        @endforeach
    </div>
</section>
@endsection
