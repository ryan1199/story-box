<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::user()->email_verified_at != null)
        {
            return $next($request);
        }
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        session()->flash('error', 'Please confirm your email first');
        return redirect()->route('login.view');
    }
}
