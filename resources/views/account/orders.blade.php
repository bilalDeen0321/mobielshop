@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<section class="container mx-auto px-4 py-10 grid gap-8 lg:grid-cols-[1fr,3fr]">
    @include('account.partials.sidebar')

    <div class="space-y-4">
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <h1 class="text-xl font-display font-bold text-gray-900 mb-1">My Orders</h1>
            <p class="text-sm text-gray-600">Here you can see all orders you have placed with {{ config('app.name') }}.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-md">
            <div class="overflow-x-auto">
                <table class="min-w-full text-xs">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Order</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Date</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Total</th>
                            <th class="px-3 py-2 text-left font-semibold text-gray-600">Status</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                        <tr>
                            <td class="px-3 py-2 text-sm font-semibold text-gray-900">#{{ $order->order_number }}</td>
                            <td class="px-3 py-2 text-xs text-gray-600">{{ optional($order->placed_at ?? $order->created_at)->format('d M Y, H:i') }}</td>
                            <td class="px-3 py-2 text-sm text-gray-900">{{ $currency }}{{ number_format($order->total, 2) }}</td>
                            <td class="px-3 py-2 text-xs">
                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] capitalize
                                    @if($order->status === 'completed') bg-green-50 text-green-700
                                    @elseif($order->status === 'processing') bg-blue-50 text-blue-700
                                    @elseif($order->status === 'cancelled') bg-red-50 text-red-700
                                    @else bg-yellow-50 text-yellow-800 @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-3 py-2 text-right">
                                <a href="{{ route('account.orders.show', $order) }}" class="text-xs text-primary font-semibold hover:underline">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-center text-sm text-gray-500">
                                You haven't placed any orders yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(method_exists($orders, 'links'))
            <div class="border-t border-gray-100 px-3 py-2">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
</section>
@endsection

