@extends('layout.app')
@section('title')
    Read {{ $chapter->title }}
@endsection
@section('content')
    <div class="w-full grid grid-cols-1 gap-4">
        <div class="grid grid-cols-1 gap-4">
            <h1 class="h1 text-gray-100"><a href="{{ route('novels.show', $novel) }}">{{ $novel->title }}</a></h1>
            <p class="p text-gray-100">{{ $chapter->title }}</p>
        </div>
        <div class="grid grid-cols-1 gap-4">
            <p class="p text-gray-100">{{ $chapter->content }}</p>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <div class="justify-self-start">
                @if ($prev != null)
                    <a href="{{ route('chapters.show', [$novel, $prev]) }}" class="p text-gray-950 p-4 bg-gray-100 hover:bg-gray-100/90 active:bg-gray-100/80 border border-gray-100 rounded-lg">Prev</a>
                @else
                    <p class="p text-gray-100 p-4 border border-gray-100 rounded-lg">Prev</p>
                @endif
            </div>
            <div class="justify-self-end">
                @if ($next != null)
                    <a href="{{ route('chapters.show', [$novel, $next]) }}" class="p text-gray-950 p-4 bg-gray-100 hover:bg-gray-100/90 active:bg-gray-100/80 border border-gray-100 rounded-lg">Next</a>
                @else
                    <p class="p text-gray-100 p-4 border border-gray-100 rounded-lg">Next</p>
                @endif
            </div>
        </div>
        @auth
            @if (Auth::id() != $novel->user->id)
                <div @auth x-data="{report: false}" @endauth class="grid grid-cols-1 gap-2">
                    <div class="grid grid-cols-1 gap-2">
                        <p><i @click="report = ! report" class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">report</i></p>
                    </div>
                    <div x-show="report" class="p-4 grid grid-cols-1 gap-2">
                        @if (collect($chapter->report)->isNotEmpty() == true)
                            <p class="p text-gray-100">This chapter already reported <a href="{{ route('reports.index') }}">check here</a></p>
                        @else
                            <x-report-form :id="$chapter->id" type="Chapter"/>
                        @endif
                    </div>
                </div>
            @endif
        @endauth
        <div class="grid grid-cols-1 gap-4">
            <div>
                <h2 class="h2 text-gray-100">Comments</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @auth
                    <div class="w-full">
                        <form action="{{ route('chapters.comments.store', [$novel, $chapter]) }}" method="post" class="w-full h-fit p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-lg">
                            @csrf
                            @method('POST')
                            <label for="content" class="form-lable w-full flex flex-col justify-between items-center">
                                <span class="w-full form-span">Content</span>
                                <textarea name="content" id="content" cols="30" rows="5">{{ old('content') }}</textarea>
                            </label>
                            @error('content')
                                <div>
                                    <span class="form-error">{{ $message }}</span>
                                </div>
                            @enderror
                            <button type="submit" class="form-button w-full h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Comment</button>
                        </form>
                    </div>
                @endauth
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 content-start">
                    @forelse ($chapter->comments as $comment)
                        <div class="grid grid-cols-1 gap-1 justify-items-start content-start place-content-start place-items-start overflow-y-auto">
                            @foreach ($users_comments as $users_comment)
                                @if ($comment->user_id == $users_comment->id)
                                    <div class="place-self-start">
                                        <p class="p text-gray-100"><a href="{{ route('users.show', $users_comment->username) }}">{{ $users_comment->username }}</a></p>
                                    </div>
                                    <div class="grid grid-cols-1 gap-2 place-self-start justify-items-start content-start place-content-start place-items-start">
                                        <p class="p text-gray-100 col-span-1">{{ $comment->content }}</p>
                                        @auth
                                            <div class="grid grid-cols-10 gap-2 place-content-center">
                                                @if (Auth::user()->id == $users_comment->id)
                                                    <div class="col-span-1 grid grid-cols-1 gap-2">
                                                        <form action="{{ route('chapters.comments.destroy', [$novel, $chapter, $comment]) }}" method="post" onclick="return confirm('Delete comment {{ $comment->content }} ?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                                        </form>
                                                    </div>
                                                @endif
                                                @if (Auth::id() != $comment->user_id)
                                                    <div @auth x-data="{report: false}" @endauth class="col-span-9 grid grid-cols-1 gap-2">
                                                        <div class="grid grid-cols-1 gap-2">
                                                            <p><i @click="report = ! report" class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">report</i></p>
                                                        </div>
                                                        <div x-show="report" class="p-4 grid grid-cols-1 gap-2">
                                                            @if (collect($comment->report)->isNotEmpty() == true)
                                                                <p class="p text-gray-100">This comment already reported <a href="{{ route('reports.index') }}">check here</a></p>
                                                            @else
                                                                <x-report-form :id="$comment->id" type="Comment"/>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endauth
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @empty
                        <div>
                            <p class="p text-gray-100">There are no comments on this chapter</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection