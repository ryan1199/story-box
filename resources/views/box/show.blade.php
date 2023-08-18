@extends('layout.app')
@section('title')
    {{ $box->title }}
@endsection
@section('content')
    <div class="w-full grid grid-cols-1 gap-4">
        <div class="p-4 grid grid-cols-1 gap-4 border border-gray-100 rounded-lg">
            <div class="grid grid-cols-1 gap-1">
                <h1 class="h1 text-gray-100 break-all">{{ $box->title }}</h1>
                <p class="p text-gray-100 break-all">Box by <a href="{{ route('users.show', $box->user->username) }}">{{ $box->user->username }}</a></p>
            </div>
            <div class="grid grid-cols-1 gap-1">
                <div>
                    <p class="p text-gray-100">{{ $box->description }}</p>
                </div>
                <div class="w-full h-fit flex flex-row flex-wrap justify-start items-start">
                    <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Categories</p>
                    @foreach ($box->categories as $category)
                        <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-lg break-all">{{ $category->name }}</p>
                    @endforeach
                </div>
                <div class="w-full h-fit flex flex-row flex-wrap justify-start items-start">
                    <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Tags</p>
                    @foreach ($box->tags as $category)
                        <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-lg break-all">{{ $category->name }}</p>
                    @endforeach
                </div>
                <div>
                    <p class="mb-2 p text-gray-100 text-left">Updated {{ now()->sub($box->updated_at)->diffForHumans() }}</p>
                </div>
            </div>
            @auth
                @if (Auth::id() != $box->user->id)
                    <div @auth x-data="{report: false}" @endauth class="grid grid-cols-1 gap-2">
                        <div class="grid grid-cols-1 gap-2">
                            <p><i @click="report = ! report" class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">report</i></p>
                        </div>
                        <div x-show="report" class="p-4 grid grid-cols-1 gap-2">
                            @if (collect($box->report)->isNotEmpty() == true)
                                <p class="p text-gray-100">This box already reported <a href="{{ route('reports.index') }}">check here</a></p>
                            @else
                                <x-report-form :id="$box->id" type="Box"/>
                            @endif
                        </div>
                    </div>
                @endif
            @endauth
        </div>
        <div class="p-4 grid grid-cols-1 gap-4 border border-gray-100 rounded-lg">
            <div>
                <h2 class="h2 text-gray-100">Novels</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                @forelse ($box->novels as $novel)
                    <div class="p-2 grid grid-cols-1 gap-2 border border-gray-100 rounded-lg">
                        <div class="grid grid-cols-1 gap-2">
                            <img src="{{ asset('storage/novel/'.$novel->image->url) }}" alt="{{ $novel->title }}" class="w-full border border-gray-100 rounded-lg">
                            <div class="grid grid-cols-1 gap-1">
                                <p class="p text-gray-100 break-all"><a href="{{ route('novels.show', $novel->slug) }}">{{ $novel->title }}</a></p>
                                <p class="p text-gray-100 break-all">Added by <a href="{{ route('users.show', $novel->user->username) }}">{{ $novel->user->username }}</a></p>
                                <p class="p text-gray-100">{{ count($novel->chapters) }} chapters</p>
                                <p class="p text-gray-100">Updated {{ now()->sub($novel->updated_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-1">
                            <div class="w-full h-fit flex flex-row flex-wrap justify-start items-start">
                                <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Categories</p>
                                @foreach ($novel->categories as $category)
                                    <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-lg break-all">{{ $category->name }}</p>
                                @endforeach
                            </div>
                            <div class="w-full h-fit flex flex-row flex-wrap justify-start items-start">
                                <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Tags</p>
                                @foreach ($novel->tags as $category)
                                    <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-lg break-all">{{ $category->name }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @empty
                    <div>
                        <p class="p text-gray-100">There are no novels in this box</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection