<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request, int $id, string $hash)
    {
        abort_unless($request->hasValidSignature(), 403);

        $user = User::findOrFail($id);

        abort_unless(hash_equals((string) $hash, sha1($user->getEmailForVerification())), 403);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect()
            ->route('login')
            ->with('success', 'Your email has been verified. You can now log in.');
    }

    public function resend(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            return back()->with('success', 'This email is already verified. You can log in now.');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('success', 'A fresh verification email has been sent to your address.');
    }
}
