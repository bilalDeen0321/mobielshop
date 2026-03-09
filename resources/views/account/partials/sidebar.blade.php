<aside class="space-y-4">
    <div class="bg-white border border-gray-200 rounded-md p-4">
        <h2 class="text-sm font-semibold text-gray-900 mb-3">Account menu</h2>
        <nav class="space-y-1 text-sm">
            <a href="{{ route('account.dashboard') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('account.dashboard') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">Dashboard</a>
            <a href="{{ route('account.orders') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('account.orders*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">Orders</a>
            <a href="{{ route('account.addresses') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('account.addresses') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">Addresses</a>
            <a href="{{ route('account.account') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('account.account') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-100' }}">Account details</a>
        </nav>
    </div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="w-full text-center px-4 py-2.5 rounded-md bg-red-50 text-red-600 text-sm font-semibold hover:bg-red-100">
            Logout
        </button>
    </form>
</aside>

