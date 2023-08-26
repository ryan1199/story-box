<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Box;
use App\Models\Novel;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('edit-user', function ($current_user, $username) {
            return $current_user->username === $username ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('update-user', function ($current_user, $username) {
            return $current_user->username === $username ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('delete-user', function ($current_user, $username) {
            return $current_user->username === $username ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('edit-box', function ($current_user, $box) {
            return $current_user->id === $box->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('update-box', function ($current_user, $box) {
            return $current_user->id === $box->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('delete-box', function ($current_user, $box) {
            return $current_user->id === $box->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('add-to-box', function ($user, $box) {
            return $user->id === $box->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('remove-from-box', function ($user, $box) {
            return $user->id === $box->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('edit-novel', function ($current_user, $novel) {
            return $current_user->id === $novel->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('update-novel', function ($current_user, $novel) {
            return $current_user->id === $novel->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('delete-novel', function ($current_user, $novel) {
            return $current_user->id === $novel->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('delete-comment-novel', function ($current_user, $comment) {
            return $current_user->id === $comment->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('create-chapter', function ($current_user, $novel) {
            return $current_user->id === $novel->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('store-chapter', function ($current_user, $novel) {
            return $current_user->id === $novel->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('edit-chapter', function ($current_user, $novel, $chapter) {
            return $current_user->id === $novel->user_id && $novel->id === $chapter->novel_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('update-chapter', function ($current_user, $novel, $chapter) {
            return $current_user->id === $novel->user_id && $novel->id === $chapter->novel_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('delete-chapter', function ($current_user, $novel, $chapter) {
            return $current_user->id === $novel->user_id && $novel->id === $chapter->novel_id ? Response::allow() : Response::denyWithStatus(401);
        });
        Gate::define('delete-comment-chapter', function ($current_user, $comment) {
            return $current_user->id === $comment->user_id ? Response::allow() : Response::denyWithStatus(401);
        });
    }
}
