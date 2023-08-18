@extends('layout.app')
@section('title')
    {{ $novel->title }}
@endsection
@section('content')
    <div class="w-full h-fit grid grid-cols-1 lg:grid-cols-1 gap-4 grid-flow-row auto-rows-min">
        <div class="grid grid-cols-1 gap-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <img src="{{ asset('storage/novel/'.$novel->image->url) }}" alt="{{ $novel->title }}">
                <div @if ($boxes_with_novels != null && $boxes_without_novels != null && $user_box != null) x-data="{ addBox: false }" @endif class="flex flex-col">
                    <h1 class="h1 text-gray-100 text-left break-all">{{ $novel->title }}</h1>
                    <p class="mb-2 p text-gray-100 text-left">{{ count($novel->chapters) }} chapters</p>
                    <p class="mb-2 p text-gray-100 text-left">Updated {{ now()->sub($novel->updated_at)->diffForHumans() }}</p>
                    <p class="mb-2 p text-gray-100 text-left break-all">Added by <a href="{{ route('users.show', $novel->user->username) }}">{{ $novel->user->username }}</a></p>
                    @auth
                        <div class="flex flex-row space-x-2 justify-start items-start">
                            @if ($boxes_with_novels != null && $boxes_without_novels != null && $user_box != null)
                                <p @click="addBox = ! addBox"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-sm">add_box</i></p>
                            @endif
                            @if (Auth::id() != $novel->user_id)
                                <div @auth x-data="{report: false}" @endauth class="grid grid-cols-1 gap-2">
                                    <div class="grid grid-cols-1 gap-2">
                                        <p><i @click="report = ! report" class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">report</i></p>
                                    </div>
                                    <div x-show="report" class="p-4 grid grid-cols-1 gap-2">
                                        @if (collect($novel->report)->isNotEmpty() == true)
                                            <p class="p text-gray-100">This novel already reported <a href="{{ route('reports.index') }}">check here</a></p>
                                        @else
                                            <x-report-form :id="$novel->id" type="Novel"/>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                        @if ($boxes_with_novels != null && $boxes_without_novels != null && $user_box != null)
                            <div x-show="addBox" class="w-full flex flex-col space-y-2">
                                @foreach ($boxes_with_novels as $box)
                                    <div>
                                        <form action="{{ route('boxes.novels.remove', [$box, $novel]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="form-button p text-gray-100">Remove from box {{ $box->title }}</button>
                                        </form>
                                    </div>
                                @endforeach
                                @foreach ($boxes_without_novels as $box)
                                    <div>
                                        <form action="{{ route('boxes.novels.add', [$box, $novel]) }}" method="post">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="form-button p text-gray-100">Add to box {{ $box->title }}</button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if ($history != null)
                            <div class="mt-2 grid grid-cols-1 gap-2">
                                <p class="p text-gray-100">Last read</p>
                                <a href="{{ route('chapters.show', [$history->novel_slug, $history->chapter_slug]) }}" class="p text-gray-100 p-2 border border-gray-100 rounded-lg justify-self-start">{{ $history->chapter_title }}</a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div class="w-full h-fit">
                    <p class="p text-gray-100 break-all">{{ $novel->description }}</p>
                </div>
                <div class="w-full h-fit flex flex-row flex-wrap justify-start items-start">
                    <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Categories</p>
                    @foreach ($novel->categories as $category)
                        <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-lg break-all">{{ $category->name }}</p>
                    @endforeach
                </div>
                <div class="w-full h-fit flex flex-row flex-wrap justify-start items-start">
                    <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Tags</p>
                    @foreach ($novel->tags as $tag)
                        <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-lg break-all">{{ $tag->name }}</p>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            {{-- chapter --}}
            <div class="md:col-span-2 grid grid-cols-1 gap-4 content-start">
                <div>
                    <h2 class="h2 text-gray-100 text-left">Chapters</h2>
                </div>
                @forelse ($chapters as $chapter)
                    <div>
                        <p class="p text-gray-100 break-all"><a href="{{ route('chapters.show', [$novel, $chapter]) }}">{{ $chapter->title }}</a></p>
                    </div>
                @empty
                    <div>
                        <p class="p text-gray-100">There are no chapters on this novel</p>
                    </div>
                @endforelse
                @if ($chapters->hasPages())
                    <div class="w-full p-4 bg-gray-100 rounded-lg">
                        {{ $chapters->links() }}
                    </div>
                @endif
            </div>
            {{-- comment --}}
            <div class="md:col-span-4 grid grid-cols-1 gap-4">
                <div>
                    <h2 class="h2 text-gray-100 text-left">Comments</h2>
                </div>
                @auth
                    <div class="w-full">
                        <form action="{{ route('novels.comments.store', $novel) }}" method="post" class="w-full h-fit p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-lg">
                            @csrf
                            @method('POST')
                            <label for="content" class="form-lable w-full flex flex-col justify-between items-center">
                                <span class="w-full form-span">Content</span>
                                <textarea name="content" id="content" cols="30" rows="5">{{ old('content') }}</textarea>
                            </label>
                            <button type="submit" class="form-button w-full h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Comment</button>
                        </form>
                    </div>
                @endauth
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse ($comments as $comment)
                        <div class="grid grid-cols-1 gap-1 justify-items-stretch content-start place-content-stretch place-items-stretch overflow-y-auto">
                            @foreach ($users_comments as $users_comment)
                                @if ($comment->user_id == $users_comment->id)
                                    <div class="place-self-start">
                                        <p class="p text-gray-100 break-all"><a href="{{ route('users.show', $users_comment->username) }}">{{ $users_comment->username }}</a></p>
                                    </div>
                                    <div class="grid grid-cols-1 gap-2 content-stretch">
                                        <p class="p text-gray-100 break-all">{{ $comment->content }}</p>
                                        <div class="w-full grid grid-cols-10 gap-2 place-content-start">
                                            @auth
                                                @if (Auth::user()->id == $users_comment->id)
                                                    <div class="col-span-1 grid grid-cols-1 gap-2">
                                                        <form action="{{ route('novels.comments.destroy', [$novel, $comment]) }}" method="post" onclick="return confirm('Delete comment {{ $comment->content }} ?')">
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
                                            @endauth
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @empty
                        <div>
                            <p class="p text-gray-100">There are no comments on this novel</p>
                        </div>
                    @endforelse
                    @if ($comments->hasPages())
                        <div class="md:col-span-3 w-full p-2 bg-gray-100 rounded-lg">
                            {{ $comments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection