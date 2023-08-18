<?php

namespace App\Http;

use App\Http\Middleware\AuthCustom;
use App\Http\Middleware\BoxExist;
use App\Http\Middleware\CategoryExist;
use App\Http\Middleware\ChapterExist;
use App\Http\Middleware\CommentExist;
use App\Http\Middleware\EnsureTicketIsValid;
use App\Http\Middleware\EnsureUserIsVerified;
use App\Http\Middleware\MakeSureEmailAndTicketIsValid;
use App\Http\Middleware\NoAuth;
use App\Http\Middleware\NovelExist;
use App\Http\Middleware\TagExist;
use App\Http\Middleware\UserExist;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'no-auth' => NoAuth::class,
        'custom-auth' => AuthCustom::class,
        'valid-ticket' => MakeSureEmailAndTicketIsValid::class,
        'user-verified' => EnsureUserIsVerified::class,
        'ticket-valid' => EnsureTicketIsValid::class,
        'user-exist' => UserExist::class,
        'novel-exist' => NovelExist::class,
        'chapter-exist' => ChapterExist::class,
        'box-exist' => BoxExist::class,
        'comment-exist' => CommentExist::class,
        'tag-exist' => TagExist::class,
        'category-exist' => CategoryExist::class,
    ];
}
