<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTicketIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ticket_exist = User::where('ticket', $request->route('ticket'))->first();
        if($ticket_exist == null)
        {
            session()->flash('error', 'Ticket is not valid');
            return redirect()->route('login.view');
        }
        return $next($request);
    }
}
