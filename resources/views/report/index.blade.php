@extends('layout.app')
@section('title')
    List of reported by user
@endsection
@section('content')
    @auth
        <div class="w-full grid grid-cols-1 gap-2">
            <div class="max-w-lg grid grid-cols-1 gap-2">
                <h1 class="h1 text-gray-100">All report by user</h1>
                <p class="p text-gray-100">List of all issues that bother users</p>
                <p class="p text-red-500">Notes: About voting, all of the users must vote before the system decides to delete or not, and if the result is tied, a random decision will be applied</p>
            </div>
            {{-- jika sedang direport tidak bisa di edit atau delete --}}
            <div class="grid grid-cols-1 gap-2">
                {{-- user --}}
                <div class="grid grid-cols-1 gap-2">
                    <div>
                        <h2 class="h2 text-gray-100">Users</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                        @forelse ($users_reported as $user_reported)
                            <div class="h-fit p-2 grid grid-cols-1 gap-2 border border-gray-100 rounded-lg divide-y-2 divide-gray-100">
                                <div class="grid grid-cols-1 gap-2">
                                    <p class="p text-gray-100 break-all"><a href="{{ route('users.show', $user_reported->username) }}">{{ $user_reported->username }}</a></p>
                                    <p class="p text-gray-100 break-all">{{ $user_reported->report->reason }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        <p class="p text-gray-100 break-all">Reported by <a href="{{ route('users.show', $user_reported->report->user->username) }}">{{ $user_reported->report->user->username }}</a></p>
                                        @if (Auth::id() == $user_reported->report->user->id)
                                            <form action="{{ route('reports.remove', [$user_reported->id, 'User']) }}" method="post" onclick="return confirm('Delete this report ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="p text-gray-100">Status {{ $user_reported->report->status }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        @if ($user_reported->report->votes->count() >= 0)
                                            @if ($user_reported->report->votes->pluck('user_id')->contains(Auth::id()))
                                                @foreach ($user_reported->report->votes as $vote)
                                                    @if ($vote->user_id == Auth::id())
                                                        <div>
                                                            <p class="p text-gray-100">You are already vote</p>
                                                            <p class="p text-gray-100">
                                                                Your vote is 
                                                                @if ($vote->accepted == true)
                                                                    accept
                                                                @else
                                                                    reject
                                                                @endif
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <form action="{{ route('votes.reports.accept', [$user_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Accept</button>
                                                </form>
                                                <form action="{{ route('votes.reports.reject', [$user_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Reject</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-2">
                                    <p class="p text-gray-100">Votes</p>
                                    <p class="p text-gray-100">{{ $user_reported->report->votes->count() }}/{{ $users->count() }} users voted</p>
                                    @if ($user_reported->report->votes->count() > 0)
                                        <div class="flex flex-row space-x-2 justify-between">
                                            <p class="p text-gray-100">Accepted {{ $user_reported->report->votes->whereNotNull('accepted')->count() }}</p>
                                            <p class="p text-gray-100">Rejected {{ $user_reported->report->votes->whereNotNull('rejected')->count() }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div>
                                <p class="p text-gray-100">No users were reported, this is good</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                {{-- novel --}}
                <div class="grid grid-cols-1 gap-2">
                    <div>
                        <h2 class="h2 text-gray-100">Novels</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                        @forelse ($novels_reported as $novel_reported)
                            <div class="h-fit p-2 grid grid-cols-1 gap-2 border border-gray-100 rounded-lg divide-y-2 divide-gray-100">
                                <div class="grid grid-cols-1 gap-2">
                                    <p class="p text-gray-100 break-all"><a href="{{ route('novels.show', $novel_reported->slug) }}">{{ $novel_reported->title }}</a></p>
                                    <p class="p text-gray-100 break-all">{{ $novel_reported->report->reason }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        <p class="p text-gray-100 break-all">Reported by <a href="{{ route('users.show', $novel_reported->report->user->username) }}">{{ $novel_reported->report->user->username }}</a></p>
                                        @if (Auth::id() == $novel_reported->report->user->id)
                                            <form action="{{ route('reports.remove', [$novel_reported->id, 'Novel']) }}" method="post" onclick="return confirm('Delete this report ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="p text-gray-100">Status {{ $novel_reported->report->status }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        @if ($novel_reported->report->votes->count() >= 0)
                                            @if ($novel_reported->report->votes->pluck('user_id')->contains(Auth::id()))
                                                @foreach ($novel_reported->report->votes as $vote)
                                                    @if ($vote->user_id == Auth::id())
                                                        <div>
                                                            <p class="p text-gray-100">You are already vote</p>
                                                            <p class="p text-gray-100">
                                                                Your vote is 
                                                                @if ($vote->accepted == true)
                                                                    accept
                                                                @else
                                                                    reject
                                                                @endif
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <form action="{{ route('votes.reports.accept', [$novel_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Accept</button>
                                                </form>
                                                <form action="{{ route('votes.reports.reject', [$novel_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Reject</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-2">
                                    <p class="p text-gray-100">Votes</p>
                                    <p class="p text-gray-100">{{ $novel_reported->report->votes->count() }}/{{ $users->count() }} users voted</p>
                                    @if ($novel_reported->report->votes->count() > 0)
                                        <div class="flex flex-row space-x-2 justify-between">
                                            <p class="p text-gray-100">Accepted {{ $novel_reported->report->votes->whereNotNull('accepted')->count() }}</p>
                                            <p class="p text-gray-100">Rejected {{ $novel_reported->report->votes->whereNotNull('rejected')->count() }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div>
                                <p class="p text-gray-100">No novels were reported, this is good</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                {{-- chapter --}}
                <div class="grid grid-cols-1 gap-2">
                    <div>
                        <h2 class="h2 text-gray-100">Chapters</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                        @forelse ($chapters_reported as $chapter_reported)
                            <div class="h-fit p-2 grid grid-cols-1 gap-2 border border-gray-100 rounded-lg divide-y-2 divide-gray-100">
                                <div class="grid grid-cols-1 gap-2">
                                    <p class="p text-gray-100 break-all"><a href="{{ route('novels.show', $chapter_reported->novel->slug) }}">{{ $chapter_reported->novel->title }}</a></p>
                                    <p class="p text-gray-100 break-all"><a href="{{ route('chapters.show', [$chapter_reported->novel->slug, $chapter_reported->slug]) }}">{{ $chapter_reported->title }}</a></p>
                                    <p class="p text-gray-100 break-all">{{ $chapter_reported->report->reason }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        <p class="p text-gray-100 break-all">Reported by <a href="{{ route('users.show', $chapter_reported->report->user->username) }}">{{ $chapter_reported->report->user->username }}</a></p>
                                        @if (Auth::id() == $chapter_reported->report->user->id)
                                            <form action="{{ route('reports.remove', [$chapter_reported->id, 'Chapter']) }}" method="post" onclick="return confirm('Delete this report ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="p text-gray-100">Status {{ $chapter_reported->report->status }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        @if ($chapter_reported->report->votes->count() >= 0)
                                            @if ($chapter_reported->report->votes->pluck('user_id')->contains(Auth::id()))
                                                @foreach ($chapter_reported->report->votes as $vote)
                                                    @if ($vote->user_id == Auth::id())
                                                        <div>
                                                            <p class="p text-gray-100">You are already vote</p>
                                                            <p class="p text-gray-100">
                                                                Your vote is 
                                                                @if ($vote->accepted == true)
                                                                    accept
                                                                @else
                                                                    reject
                                                                @endif
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <form action="{{ route('votes.reports.accept', [$chapter_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Accept</button>
                                                </form>
                                                <form action="{{ route('votes.reports.reject', [$chapter_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Reject</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-2">
                                    <p class="p text-gray-100">Votes</p>
                                    <p class="p text-gray-100">{{ $chapter_reported->report->votes->count() }}/{{ $users->count() }} users voted</p>
                                    @if ($chapter_reported->report->votes->count() > 0)
                                        <div class="flex flex-row space-x-2 justify-between">
                                            <p class="p text-gray-100">Accepted {{ $chapter_reported->report->votes->whereNotNull('accepted')->count() }}</p>
                                            <p class="p text-gray-100">Rejected {{ $chapter_reported->report->votes->whereNotNull('rejected')->count() }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div>
                                <p class="p text-gray-100">No chapters were reported, this is good</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                {{-- boxes --}}
                <div class="grid grid-cols-1 gap-2">
                    <div>
                        <h2 class="h2 text-gray-100">Boxes</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                        @forelse ($boxes_reported as $box_reported)
                            <div class="h-fit p-2 grid grid-cols-1 gap-2 border border-gray-100 rounded-lg divide-y-2 divide-gray-100">
                                <div class="grid grid-cols-1 gap-2">
                                    <p class="p text-gray-100 break-all"><a href="{{ route('boxes.show', $box_reported->slug) }}">{{ $box_reported->title }}</a></p>
                                    <p class="p text-gray-100 break-all">{{ $box_reported->report->reason }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        <p class="p text-gray-100 break-all">Reported by <a href="{{ route('users.show', $box_reported->report->user->username) }}">{{ $box_reported->report->user->username }}</a></p>
                                        @if (Auth::id() == $box_reported->report->user->id)
                                            <form action="{{ route('reports.remove', [$box_reported->id, 'Box']) }}" method="post" onclick="return confirm('Delete this report ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="p text-gray-100">Status {{ $box_reported->report->status }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        @if ($box_reported->report->votes->count() >= 0)
                                            @if ($box_reported->report->votes->pluck('user_id')->contains(Auth::id()))
                                                @foreach ($box_reported->report->votes as $vote)
                                                    @if ($vote->user_id == Auth::id())
                                                        <div>
                                                            <p class="p text-gray-100">You are already vote</p>
                                                            <p class="p text-gray-100">
                                                                Your vote is 
                                                                @if ($vote->accepted == true)
                                                                    accept
                                                                @else
                                                                    reject
                                                                @endif
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <form action="{{ route('votes.reports.accept', [$box_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Accept</button>
                                                </form>
                                                <form action="{{ route('votes.reports.reject', [$box_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Reject</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-2">
                                    <p class="p text-gray-100">Votes</p>
                                    <p class="p text-gray-100">{{ $box_reported->report->votes->count() }}/{{ $users->count() }} users voted</p>
                                    @if ($box_reported->report->votes->count() > 0)
                                        <div class="flex flex-row space-x-2 justify-between">
                                            <p class="p text-gray-100">Accepted {{ $box_reported->report->votes->whereNotNull('accepted')->count() }}</p>
                                            <p class="p text-gray-100">Rejected {{ $box_reported->report->votes->whereNotNull('rejected')->count() }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div>
                                <p class="p text-gray-100">No boxes were reported, this is good</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                {{-- comment --}}
                <div class="grid grid-cols-1 gap-2">
                    <div>
                        <h2 class="h2 text-gray-100">Comments</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                        @forelse ($comments_reported as $comment_reported)
                            <div class="h-fit p-2 grid grid-cols-1 gap-2 border border-gray-100 rounded-lg divide-y-2 divide-gray-100">
                                <div class="grid grid-cols-1 gap-2">
                                    <div class="max-h-40 overflow-y-auto">
                                        <p class="p text-gray-100 break-all">{{ $comment_reported->content }}</p>
                                    </div>
                                    <p class="p text-gray-100 break-all">Comment by <a href="{{ route('users.show', $comment_reported->user->username) }}">{{ $comment_reported->user->username }}</a></p>
                                    <p class="p text-gray-100 break-all">{{ $comment_reported->report->reason }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        <p class="p text-gray-100 break-all">Reported by <a href="{{ route('users.show', $comment_reported->report->user->username) }}">{{ $comment_reported->report->user->username }}</a></p>
                                        @if (Auth::id() == $comment_reported->report->user->id)
                                            <form action="{{ route('reports.remove', [$comment_reported->id, 'Comment']) }}" method="post" onclick="return confirm('Delete this report ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="p text-gray-100">Status {{ $comment_reported->report->status }}</p>
                                    <div class="flex flex-row space-x-2 justify-between">
                                        @if ($comment_reported->report->votes->count() >= 0)
                                            @if ($comment_reported->report->votes->pluck('user_id')->contains(Auth::id()))
                                                @foreach ($comment_reported->report->votes as $vote)
                                                    @if ($vote->user_id == Auth::id())
                                                        <div>
                                                            <p class="p text-gray-100">You are already vote</p>
                                                            <p class="p text-gray-100">
                                                                Your vote is 
                                                                @if ($vote->accepted == true)
                                                                    accept
                                                                @else
                                                                    reject
                                                                @endif
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <form action="{{ route('votes.reports.accept', [$comment_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Accept</button>
                                                </form>
                                                <form action="{{ route('votes.reports.reject', [$comment_reported->report->id, Auth::user()]) }}" method="post">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit" class="form-button w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Reject</button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-2">
                                    <p class="p text-gray-100">Votes</p>
                                    <p class="p text-gray-100">{{ $comment_reported->report->votes->count() }}/{{ $users->count() }} users voted</p>
                                    @if ($comment_reported->report->votes->count() > 0)
                                        <div class="flex flex-row space-x-2 justify-between">
                                            <p class="p text-gray-100">Accepted {{ $comment_reported->report->votes->whereNotNull('accepted')->count() }}</p>
                                            <p class="p text-gray-100">Rejected {{ $comment_reported->report->votes->whereNotNull('rejected')->count() }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div>
                                <p class="p text-gray-100">No comments were reported, this is good</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endauth
@endsection