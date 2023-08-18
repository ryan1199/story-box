<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user_exist = User::where('username', $request->route('username'))->first();
        if($user_exist == null)
        {
            session()->flash('error', 'User not found');
            return redirect()->route('home');
        }
        return $next($request);
    }
}
