<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Http\Requests\StoreBoxRequest;
use App\Http\Requests\UpdateBoxRequest;
use App\Models\BoxNovel;
use App\Models\Category;
use App\Models\Novel;
use App\Models\Report;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class BoxController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom-auth', ['except' => ['index', 'show']]);
        $this->middleware('box-exist', ['except' => ['index', 'create', 'store']]);
        $this->middleware('novel-exist', ['only' => ['add', 'remove']]);
        $this->middleware('throttle:global', ['except' => ['index', 'show', 'add', 'remove']]);
        $this->middleware('not-reported:App\Models\Box', ['only' => ['edit', 'destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boxes = Box::with(['user', 'novels', 'categories', 'tags'])->where('visible', 'Public')->orderBy('title', 'asc')->get();
        return view('box.index', [
            'boxes' => $boxes
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tags = Tag::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('box.create', [
            'tags' => $tags,
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBoxRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('id', Auth::id())->first();
        DB::transaction(function () use ($validated, $user) {
            $box = $user->boxes()->create([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']).'-'.strtotime("now"),
                'description' => $validated['description'],
                'visible' => $validated['visible']
            ]);
            $tags = Tag::whereIn('name', $validated['tags'])->get();
            $categories = Category::whereIn('name', $validated['categories'])->get();
            $box->tags()->attach($tags->pluck('id'));
            $box->categories()->attach($categories->pluck('id'));
        });
        session()->flash('success', 'Successfully add the box');
        return redirect()->route('users.show', Auth::user()->username);
    }

    /**
     * Display the specified resource.
     */
    public function show(Box $box)
    {
        $box = Box::with(['user', 'novels' => function ($query) { $query->orderBy('title', 'asc'); }, 'novels.image', 'novels.tags', 'novels.categories', 'novels.user', 'tags', 'categories', 'report'])->where('id', $box->id)->first();
        return view('box.show', [
            'box' => $box,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Box $box)
    {
        Gate::authorize('edit-box', $box);
        $box_categories = $box->categories()->select('category_id')->get();
        $box_categories = Category::whereIn('id', $box_categories->pluck('category_id'))->get();
        $box_tags = $box->tags()->select('tag_id')->get();
        $box_tags = Tag::whereIn('id', $box_tags->pluck('tag_id'))->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $tags = Tag::orderBy('name', 'asc')->get();
        $box = Box::with(['user', 'categories', 'tags'])->where('id', $box->id)->first();
        return view('box.edit', [
            'box_categories' => $box_categories,
            'box_tags' => $box_tags,
            'tags' => $tags,
            'categories' => $categories,
            'box' => $box
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBoxRequest $request, Box $box)
    {
        Gate::authorize('update-box', $box);
        $validated = $request->validated();
        DB::transaction(function () use ($validated, $box) {
            $box->update([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']).'-'.strtotime("now"),
                'description' => $validated['description'],
                'visible' => $validated['visible']
            ]);
            $tags = Tag::whereIn('name', $validated['tags'])->get();
            $categories = Category::whereIn('name', $validated['categories'])->get();
            $box->tags()->sync($tags->pluck('id'));
            $box->categories()->sync($categories->pluck('id'));
            $box->touch();
        });
        session()->flash('success', 'Successfully edit the box');
        return redirect()->route('users.show', Auth::user()->username);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Box $box)
    {
        Gate::authorize('delete-box', $box);
        DB::transaction(function () use ($box) {
            $box->tags()->detach();
            $box->categories()->detach();
            $box->novels()->detach();
            $report = Report::where('reportable_type', 'App\Models\Box')->where('reportable_id', $box->id)->first();
            if($report != null)
            {
                $report->votes()->delete();
                $report->delete();
            }
            $box->delete();
        });
        session()->flash('success', 'Successfully delete the box');
        return redirect()->route('users.show', Auth::user()->username);
    }

    public function add(Box $box, Novel $novel)
    {
        Gate::authorize('add-to-box', $box);
        $box->novels()->attach([
            'novel_id' => $novel->id
        ]);
        $box->touch();
        session()->flash('success', 'Successfully add the novel to '.$box->title);
        return redirect()->route('novels.show', $novel);
    }

    public function remove(Box $box, Novel $novel)
    {
        Gate::authorize('remove-from-box', $box);
        if(BoxNovel::where('box_id', $box->id)->where('novel_id', $novel->id)->first() != null)
        {
            $box->novels()->detach([
                'novel_id', $novel->id
            ]);
            $box->touch();
            session()->flash('success', 'Successfully remove the novel from '.$box->title);
            return redirect()->route('users.show', Auth::user()->username);
        }
        session()->flash('The novel is not in your box');
        return redirect()->route('users.show', Auth::user()->username);
    }
}
