<?php

namespace App\Http\Middleware;

use App\Models\Category;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $category_exist = Category::where('name', $request->route('category')->name)->first();
        if($category_exist == null)
        {
            session()->flash('error', 'Category not found');
            return redirect()->route('categories.index');
        }
        return $next($request);
    }
}
