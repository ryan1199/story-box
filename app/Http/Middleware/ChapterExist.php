<?php

namespace App\Http\Middleware;

use App\Models\Chapter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChapterExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $chapter_exist = Chapter::where('slug', $request->route('chapter')->slug)->first();
        if($chapter_exist == null)
        {
            session()->flash('error', 'Chapter not found');
            return redirect()->route('novels.show', $request->route('novel'));
        }
        return $next($request);
    }
}
