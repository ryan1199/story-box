<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\History;
use App\Models\Image;
use App\Models\Novel;
use App\Models\NovelCategoryTagSearch;
use App\Models\Report;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('custom-auth', ['except' => ['show']]);
        $this->middleware('user-exist');
        $this->middleware('throttle:global', ['except' => ['show']]);
    }
    public function show($username)
    {
        $user = User::where('username', $username)->with([
            'image', 'novels.image', 
            'novels.chapters' => function ($query) { $query->orderBy('chapters.title', 'asc'); }, 
            'novels.categories', 'novels.tags', 'novels.comments', 
            'boxes.novels' => function ($query) { $query->orderBy('novels.title', 'asc'); }, 
            'boxes.tags', 'boxes.categories', 'histories', 'report'])
            ->first();
        $histories = History::where('user_id', $user->id)->get();
        return view('user.show', [
            'user' => $user,
            'histories' => $histories,
        ]);
    }
    public function edit($username)
    {
        Gate::authorize('edit-user', $username);
        $user = User::where('username', $username)->first();
        return view('user.edit', [
            'user' => $user
        ]);
    }
    public function update(UserUpdateRequest $userUpdateRequest, $username)
    {
        Gate::authorize('update-user', $username);
        $validated = $userUpdateRequest->validated();
        $user = User::where('username', $username)->with('image')->first();
        $picture = $userUpdateRequest->file('picture');
        $url = $picture->hashName();
        $old_user_image_url = $user->image->url;
        DB::transaction(function () use ($userUpdateRequest, $user, $username, $validated, $url) {
            User::where('username', $username)->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'username' => $validated['username']
            ]);
            // $user = User::find($user);
            $user = User::where('id', $user->id)->first();
            // dd($user);
            Image::where('imageable_type', 'App\Models\User')->where('imageable_id', $user->id)->update([
                'url' => $url
            ]);
            // $user->image()->update([
            //     'url' => $url
            // ]);
        });
        Storage::delete('profile/'.$old_user_image_url);
        $userUpdateRequest->file('picture')->storeAs('profile', $url, 'public');
        $user = User::where('id', $user->id)->first();
        session()->flash('success', 'Done');
        return redirect()->route('users.show', $user->username);
    }
    public function destroy(Request $request, $username)
    {
        Gate::authorize('delete-user', $username);
        $user = User::where('username', $username)->with(['image', 'novels.image', 'novels.chapters.comments', 'novels.categories', 'novels.tags', 'novels.search', 'novels.comments', 'boxes', 'histories'])->first();
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->flash('success', 'Account deleted');
        return redirect()->route('register.view');
    }
}
