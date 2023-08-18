<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom-auth');
    }
    public function accept($id, User $user)
    {
        if(Vote::where('report_id', $id)->where('user_id', $user->id)->first() == null)
        {
            $user->votes()->create([
                'report_id' => $id,
                'accepted' => true
            ]);
            $status = $this->process($id);
            // $report = Report::where('id', $id)->first();
            // if($report->status == 'Done')
            // {
            //     $report->votes()->delete();
            //     Report::where('id', $id)->delete();
            // }
            switch ($status)
            {
                case 'accepted' :
                    session()->flash('success', 'Successfully voting, the result is accepted');
                    break;
                case 'rejected' :
                    session()->flash('success', 'Successfully voting, the result is rejected');
                    break;
                case 'error' :
                    session()->flash('error', 'Error, you are voting on something that does not exist');
                    break;
                default :
                    session()->flash('success', 'Successfully voting');
            }
        } else {
            session()->flash('error', 'You are already vote');
        }
        return redirect()->route('reports.index');
    }

    public function reject($id, User $user)
    {
        if(Vote::where('report_id', $id)->where('user_id', $user->id)->first() == null)
        {
            $user->votes()->create([
                'report_id' => $id,
                'rejected' => true
            ]);
            $status = $this->process($id);
            // $report = Report::where('id', $id)->first();
            // if($report->status == 'Done')
            // {
            //     $report->votes()->delete();
            //     Report::where('id', $id)->delete();
            // }
            switch ($status)
            {
                case 'accepted' :
                    session()->flash('success', 'Successfully voting, the result is accepted');
                    break;
                case 'rejected' :
                    session()->flash('success', 'Successfully voting, the result is rejected');
                    break;
                case 'error' :
                    session()->flash('error', 'Error, you are voting on something that does not exist');
                    break;
                default :
                    session()->flash('success', 'Successfully voting');
            }
        } else {
            session()->flash('error', 'You are already vote');
        }
        return redirect()->route('reports.index');
    }

    public function process($id)
    {
        $users_population = User::count();
        $report = Report::with('votes')->where('id', $id)->first();
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
                    Report::where('id', $id)->update([
                        'status' => 'Done'
                    ]);
                    $report = Report::where('id', $id)->first();
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
                Report::where('id', $id)->update([
                    'status' => 'Done'
                ]);
                $report = Report::where('id', $id)->first();
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
                Report::where('id', $id)->update([
                    'status' => 'Done'
                ]);
                $status = 'rejected';
            }
        }
        return $status;
    }
}
