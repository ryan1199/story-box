@extends('layout.app')
@section('title')
    {{ $user->username }}
@endsection
@section('content')
    {{-- user profile --}}
    <div class="w-full max-w-lg h-fit flex flex-col space-y-2 border-2 border-gray-100 rounded-lg">
        <div @auth x-data="{open: false}" @endauth class="w-full h-fit flex flex-col justify-center items-center">
            <img src="{{ asset('storage/profile/'.$user->image->url) }}" alt="photo profile {{ $user->username }}" class="w-full">
            <div class="p-4">
                <h1 class="h1 text-gray-100 text-left break-all">{{ $user->username }} @auth <i @click="open = ! open" class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">more_vert</i> @endauth</h1>
            </div>
            @auth
                <div x-show="open" @auth x-data="{report: false}" @endauth class="p-4 pt-0 grid grid-cols-1 gap-2">
                    <div class="flex flex-row justify-center items-center space-x-2">
                        @if (Auth::user()->id == $user->id)
                            <a href="{{ route('users.edit', $user->username) }}"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">edit</i></a>
                            <form action="{{ route('users.destroy', $user->username) }}" method="post" onclick="return confirm('Delete user {{ $user->username }} ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                            </form>
                        @endif
                        @if (Auth::user()->id != $user->id)
                            <p><i @click="report = ! report" class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">report</i></p>
                        @endif
                    </div>
                    <div x-show="report" class="p-4 grid grid-cols-1 gap-2">
                        @if (collect($user->report)->isNotEmpty() == true)
                            <p class="p text-gray-100">This user already reported <a href="{{ route('reports.index') }}">check here</a></p>
                        @else
                            <x-report-form :id="$user->id" type="User"/>
                        @endif
                    </div>
                </div>
            @endauth
        </div>
    </div>
    {{-- novel --}}
    <div class="w-full h-fit mx-auto mt-4 p-4 grid grid-cols-1 gap-2 border-2 border-gray-100 rounded-lg">
        <div class="flex flex-row justify-between items-center">
            <h2 class="h2 text-gray-100 text-left">Novels</h2>
            @auth
                @if (Auth::user()->id == $user->id)
                    <a href="{{ route('novels.create') }}"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">add_circle</i></a>
                @endif
            @endauth
        </div>
        <div class="w-full h-full grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-4 grid-flow-row auto-rows-min">
            @forelse ($user->novels as $novel)
                <div class="h-fit max-h-72 flex flex-col justify-start items-start border border-gray-100 rounded-lg overflow-clip overflow-y-auto">
                    <div class="w-full h-fit p-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-1 gap-2">
                        <img src="{{ asset('storage/novel/'.$novel->image->url) }}" alt="novel cover {{ $novel->title }}" class="w-full h-fit border border-gray-100 rounded-lg">
                        <div class="w-full flex flex-row space-x-2 justify-between items-start">
                            <div class="w-full flex flex-col space-y-2 justify-center items-start">
                                <p class="p text-gray-100 text-left"><a href="{{ route('novels.show', $novel) }}">{{ $novel->title }}</a></p>
                                <p class="p text-gray-100 text-left break-all">Added by <a href="{{ route('users.show', $user->username) }}">{{ $user->username }}</a></p>
                                <p class="p text-gray-100 text-left">{{ count($novel->chapters) }} chapters</p>
                                <p class="p text-gray-100 text-left">Updated {{ now()->sub($novel->updated_at)->diffForHumans() }}</p>
                            </div>
                            @auth
                                @if (Auth::user()->id == $user->id && Auth::user()->id == $novel->user_id)
                                    <div class="w-fit flex flex-row justify-between items-center">
                                        <div class="w-fit h-fit flex flex-col justify-between items-center space-y-2">
                                            <a href="{{ route('novels.edit', $novel) }}"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">edit</i></a>
                                            <form action="{{ route('novels.destroy', $novel) }}" method="post" onclick="return confirm('Delete novel {{ $novel->title }} ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                    <div class="w-full h-fit p-2 pt-1 flex flex-col">
                        <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-start items-start">
                            <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Categories</p>
                            @foreach ($novel->categories as $category)
                                <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-3xl">{{ $category->name }}</p>
                            @endforeach
                        </div>
                        <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-start items-start">
                            <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Tags</p>
                            @foreach ($novel->tags as $tag)
                                <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-3xl">{{ $tag->name }}</p>
                            @endforeach
                        </div>
                        <div class="w-full h-fit flex flex-row flex-wrap justify-start items-start scroll-py-2">
                            <div class="w-full flex flex-row justify-between items-center space-x-2">
                                <p class="p text-gray-100">Chapters</p>
                                @auth
                                    @if (Auth::user()->id == $user->id)
                                        <a href="{{ route('chapters.create', $novel) }}"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">add_circle</i></a>
                                    @endif
                                @endauth
                            </div>
                            @forelse ($novel->chapters as $chapter)
                                <div class="w-full flex flex-row justify-between items-center space-x-2">
                                    <div>
                                        <p class="p text-gray-100">{{ $chapter->title }}</p>
                                    </div>
                                    @auth
                                        @if (Auth::user()->id == $user->id)
                                            <div class="flex flex-row justify-between items-center space-x-2">
                                                <a href="{{ route('chapters.edit', [$novel, $chapter]) }}"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">edit</i></a>
                                                <form action="{{ route('chapters.destroy', [$novel, $chapter]) }}" method="post" onclick="return confirm('Delete chapter {{ $chapter->title }} ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @empty
                                <div>
                                    <p class="p text-gray-100 text-left">There are no chapters on this novel</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div>
                    <p class="p text-gray-100 text-left">There are no novels on this user</p>
                </div>
            @endforelse
        </div>
    </div>
    {{-- box --}}
    <div class="w-full h-fit mx-auto mt-4 p-4 grid grid-cols-1 gap-2 border-2 border-gray-100 rounded-lg">
        <div class="flex flex-row justify-between items-center">
            <h2 class="h2 text-gray-100 text-left">Boxes</h2>
            @auth
                @if (Auth::user()->id == $user->id)
                    <a href="{{ route('boxes.create') }}"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">add_circle</i></a>
                @endif
            @endauth
        </div>
        <div class="w-full h-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 grid-flow-row auto-rows-min">
            @forelse ($user->Boxes as $box)
                @auth
                    @if (Auth::user()->id == $user->id)
                        <div class="h-fit max-h-72 flex flex-col justify-start items-start border border-gray-100 rounded-lg overflow-clip overflow-y-auto">
                            <div class="w-full h-fit p-2 flex flex-col">
                                <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-between items-center">
                                    <p class="p text-gray-100"><a href="{{ route('boxes.show', $box) }}">{{ $box->title }}</a></p>
                                    <div class="w-fit h-fit flex flex-row justify-between items-center space-x-2">
                                        <a href="{{ route('boxes.edit', $box) }}"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">edit</i></a>
                                        <form action="{{ route('boxes.destroy', $box) }}" method="post" onclick="return confirm('Delete box {{ $box->title }} ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                        </form>
                                    </div>
                                </div>
                                <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-start items-start">
                                    <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Categories</p>
                                    @foreach ($box->categories as $category)
                                        <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-3xl">{{ $category->name }}</p>                                
                                    @endforeach
                                </div>
                                <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-start items-start">
                                    <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Tags</p>
                                    @foreach ($box->tags as $tag)
                                        <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-3xl">{{ $tag->name }}</p>                                
                                    @endforeach
                                </div>
                            </div>
                            <div class="w-full h-fit p-2 pt-1 flex flex-col">
                                @forelse ($box->novels as $novel)
                                    <div class="w-full p-2 flex flex-row space-x-2 justify-between items-center border border-gray-100 rounded-lg">
                                        <p class="p text-gray-100"><a href="{{ route('novels.show', $novel) }}">{{ $novel->title }}</a></p>
                                        <form action="{{ route('boxes.novels.remove', [$box, $novel]) }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" ><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                        </form>
                                    </div>
                                @empty
                                    <div>
                                        <p class="p text-gray-100">There are no novels in this box</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @else
                        @if ($box->visible == 'Public')
                            <div class="h-fit max-h-72 flex flex-col justify-start items-start border border-gray-100 rounded-lg overflow-clip overflow-y-auto">
                                <div class="w-full h-fit p-2 flex flex-col">
                                    <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-between items-center">
                                        <p class="p text-gray-100"><a href="{{ route('boxes.show', $box) }}">{{ $box->title }}</a></p>
                                    </div>
                                    <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-start items-start">
                                        <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Categories</p>
                                        @foreach ($box->categories as $category)
                                            <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-3xl">{{ $category->name }}</p>                                
                                        @endforeach
                                    </div>
                                    <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-start items-start">
                                        <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Tags</p>
                                        @foreach ($box->tags as $tag)
                                            <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-3xl">{{ $tag->name }}</p>                                
                                        @endforeach
                                    </div>
                                </div>
                                <div class="w-full h-fit p-2 pt-1 flex flex-col">
                                    @forelse ($box->novels as $novel)
                                        <div class="w-full p-2 flex flex-row space-x-2 justify-between items-center border border-gray-100 rounded-lg">
                                            <p class="p text-gray-100"><a href="{{ route('novels.show', $novel) }}">{{ $novel->title }}</a></p>
                                        </div>
                                    @empty
                                        <div>
                                            <p class="p text-gray-100">There are no novels in this box</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif
                    @endif
                @endauth
                @guest
                    @if ($box->visible == 'Public')
                        <div class="h-fit max-h-72 flex flex-col justify-start items-start border border-gray-100 rounded-lg overflow-clip overflow-y-auto">
                            <div class="w-full h-fit p-2 flex flex-col">
                                <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-between items-center">
                                    <p class="p text-gray-100"><a href="{{ route('boxes.show', $box) }}">{{ $box->title }}</a></p>
                                </div>
                                <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-start items-start">
                                    <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Categories</p>
                                    @foreach ($box->categories as $category)
                                        <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-3xl">{{ $category->name }}</p>                                
                                    @endforeach
                                </div>
                                <div class="w-full h-fit mb-2 flex flex-row flex-wrap justify-start items-start">
                                    <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Tags</p>
                                    @foreach ($box->tags as $tag)
                                        <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-3xl">{{ $tag->name }}</p>                                
                                    @endforeach
                                </div>
                            </div>
                            <div class="w-full h-fit p-2 pt-1 flex flex-col">
                                @forelse ($box->novels as $novel)
                                    <div class="w-full p-2 flex flex-row space-x-2 justify-between items-center border border-gray-100 rounded-lg">
                                        <p class="p text-gray-100"><a href="{{ route('novels.show', $novel) }}">{{ $novel->title }}</a></p>
                                    </div>
                                @empty
                                    <div>
                                        <p class="p text-gray-100">There are no novels in this box</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endif
                @endguest
            @empty
                <div>
                    <p class="p text-gray-100 text-left">There are no boxes on this user</p>
                </div>
            @endforelse
        </div>
    </div>
    @auth
        @if (Auth::user()->id == $user->id)
            {{-- history --}}
            <div class="w-full h-fit grid grid-cols-1">
                <div>
                    <h2 class="h2 text-gray-100 text-left">History</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @forelse ($histories as $history)
                        <div class="grid grid-cols-1 gap-2 p-2 border border-gray-100 rounded-lg">
                            <p class="p text-gray-100"><a href="{{ route('novels.show', $history->novel_slug) }}">{{ $history->novel_title }}</a></p>
                            <p class="p text-gray-100"><a href="{{ route('chapters.show', [$history->novel_slug, $history->chapter_slug]) }}">{{ $history->chapter_title }}</a></p>
                        </div>
                    @empty
                        <div>
                            <p class="p text-gray-100">There are no histories on this user</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    @endauth
@endsection