<?php

namespace App\Http\Middleware;

use App\Models\Box;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BoxExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $box_exist = Box::where('slug', $request->route('box')->slug)->first();
        if($box_exist == null)
        {
            session()->flash('error', 'Box not found');
            return redirect()->route('boxes.index');
        }
        return $next($request);
    }
}
