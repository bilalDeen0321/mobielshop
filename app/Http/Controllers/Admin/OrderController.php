<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->latest('placed_at')
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:processing,completed,rejected'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
        ]);

        if (!array_key_exists('status', $validated) && !$request->has('tracking_number')) {
            return redirect()
                ->route('admin.orders.show', $order)
                ->with('error', 'Nothing to update.');
        }

        $updates = [];
        $status = $validated['status'] ?? null;

        if ($request->has('tracking_number')) {
            $updates['tracking_number'] = $validated['tracking_number'] ?: null;
        }

        if ($status) {
            $updates['status'] = $status;
        }

        if ($status && in_array($status, ['processing', 'completed'], true)) {
            $updates['processed_at'] = $order->processed_at ?? now();
        }

        if ($status === 'rejected') {
            $updates['payment_status'] = 'refunded';
        } elseif ($status && $order->payment_status === 'unpaid') {
            $updates['payment_status'] = 'paid';
        }

        $order->update($updates);

        if ($status === 'processing') {
            $message = 'Order marked as processing.';
        } elseif ($status === 'completed') {
            $message = 'Order marked as completed.';
        } elseif ($status === 'rejected') {
            $message = 'Order rejected successfully.';
        } elseif ($request->has('tracking_number')) {
            $message = 'Tracking number updated.';
        } else {
            $message = 'Order updated.';
        }

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('success', $message);
    }
}
