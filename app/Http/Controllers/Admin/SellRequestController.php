<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellRequest;
use Illuminate\Http\Request;

class SellRequestController extends Controller
{
    public function index()
    {
        $requests = SellRequest::orderByDesc('created_at')->paginate(20);

        return view('admin.sell-requests.index', compact('requests'));
    }

    public function show(SellRequest $sellRequest)
    {
        return view('admin.sell-requests.show', compact('sellRequest'));
    }

    public function destroy(SellRequest $sellRequest)
    {
        $sellRequest->delete();

        return redirect()
            ->route('admin.sell-requests.index')
            ->with('success', 'Sell request deleted.');
    }
}

