<?php

namespace App\Http\Controllers;

use App\Mail\SellRequestSubmitted;
use App\Models\SellRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SellRequestController extends Controller
{
    public function create()
    {
        return view('pages.sell');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'device_type' => ['nullable', 'string', 'max:50'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['nullable', 'image', 'max:4096'],
        ]);

        $storedImages = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file && $file->isValid()) {
                    $storedImages[] = $file->store('sell-requests/'.date('Y/m'), 'public');
                }
            }
        }

        $sellRequest = SellRequest::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'device_type' => $data['device_type'] ?? null,
            'brand' => $data['brand'] ?? null,
            'model' => $data['model'] ?? null,
            'condition' => $data['condition'] ?? null,
            'description' => $data['description'] ?? null,
            'images' => $storedImages ?: null,
            'status' => 'new',
        ]);

        $to = config('mail.from.address') ?: env('ADMIN_EMAIL');
        if ($to) {
            Mail::to($to)->send(new SellRequestSubmitted($sellRequest));
        }

        return redirect()->route('sell.create')
            ->with('success', 'Thank you. We have received your device details and will email you shortly with an offer.');
    }
}

