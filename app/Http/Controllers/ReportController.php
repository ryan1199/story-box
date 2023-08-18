<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddReportRequest;
use App\Models\Box;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\History;
use App\Models\Novel;
use App\Models\NovelCategoryTagSearch;
use App\Models\Report;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom-auth');
        $this->middleware('throttle:global', ['except' => ['index']]);
    }
    public function index()
    {
        $check_reports = Report::all();
        foreach($check_reports as $check_report)
        {
            $users_population = User::count();
            $report = Report::with('votes')->where('id', $check_report->id)->first();
            $total_votes = $report->votes->count();
            $status = null;
            if($total_votes == $users_population)
            {
                if($report->votes->whereNotNull('accepted')->count() == $report->votes->whereNotNull('rejected')->count())
                {
                    $random = rand(0,1);
                    $delete = $random == 1 ? true : false;
                    if($delete)
                    {
                        Report::where('id', $check_report->id)->update([
                            'status' => 'Done'
                        ]);
                        $report = Report::where('id', $check_report->id)->first();
                        switch ($report->reportable_type)
                        {
                            case 'App\Models\User' :
                                $user = User::where('id', $report->reportable_id)->with(['image', 'novels.image', 'novels.chapters.comments', 'novels.categories', 'novels.tags', 'novels.search', 'novels.comments', 'boxes', 'histories'])->first();
                                $old_user_image_url = $user->image->url;
                                DB::transaction(function () use ($user) {
                                    // pake observer harusnya
                            
                                    // image
                                    $user->image->delete();
                            
                                    // report <- vote
                                    Vote::where('user_id', $user->id)->delete();
                                    Report::where('user_id', $user->id)->delete();
                                    Report::where('reportable_type', 'App\Models\User')->where('reportable_id', $user->id)->delete();

                                    // comment
                                    Comment::where('user_id', $user->id)->delete();

                                    // history
                                    History::where('user_id', $user->id)->delete();
                            
                                    // box <- pivot
                                    foreach($user->boxes as $box)
                                    {
                                        $box->tags()->detach();
                                        $box->categories()->detach();
                                        $box->novels()->detach();
                                    }
                                    $user->boxes()->delete();
                            
                                    // novel <- chapter <- image <- pivot
                                    NovelCategoryTagSearch::whereIn('novel_id', $user->novels->pluck('id'));
                                    foreach($user->novels as $novel)
                                    {
                                        Storage::delete('novel/'.$novel->image->url);
                                        $novel->image->delete();
                                        $novel->categories()->detach();
                                        $novel->tags()->detach();
                                        foreach($novel->chapters as $chapter)
                                        {
                                            $chapter->comments()->delete();
                                            $chapter->delete();
                                        }
                                        $novel->comments()->delete();
                                        $novel->search()->delete();
                                        $novel->boxes()->detach();
                                        History::where('novel_id', $novel->id)->delete();
                                    }
                                    $user->novels()->delete();
                            
                                    // user
                                    $user->delete();
                                });
                                Storage::delete('profile/'.$old_user_image_url);
                                break;
                            case 'App\Models\Novel' :
                                $novel = Novel::with(['image', 'chapters', 'categories', 'tags', 'comments', 'boxes'])->where('id', $report->reportable_id)->first();
                                $old_novel_image_url = $novel->image->url;
                                DB::transaction(function () use ($novel) {
                                    // image
                                    $novel->image->delete();
                            
                                    // categories
                                    $novel->categories()->detach();
                                    
                                    // tags
                                    $novel->tags()->detach();
                            
                                    // search
                                    $novel->search()->delete();

                                    // comment
                                    $novel->comments()->delete();
                            
                                    // chapter
                                    foreach($novel->chapters as $chapter)
                                    {
                                        $chapter->comments()->delete();
                                    }
                                    // Comment::whereIn('chapter_id', $novel->chapters->pluck('id'))->delete();
                                    $novel->chapters()->delete();
                            
                                    // box
                                    $novel->boxes()->detach();

                                    // report <- vote

                                    // history
                                    History::where('novel_id', $novel->id)->delete();
                            
                                    // novel
                                    $novel->delete();
                                });
                                Storage::delete('novel/'.$old_novel_image_url);
                                break;
                            case 'App\Models\Chapter' :
                                $chapter = Chapter::where('id', $report->reportable_id)->first();
                                $chapter->delete();
                                $chapter->comments()->delete();
                                break;
                            case 'App\Models\Box' :
                                $box = Box::where('id', $report->reportable_id)->first();
                                DB::transaction(function () use ($box) {
                                    $box->tags()->detach();
                                    $box->categories()->detach();
                                    $box->novels()->detach();
                                    $box->delete();
                                });
                                break;
                            case 'App\Models\Comment' :
                                Comment::where('id', $report->reportable_id)->delete();
                                break;
                            default:
                                $status = 'error';
                        }
                        $status = 'accepted';
                    }
                } 
                if($report->votes->whereNotNull('accepted')->count() > $report->votes->whereNotNull('rejected')->count())
                {
                    Report::where('id', $check_report->id)->update([
                        'status' => 'Done'
                    ]);
                    $report = Report::where('id', $check_report->id)->first();
                    switch ($report->reportable_type)
                    {
                        case 'App\Models\User' :
                            $user = User::where('id', $report->reportable_id)->with(['image', 'novels.image', 'novels.chapters.comments', 'novels.categories', 'novels.tags', 'novels.search', 'novels.comments', 'boxes', 'histories'])->first();
                            $old_user_image_url = $user->image->url;
                            DB::transaction(function () use ($user) {
                                // pake observer harusnya
                        
                                // image
                                $user->image->delete();
                        
                                // report <- vote
                                Vote::where('user_id', $user->id)->delete();
                                Report::where('user_id', $user->id)->delete();
                                Report::where('reportable_type', 'App\Models\User')->where('reportable_id', $user->id)->delete();

                                // comment
                                Comment::where('user_id', $user->id)->delete();

                                // history
                                History::where('user_id', $user->id)->delete();
                        
                                // box <- pivot
                                foreach($user->boxes as $box)
                                {
                                    $box->tags()->detach();
                                    $box->categories()->detach();
                                    $box->novels()->detach();
                                }
                                $user->boxes()->delete();
                        
                                // novel <- chapter <- image <- pivot
                                NovelCategoryTagSearch::whereIn('novel_id', $user->novels->pluck('id'));
                                foreach($user->novels as $novel)
                                {
                                    Storage::delete('novel/'.$novel->image->url);
                                    $novel->image->delete();
                                    $novel->categories()->detach();
                                    $novel->tags()->detach();
                                    foreach($novel->chapters as $chapter)
                                    {
                                        $chapter->comments()->delete();
                                        $chapter->delete();
                                    }
                                    $novel->comments()->delete();
                                    $novel->search()->delete();
                                    $novel->boxes()->detach();
                                    History::where('novel_id', $novel->id)->delete();
                                }
                                $user->novels()->delete();
                        
                                // user
                                $user->delete();
                            });
                            Storage::delete('profile/'.$old_user_image_url);
                            break;
                        case 'App\Models\Novel' :
                            $novel = Novel::with(['image', 'chapters', 'categories', 'tags', 'comments', 'boxes'])->where('id', $report->reportable_id)->first();
                            $old_novel_image_url = $novel->image->url;
                            DB::transaction(function () use ($novel) {
                                // image
                                $novel->image->delete();
                        
                                // categories
                                $novel->categories()->detach();
                                
                                // tags
                                $novel->tags()->detach();
                        
                                // search
                                $novel->search()->delete();

                                // comment
                                $novel->comments()->delete();
                        
                                // chapter
                                foreach($novel->chapters as $chapter)
                                {
                                    $chapter->comments()->delete();
                                }
                                // Comment::whereIn('chapter_id', $novel->chapters->pluck('id'))->delete();
                                $novel->chapters()->delete();
                        
                                // box
                                $novel->boxes()->detach();

                                // report <- vote

                                // history
                                History::where('novel_id', $novel->id)->delete();
                        
                                // novel
                                $novel->delete();
                            });
                            Storage::delete('novel/'.$old_novel_image_url);
                            break;
                        case 'App\Models\Chapter' :
                            $chapter = Chapter::where('id', $report->reportable_id)->first();
                            $chapter->delete();
                            $chapter->comments()->delete();
                            break;
                        case 'App\Models\Box' :
                            $box = Box::where('id', $report->reportable_id)->first();
                            DB::transaction(function () use ($box) {
                                $box->tags()->detach();
                                $box->categories()->detach();
                                $box->novels()->detach();
                                $box->delete();
                            });
                            break;
                        case 'App\Models\Comment' :
                            Comment::where('id', $report->reportable_id)->delete();
                            break;
                        default :
                            $status = 'error';
                    }
                    $status = 'accepted';
                } else {
                    Report::where('id', $check_report->id)->update([
                        'status' => 'Done'
                    ]);
                    $status = 'rejected';
                }
            }
        }
        $check_reports = Report::all();
        foreach($check_reports as $check_report)
        {
            if($check_report->status == 'Done')
            {
                $check_report->votes()->delete();
                Report::where('id', $check_report->id)->delete();
            }
        }
        $users_reported = User::has('report')->with('report.user', 'report.votes.user')->orderBy('username', 'asc')->get();
        $novels_reported = Novel::has('report')->with('report.user')->orderBy('title', 'asc')->get();
        $chapters_reported = Chapter::has('report')->with(['report.user', 'novel'])->orderBy('title', 'asc')->get();
        $boxes_reported = Box::has('report')->with(['report.user', 'report.votes.user', 'user'])->orderBy('title', 'asc')->get();
        $comments_reported = Comment::has('report')->with(['report.user', 'user'])->orderBy('content', 'asc')->get();
        $users = User::all();
        return view('report.index', [
            'users_reported' => $users_reported,
            'novels_reported' => $novels_reported,
            'chapters_reported' => $chapters_reported,
            'boxes_reported' => $boxes_reported,
            'comments_reported' => $comments_reported,
            'users' => $users
        ]);
    }

    public function add(AddReportRequest $request, $id, $type)
    {
        $validated = $request->validated();
        $report = null;
        switch ($type)
        {
            case "User" :
                $user = User::where('id', $id)->first();
                if($user != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\User')->where('reportable_id', $id)->first();
                    if($report == null)
                    {
                        $report = Report::create(['reason' => $validated['reason'], 'reportable_type' => 'App\Models\User', 'reportable_id' => $id, 'user_id' => Auth::id()]);
                    } else {
                        $report = null;
                    }
                } else {
                    $report = null;
                }
                break;
            case "Novel" :
                $novel = Novel::where('id', $id)->first();
                if($novel != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\Novel')->where('reportable_id', $id)->first();
                    if($report == null)
                    {
                        $report = Report::create(['reason' => $validated['reason'], 'reportable_type' => 'App\Models\Novel', 'reportable_id' => $id, 'user_id' => Auth::id()]);
                    } else {
                        $report = null;
                    }
                } else {
                    $report = null;
                }
                // dd($report);
                break;
            case "Chapter" :
                $chapter = Chapter::where('id', $id)->first();
                if($chapter != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\Chapter')->where('reportable_id', $id)->first();
                    if($report == null)
                    {
                        $report = Report::create(['reason' => $validated['reason'], 'reportable_type' => 'App\Models\Chapter', 'reportable_id' => $id, 'user_id' => Auth::id()]);
                    } else {
                        $report = null;
                    }
                } else {
                    $report = null;
                }
                break;
            case "Box" :
                $box = Box::where('id', $id)->first();
                if($box != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\Box')->where('reportable_id', $id)->first();
                    if($report == null)
                    {
                        $report = Report::create(['reason' => $validated['reason'], 'reportable_type' => 'App\Models\Box', 'reportable_id' => $id, 'user_id' => Auth::id()]);
                    } else {
                        $report = null;
                    }
                } else {
                    $report = null;
                }
                break;
            case "Comment" :
                $comment = Comment::where('id', $id)->first();
                if($comment != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\Comment')->where('reportable_id', $id)->first();
                    if($report == null)
                    {
                        $report = Report::create(['reason' => $validated['reason'], 'reportable_type' => 'App\Models\Comment', 'reportable_id' => $id, 'user_id' => Auth::id()]);
                    } else {
                        $report = null;
                    }
                } else {
                    $report = null;
                }
                break;
            default :
                $report = null;
        }
        if($report == null)
        {
            session()->flash('error', 'What you want to report is unavailable or already reported');
            return redirect()->route('home');
        }
        session()->flash('success', 'Successfully report');
        return redirect()->route('reports.index');
    }

    public function remove($id, $type)
    {
        $status = true;
        switch ($type)
        {
            case "User" :
                if(User::where('id', $id)->first() != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\User')->where('reportable_id', $id)->where('user_id', Auth::id())->first();
                    if($report != null)
                    {
                        Report::where('reportable_type', 'App\Models\User')->where('reportable_id', $id)->where('user_id', Auth::id())->delete();
                        $report->votes()->delete();
                    } else {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
                break;
            case "Novel" :
                if(Novel::where('id', $id)->first() != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\Novel')->where('reportable_id', $id)->where('user_id', Auth::id())->first();
                    if($report != null)
                    {
                        Report::where('reportable_type', 'App\Models\Novel')->where('reportable_id', $id)->where('user_id', Auth::id())->delete();
                        $report->votes()->delete();
                    } else {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
                break;
            case "Chapter" :
                if(Chapter::where('id', $id)->first() != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\Chapter')->where('reportable_id', $id)->where('user_id', Auth::id())->first();
                    if($report != null)
                    {
                        Report::where('reportable_type', 'App\Models\Chapter')->where('reportable_id', $id)->where('user_id', Auth::id())->delete();
                        $report->votes()->delete();
                    } else {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
                break;
            case "Box" :
                if(Box::where('id', $id)->first() != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\Box')->where('reportable_id', $id)->where('user_id', Auth::id())->first();
                    if($report != null)
                    {
                        Report::where('reportable_type', 'App\Models\Box')->where('reportable_id', $id)->where('user_id', Auth::id())->delete();
                        $report->votes()->delete();
                    } else {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
                break;
            case "Comment" :
                if(Comment::where('id', $id)->first() != null)
                {
                    $report = Report::where('reportable_type', 'App\Models\Comment')->where('reportable_id', $id)->where('user_id', Auth::id())->first();
                    if($report != null)
                    {
                        Report::where('reportable_type', 'App\Models\Comment')->where('reportable_id', $id)->where('user_id', Auth::id())->delete();
                        $report->votes()->delete();
                    } else {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
                break;
            default :
                $status = false;
        }
        switch ($status)
        {
            case true :
                session()->flash('success', 'Successfully delete the report');
                break;
            case false :
                session()->flash('error', 'The report you want to delete is not found or you are not the one who make the report');
                break;
        }
        return redirect()->route('reports.index');
    }
}
