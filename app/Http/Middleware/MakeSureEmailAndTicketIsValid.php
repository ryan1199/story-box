<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MakeSureEmailAndTicketIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where(['email' => $request->route('email'), 'ticket' => $request->route('ticket')])->first();
        if($user == null)
        {
            session()->flash('error', 'Ticket is not valid');
            return redirect()->route('home');
        }
        return $next($request);
    }
}
