<?php

namespace App\Http\Controllers;

use App\Events\EmailVerificationProcessed;
use App\Http\Requests\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('no-auth');
        $this->middleware('throttle:email-verification');
    }
    public function sendEmail(EmailVerificationRequest $emailVerificationRequest)
    {
        $validated = $emailVerificationRequest->validated();
        $user = User::where('email', $validated['email'])->update([
            'ticket' => Str::random(100),
        ]);
        EmailVerificationProcessed::dispatch(User::findOrFail($user));
        session()->flash('success', 'We have send you an email, go check it');
        return redirect()->route('home');
    }
    public function verify(Request $request)
    {
        User::where('email', $request->email)->update([
            'email_verified_at' => now(),
            'ticket' => null
        ]);
        session()->flash('success', 'Email verification done, now you can use this account');
        return redirect()->route('login.view');
    }
}
