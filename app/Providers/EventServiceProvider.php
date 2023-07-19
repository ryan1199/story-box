<?php

namespace App\Providers;

use App\Events\EmailVerificationProcessed;
use App\Events\ForgotPasswordProcessed;
use App\Listeners\SendEmailVerificationNotification as ListenersSendEmailVerificationNotification;
use App\Listeners\SendForgotPasswordNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ForgotPasswordProcessed::class => [
            SendForgotPasswordNotification::class,
        ],
        EmailVerificationProcessed::class => [
            ListenersSendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
