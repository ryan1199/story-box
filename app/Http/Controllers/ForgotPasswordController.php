<?php

namespace App\Http\Controllers;

use App\Events\ForgotPasswordProcessed;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetPasswordEmailRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function sendEmail(SendResetPasswordEmailRequest $sendResetPasswordEmailRequest)
    {
        $validated = $sendResetPasswordEmailRequest->validated();
        $user = User::where('email', $validated['email'])->update([
            'ticket' => Str::random(100),
        ]);
        ForgotPasswordProcessed::dispatch(User::findOrFail($user));
        session()->flash('success', 'We have send you an email, go check it');
        return redirect()->route('home');
    }
    public function resetPassword(ResetPasswordRequest $resetPasswordRequest)
    {
        $validated = $resetPasswordRequest->validated();
        User::where('email', $resetPasswordRequest->email)->update([
            'password' => Hash::make($validated['password']),
            'ticket' => null
        ]);
        session()->flash('success', 'Change password done, now you can login with your new password');
        return redirect()->route('login.view');
    }
}
