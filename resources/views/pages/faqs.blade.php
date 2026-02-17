@extends('layouts.app')

@section('title', 'FAQs - LowPricePhones')

@section('content')
<section class="container mx-auto px-4 py-10 max-w-3xl">
    <h1 class="text-3xl font-display font-bold text-gray-900 mb-6">FAQ'S</h1>
    <div class="space-y-4">
        @foreach([
            ['q' => 'What kinds of phones do you sell?', 'a' => 'We specialise in a variety of phones from major brands including Apple, Samsung, Google, Huawei and more. All phones are factory unlocked unless otherwise stated.'],
            ['q' => 'Are your phones new, used or refurbished?', 'a' => 'We clearly state the condition on each product page. Stock ranges from brand new to refurbished and used excellent condition handsets.'],
            ['q' => 'Do your phones come unlocked?', 'a' => 'Yes, all our phones are SIM-free and factory unlocked, so you can use any compatible UK or international network.'],
            ['q' => 'What warranty do you provide?', 'a' => 'Most items come with 12 months warranty covering manufacturing faults and hardware issues. Please see individual products for exact details.'],
        ] as $item)
        <div class="border border-gray-200 rounded-md p-4 bg-white">
            <h2 class="text-sm font-semibold text-gray-900 mb-1">{{ $item['q'] }}</h2>
            <p class="text-sm text-gray-600">{{ $item['a'] }}</p>
        </div>
        @endforeach
    </div>
</section>
@endsection
