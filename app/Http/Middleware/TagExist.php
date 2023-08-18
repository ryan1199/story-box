<?php

namespace App\Http\Middleware;

use App\Models\Tag;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TagExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tag_exist = Tag::where('name', $request->route('tag')->name)->first();
        if($tag_exist == null)
        {
            session()->flash('error', 'Tag not found');
            return redirect()->route('tags.index');
        }
        return $next($request);
    }
}
