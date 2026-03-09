@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<section class="container mx-auto px-4 py-10 grid gap-8 lg:grid-cols-[1fr,3fr]">
    @include('account.partials.sidebar')

    <div class="space-y-4">
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <h1 class="text-xl font-display font-bold text-gray-900 mb-1">Addresses</h1>
            <p class="text-sm text-gray-600">Manage your delivery and billing addresses for faster checkout.</p>
        </div>

        <form method="POST" action="{{ route('account.addresses.save') }}" class="grid gap-4 md:grid-cols-2">
            @csrf
            <div class="bg-white border border-gray-200 rounded-md p-4 space-y-3">
                <h2 class="text-sm font-semibold text-gray-900">Delivery address</h2>
                <div class="space-y-2 text-xs">
                    <div>
                        <label class="block text-gray-600 mb-1">Full name</label>
                        <input type="text" name="shipping[full_name]" value="{{ old('shipping.full_name', $shipping->full_name ?: $user->name) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Phone</label>
                        <input type="text" name="shipping[phone]" value="{{ old('shipping.phone', $shipping->phone ?: $user->phone) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Street address</label>
                        <input type="text" name="shipping[street]" value="{{ old('shipping.street', $shipping->street) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-600 mb-1">City</label>
                            <input type="text" name="shipping[city]" value="{{ old('shipping.city', $shipping->city) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">State / Province</label>
                            <input type="text" name="shipping[state]" value="{{ old('shipping.state', $shipping->state) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-600 mb-1">Postal code</label>
                            <input type="text" name="shipping[postal_code]" value="{{ old('shipping.postal_code', $shipping->postal_code) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Country</label>
                            <input type="text" name="shipping[country]" value="{{ old('shipping.country', $shipping->country ?: 'United Kingdom') }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-md p-4 space-y-3">
                <h2 class="text-sm font-semibold text-gray-900">Billing address</h2>
                <p class="text-xs text-gray-500 mb-1">Leave blank if your billing address is the same as your delivery address.</p>
                <div class="space-y-2 text-xs">
                    <div>
                        <label class="block text-gray-600 mb-1">Full name</label>
                        <input type="text" name="billing[full_name]" value="{{ old('billing.full_name', $billing->full_name) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Phone</label>
                        <input type="text" name="billing[phone]" value="{{ old('billing.phone', $billing->phone) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Street address</label>
                        <input type="text" name="billing[street]" value="{{ old('billing.street', $billing->street) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-600 mb-1">City</label>
                            <input type="text" name="billing[city]" value="{{ old('billing.city', $billing->city) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">State / Province</label>
                            <input type="text" name="billing[state]" value="{{ old('billing.state', $billing->state) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-gray-600 mb-1">Postal code</label>
                            <input type="text" name="billing[postal_code]" value="{{ old('billing.postal_code', $billing->postal_code) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </div>
                        <div>
                            <label class="block text-gray-600 mb-1">Country</label>
                            <input type="text" name="billing[country]" value="{{ old('billing.country', $billing->country) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">
                    Save addresses
                </button>
            </div>
        </form>
    </div>
</section>
@endsection

