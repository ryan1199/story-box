<?php

namespace App\Listeners;

use App\Events\EmailVerificationProcessed;
use App\Mail\EmailVerificationMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailVerificationNotification
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
    public function handle(EmailVerificationProcessed $event): void
    {
        Mail::to($event->user->email)->send(new EmailVerificationMail($event->user));
    }
}
