<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentChapterStoreRequest;
use App\Models\Chapter;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;
use App\Models\Comment;
use App\Models\History;
use App\Models\Novel;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ChapterController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom-auth', ['except' => ['index', 'show']]);
        $this->middleware('novel-exist' , ['except' => ['index']]);
        $this->middleware('chapter-exist', ['except' => ['index', 'create', 'store']]);
        $this->middleware('comment-exist', ['only' => ['commentDestroy']]);
        $this->middleware('throttle:global', ['except' => ['show']]);
        $this->middleware('not-reported:App\Models\Chapter', ['only' => ['edit', 'destroy']]);
        $this->middleware('not-reported:App\Models\Comment', ['only' => ['commentDestroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Novel $novel)
    {
        Gate::authorize('create-chapter', $novel);
        return view('chapter.create', [
            'novel' => $novel
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChapterRequest $request, Novel $novel)
    {
        Gate::authorize('store-chapter', $novel);
        $validated = $request->validated();
        $novel->chapters()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'slug' => Str::slug($validated['title']).'-'.strtotime("now"),
        ]);
        $novel->touch();
        session()->flash('success', 'Successfully add the chapter');
        return redirect()->route('users.show', Auth::user()->username);
    }

    /**
     * Display the specified resource.
     */
    public function show(Novel $novel, Chapter $chapter)
    {
        if(Auth::check())
        {
            $user = User::where('id', Auth::id())->first();
            $history = History::updateOrInsert(
                ['user_id' => $user->id, 'novel_id' => $novel->id, 'novel_title' => $novel->title, 'novel_slug' => $novel->slug],
                ['chapter_id' => $chapter->id, 'chapter_title' => $chapter->title, 'chapter_slug' => $chapter->slug]
            );
            $history->touch();
        }
        $chapter = Chapter::with(['comments.report', 'report'])->where('id', $chapter->id)->first();
        $chapters = Chapter::where('novel_id', $novel->id)->orderBy('title', 'asc')->get();
        $current_chapter = $chapters->pluck('id')->search($chapter->id);
        // dd($current_chapter);
        switch ($current_chapter)
        {
            case 0 :
                $prev = null;
                if($chapters->count() > 1)
                {
                    $next = $chapters[$current_chapter + 1];
                } else {
                    $next = null;
                }
                break;
            case $chapters->count() - 1 :
                $prev = $chapters[$current_chapter - 1];
                $next = null;
                break;
            default : 
                $prev = $chapters[$current_chapter - 1];
                $next = $chapters[$current_chapter + 1];
        }
        $users_comments = User::with('image')->whereIn('id', $chapter->comments->pluck('user_id'))->get();
        return view('chapter.show', [
            'novel' => $novel,
            'chapter' => $chapter,
            'prev' => $prev,
            'next' => $next,
            'users_comments' => $users_comments
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Novel $novel ,Chapter $chapter)
    {
        Gate::authorize('edit-chapter', [$novel, $chapter]);
        return view('chapter.edit', [
            'novel' => $novel,
            'chapter' => $chapter
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChapterRequest $request, Novel $novel ,Chapter $chapter)
    {
        Gate::authorize('update-chapter', [$novel, $chapter]);
        $validated = $request->validated();
        $chapter->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'slug' => Str::slug($validated['title']).'-'.strtotime("now"),
        ]);
        $novel->touch();
        session()->flash('success', 'Successfully edit the chapter');
        return redirect()->route('users.show', Auth::user()->username);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Novel $novel ,Chapter $chapter)
    {
        Gate::authorize('delete-chapter', [$novel, $chapter]);
        DB::transaction(function () use ($novel, $chapter) {
            $report = Report::where('reportable_type', 'App\Models\Chapter')->where('reportable_id', $chapter->id)->first();
            if($report != null)
            {
                $report->votes()->delete();
                $report->delete();
            }
            $comments = $chapter->comments()->get();
            foreach($comments as $comment)
            {
                $report = Report::where('reportable_type', 'App\Models\Comment')->where('reportable_id', $comment->id)->first();
                if($report != null)
                {
                    $report->votes()->delete();
                    $report->delete();
                }
            }
            $chapter->comments()->delete();
            $chapter->delete();
            $novel->touch();
        });
        session()->flash('success', 'Successfully delete the chapter');
        return redirect()->route('users.show', Auth::user()->username);
    }

    public function commentStore(CommentChapterStoreRequest $request, Novel $novel, Chapter $chapter)
    {
        $validated = $request->validated();
        $chapter->comments()->create([
            'content' => $validated['content'],
            'user_id' => Auth::id()
        ]);
        session()->flash('success', 'Successfully add the comment');
        return redirect()->route('chapters.show', [$novel, $chapter]);
    }

    public function commentDestroy(Novel $novel, Chapter $chapter, Comment $comment)
    {
        Gate::authorize('delete-comment-chapter', $comment);
        DB::transaction(function () use ($comment) {
            $report = Report::where('reportable_type', 'App\Models\Comment')->where('reportable_id', $comment->id)->first();
            if($report != null)
            {
                $report->votes()->delete();
                $report->delete();
            }
            Comment::where('id', $comment->id)->delete();
        });
        session()->flash('success', 'Successfully delete the comment');
        return redirect()->route('chapters.show', [$novel, $chapter]);
    }
}
