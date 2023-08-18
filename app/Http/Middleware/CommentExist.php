<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $comment_exist = Comment::where('id', $request->route('comment')->id)->first();
        if($comment_exist == null)
        {
            session()->flash('error', 'Comment not found');
            return redirect()->route('home');
        }
        return $next($request);
    }
}
