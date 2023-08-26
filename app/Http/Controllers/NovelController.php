<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentNovelStoreRequest;
use App\Http\Requests\SearchNovelRequest;
use App\Models\Novel;
use App\Http\Requests\StoreNovelRequest;
use App\Http\Requests\UpdateNovelRequest;
use App\Models\Box;
use App\Models\Category;
use App\Models\CategoryNovel;
use App\Models\Comment;
use App\Models\History;
use App\Models\NovelCategoryTagSearch;
use App\Models\Report;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NovelController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom-auth', ['except' => ['index', 'show', 'search']]);
        $this->middleware('novel-exist', ['except' => ['index', 'create', 'store', 'search']]);
        $this->middleware('comment-exist', ['only' => ['commentDestroy']]);
        $this->middleware('throttle:global', ['except' => ['index', 'show', 'search']]);
        $this->middleware('not-reported:App\Models\Novel', ['only' => ['edit', 'destroy']]);
        $this->middleware('not-reported:App\Models\Comment', ['only' => ['commentDestroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $novels = Novel::with(['tags', 'categories', 'chapters', 'user', 'image'])->orderBy('title', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();
        return view('novel.index', [
            'novels' => $novels,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('novel.create', [
            'tags' => $tags,
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNovelRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('id', Auth::id())->first();
        $picture = $request->file('picture');
        $url = $picture->hashName();
        DB::transaction(function () use ($validated, $url, $user) {
            // gamber lebih baik di simpan lewat observer
            $novel = $user->novels()->create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'slug' => Str::slug($validated['title']).'-'.strtotime("now"),
            ]);
            $novel->image()->create([
                'url' => $url,
            ]);
            $categories = Category::whereIn('name', $validated['categories'])->get();
            $tags = Tag::whereIn('name', $validated['tags'])->get();
            $novel->categories()->attach($categories->pluck('id'));
            $novel->tags()->attach($tags->pluck('id'));
            $novel->search()->create([
                'title' => $validated['title'],
                'categories' => $categories->pluck('name')->implode(','),
                'tags' => $tags->pluck('name')->implode(','),
            ]);
        });
        $request->file('picture')->storeAs('novel', $url, 'public');
        session()->flash('success', 'Successfully add the novel');
        return redirect()->route('users.show', Auth::user()->username);
    }

    /**
     * Display the specified resource.
     */
    public function show(Novel $novel)
    {
        $novel = Novel::with(['tags', 'categories', 'chapters', 'user', 'image', 'boxes', 'comments.report', 'report'])->where('id', $novel->id)->first();
        $users_comments = User::with('image')->whereIn('id', $novel->comments->pluck('user_id'))->get();
        $chapters = $novel->chapters()->orderBy('title', 'asc')->paginate(
            $perPage = 10, $columns = ['*'], $pageName = 'chapters'
        );
        $comments = $novel->comments()->paginate(
            $perPage = 3, $columns = ['*'], $pageName = 'comments'
        );
        $history = null;
        $boxes_with_novels = null;
        $boxes_without_novels = null;
        $user_box = null;
        if(Auth::check())
        {
            $history = History::where('user_id', Auth::id())->where('novel_id', $novel->id)->first();
            $boxes_with_novels = Box::whereHas('novels', function ($query) use ($novel) {
                $query->where('novel_id', $novel->id);
            })->where('user_id', Auth::id())->get();
            $boxes_without_novels = Box::whereDoesntHave('novels', function ($query) use ($novel) {
                $query->where('novel_id', $novel->id);
            })->where('user_id', Auth::id())->get();
            $user_box = Box::where('user_id', Auth::id())->get();
            $user_box = $user_box->isEmpty() ? null : $user_box;
        }
        if(!Auth::check())
        {
            $user_box = null;
        }
        return view('novel.show', [
            'novel' => $novel,
            'chapters' => $chapters,
            'users_comments' => $users_comments,
            'comments' => $comments,
            'history' => $history,
            'boxes_with_novels' => $boxes_with_novels,
            'boxes_without_novels' => $boxes_without_novels,
            'user_box' => $user_box
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Novel $novel)
    {
        Gate::authorize('edit-novel', $novel);
        $novel_categories = $novel->categories()->select('category_id')->get();
        $novel_categories = Category::whereIn('id', $novel_categories->pluck('category_id'))->get();
        $novel_tags = $novel->tags()->select('tag_id')->get();
        $novel_tags = Tag::whereIn('id', $novel_tags->pluck('tag_id'))->get();
        $tags = Tag::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $novel = Novel::with(['tags', 'categories', 'chapters', 'user', 'image'])->where('id', $novel->id)->first();
        return view('novel.edit', [
            'novel' => $novel,
            'tags' => $tags,
            'categories' => $categories,
            'novel_categories' => $novel_categories,
            'novel_tags' => $novel_tags
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNovelRequest $request, Novel $novel)
    {
        Gate::authorize('update-novel', $novel);
        $validated = $request->validated();
        $picture = $request->file('picture');
        $url = $picture->hashName();
        $old_novel_image_url = $novel->image->url;
        DB::transaction(function () use ($validated, $url, $novel) {
            // gamber lebih baik di simpan lewat observer
            $novel->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'slug' => Str::slug($validated['title']).'-'.strtotime("now"),
            ]);
            $novel->image()->update([
                'url' => $url
            ]);
            $categories = Category::whereIn('name', $validated['categories'])->get();
            $tags = Tag::whereIn('name', $validated['tags'])->get();
            $novel->categories()->sync($categories->pluck('id'));
            $novel->tags()->sync($tags->pluck('id'));
            $novel->search()->update([
                'title' => $validated['title'],
                'categories' => $categories->pluck('name')->implode(','),
                'tags' => $tags->pluck('name')->implode(','),
            ]);
        });
        Storage::delete('novel/'.$old_novel_image_url);
        $request->file('picture')->storeAs('novel', $url, 'public');
        session()->flash('success', 'Successfully edit the novel');
        return redirect()->route('users.show', Auth::user()->username);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Novel $novel)
    {
        Gate::authorize('delete-novel', $novel);
        $novel = Novel::with(['image', 'chapters', 'categories', 'tags', 'comments', 'boxes'])->where('id', $novel->id)->first();
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
            $comments = $novel->comments()->get();
            foreach($comments as $comment)
            {
                $report = Report::where('reportable_type', 'App\Models\Comment')->where('reportable_id', $comment->id)->first();
                if($report != null)
                {
                    $report->votes()->delete();
                    $report->delete();
                }
            }
            $novel->comments()->delete();
    
            // chapter
            $chapters = $novel->chapters()->get();
            foreach($chapters as $chapter)
            {
                $report = Report::where('reportable_type', 'App\Models\Chapter')->where('reportable_id', $chapter->id)->first();
                if($report != null)
                {
                    $report->votes()->delete();
                    $report->delete();
                }
            }
            foreach($novel->chapters as $chapter)
            {
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
            }
            $novel->chapters()->delete();
    
            // box
            $novel->boxes()->detach();

            // report <- vote
            $report = Report::where('reportable_type', 'App\Models\Novel')->where('reportable_id', $novel->id)->first();
            if($report != null)
            {
                $report->votes()->delete();
                $report->delete();
            }

            // history
            History::where('novel_id', $novel->id)->delete();
    
            // novel
            $novel->delete();
        });
        Storage::delete('novel/'.$old_novel_image_url);
        session()->flash('success', 'Successfully delete the novel');
        return redirect()->route('users.show', Auth::user()->username);
    }

    public function search(SearchNovelRequest $request)
    {
        $request->validated();
        $input_title = $request->input('title', '');
        $input_categories = $request->input('categories', ['']);
        $input_tags = $request->input('tags', ['']);
        $search_title = collect(Str::of($input_title)->explode(' '))->implode('%');
        $search_categories = collect($input_categories)->implode('%');
        $search_tags = collect($input_tags)->implode('%');
        $search_novels = NovelCategoryTagSearch::where('title', 'like', '%' . $search_title . '%')->where('categories', 'like', '%' . $search_categories . '%')->where('tags', 'like', '%' . $search_tags . '%')->get();
        $novels = Novel::with(['tags', 'categories', 'chapters', 'user', 'image'])
            ->whereIn('id', $search_novels->pluck('novel_id'))
            ->orderBy('created_at', 'desc')
            ->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();
        return view('novel.index', [
            'novels' => $novels,
            'categories' => $categories,
            'tags' => $tags
        ]);
    }

    public function commentStore(CommentNovelStoreRequest $request, Novel $novel)
    {
        $validated = $request->validated();
        $novel->comments()->create([
            'content' => $validated['content'],
            'user_id' => Auth::id()
        ]);
        session()->flash('success', 'Successfully add the comment');
        return redirect()->route('novels.show', $novel);
    }

    public function commentDestroy(Novel $novel, Comment $comment)
    {
        Gate::authorize('delete-comment-novel', $comment);
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
        return redirect()->route('novels.show', $novel);
    }
}
