<?php

namespace App\Listeners;

use App\Events\ForgotPasswordProcessed;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(public User $user)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ForgotPasswordProcessed $event): void
    {
        Mail::to($event->user->email)->send(new ResetPasswordMail($event->user));
    }
}
