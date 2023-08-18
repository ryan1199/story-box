<?php

namespace App\Http\Middleware;

use App\Models\Novel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NovelExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $novel_exist = Novel::where('slug', $request->route('novel')->slug)->first();
        if($novel_exist == null)
        {
            session()->flash('error', 'Novel not found');
            return redirect()->route('novels.index');
        }
        return $next($request);
    }
}
