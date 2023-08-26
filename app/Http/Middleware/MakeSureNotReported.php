<?php

namespace App\Http\Middleware;

use App\Models\Report;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MakeSureNotReported
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $model): Response
    {
        switch ($model)
        {
            case 'App\Models\User' :
                $report = Report::where('reportable_type', $model)->where('reportable_id', $request->user()->id)->first();
                break;
            case 'App\Models\Novel' :
                $report = Report::where('reportable_type', $model)->where('reportable_id', $request->novel->id)->first();
                break;
            case 'App\Models\Chapter' :
                $report = Report::where('reportable_type', $model)->where('reportable_id', $request->chapter->id)->first();
                break;
            case 'App\Models\Box' :
                $report = Report::where('reportable_type', $model)->where('reportable_id', $request->box->id)->first();
                break;
            case 'App\Models\Comment' :
                $report = Report::where('reportable_type', $model)->where('reportable_id', $request->comment->id)->first();
                break;
        }
        if($report != null)
        {
            switch ($model)
            {
                case 'App\Models\User' :
                    session()->flash('error', 'User is being reported, you cannot edit  or delete');
                    break;
                case 'App\Models\Novel' :
                    session()->flash('error', 'Novel is being reported, you cannot edit  or delete');
                    break;
                case 'App\Models\Chapter' :
                    session()->flash('error', 'Chapter is being reported, you cannot edit  or delete');
                    break;
                case 'App\Models\Box' :
                    session()->flash('error', 'Box is being reported, you cannot edit or delete');
                    break;
                case 'App\Models\Comment' :
                    session()->flash('error', 'Comment is being reported, you cannot delete');
                    break;
            }
            return redirect()->back();
        }
        return $next($request);
    }
}
