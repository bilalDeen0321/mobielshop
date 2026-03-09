<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)->latest()->limit(5)->get();

        return view('account.dashboard', compact('user', 'recentOrders'));
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->latest()->paginate(10);

        return view('account.orders', compact('orders', 'user'));
    }

    public function showOrder(Order $order)
    {
        $user = Auth::user();
        abort_unless($order->user_id === $user->id, 404);
        $order->load('items');

        return view('account.order-show', compact('order', 'user'));
    }

    public function addresses()
    {
        $user = Auth::user();
        $shipping = UserAddress::firstOrNew(['user_id' => $user->id, 'type' => 'shipping']);
        $billing = UserAddress::firstOrNew(['user_id' => $user->id, 'type' => 'billing']);

        return view('account.addresses', compact('user', 'shipping', 'billing'));
    }

    public function saveAddresses(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'shipping.full_name' => ['required', 'string', 'max:255'],
            'shipping.phone' => ['nullable', 'string', 'max:50'],
            'shipping.street' => ['required', 'string', 'max:255'],
            'shipping.city' => ['required', 'string', 'max:120'],
            'shipping.state' => ['nullable', 'string', 'max:120'],
            'shipping.postal_code' => ['required', 'string', 'max:20'],
            'shipping.country' => ['required', 'string', 'max:120'],
            'billing.full_name' => ['nullable', 'string', 'max:255'],
            'billing.phone' => ['nullable', 'string', 'max:50'],
            'billing.street' => ['nullable', 'string', 'max:255'],
            'billing.city' => ['nullable', 'string', 'max:120'],
            'billing.state' => ['nullable', 'string', 'max:120'],
            'billing.postal_code' => ['nullable', 'string', 'max:20'],
            'billing.country' => ['nullable', 'string', 'max:120'],
        ]);

        $shippingData = $data['shipping'];
        $shippingData['type'] = 'shipping';
        $shippingData['user_id'] = $user->id;
        UserAddress::updateOrCreate(
            ['user_id' => $user->id, 'type' => 'shipping'],
            $shippingData
        );

        if (!empty($data['billing']['street'])) {
            $billingData = $data['billing'];
            $billingData['type'] = 'billing';
            $billingData['user_id'] = $user->id;
            UserAddress::updateOrCreate(
                ['user_id' => $user->id, 'type' => 'billing'],
                $billingData
            );
        }

        return redirect()->route('account.addresses')->with('success', 'Addresses updated successfully.');
    }

    public function account()
    {
        $user = Auth::user();

        return view('account.account', compact('user'));
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $user->name = trim($validated['first_name'] . ' ' . ($validated['last_name'] ?? ''));
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->save();

        return redirect()->route('account.account')->with('success', 'Account details updated.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->password = $request->input('password');
        $user->save();

        return redirect()->route('account.account')->with('success', 'Password updated successfully.');
    }
}

