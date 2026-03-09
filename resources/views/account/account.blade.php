@extends('layouts.app')

@section('title', 'Account Details')

@section('content')
<section class="container mx-auto px-4 py-10 grid gap-8 lg:grid-cols-[1fr,3fr]">
    @include('account.partials.sidebar')

    @php
        $parts = preg_split('/\s+/', $user->name ?? '', 2);
        $firstName = $parts[0] ?? '';
        $lastName = $parts[1] ?? '';
    @endphp

    <div class="space-y-4">
        <div class="bg-white border border-gray-200 rounded-md p-4">
            <h1 class="text-xl font-display font-bold text-gray-900 mb-1">Account details</h1>
            <p class="text-sm text-gray-600">Update your personal information and change your password.</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-md p-4 space-y-6">
            <form method="POST" action="{{ route('account.account.update') }}" class="space-y-3">
                @csrf
                <h2 class="text-sm font-semibold text-gray-900 mb-1">Personal information</h2>
                <div class="grid md:grid-cols-2 gap-3 text-xs">
                    <div>
                        <label class="block text-gray-600 mb-1">First name</label>
                        <input type="text" name="first_name" value="{{ old('first_name', $firstName) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Last name</label>
                        <input type="text" name="last_name" value="{{ old('last_name', $lastName) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                </div>
                <div class="flex justify-end pt-1">
                    <button type="submit" class="px-5 py-2 rounded-md bg-primary text-white text-sm font-semibold hover:opacity-90">
                        Save details
                    </button>
                </div>
            </form>

            <hr class="border-gray-100">

            <form method="POST" action="{{ route('account.password.update') }}" class="space-y-3">
                @csrf
                <h2 class="text-sm font-semibold text-gray-900 mb-1">Change password</h2>
                <div class="grid md:grid-cols-3 gap-3 text-xs">
                    <div class="md:col-span-1">
                        <label class="block text-gray-600 mb-1">Current password</label>
                        <input type="password" name="current_password" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">New password</label>
                        <input type="password" name="password" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label class="block text-gray-600 mb-1">Confirm password</label>
                        <input type="password" name="password_confirmation" class="w-full h-9 px-2 rounded-md border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                </div>
                <div class="flex justify-end pt-1">
                    <button type="submit" class="px-5 py-2 rounded-md bg-gray-900 text-white text-sm font-semibold hover:bg-black">
                        Update password
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

