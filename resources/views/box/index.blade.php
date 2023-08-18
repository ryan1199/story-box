@extends('layout.app')
@section('title')
    Box list
@endsection
@section('content')
    <div class="w-full grid grid-cols-1 gap-2">
        <div class="grid grid-cols-1 gap-2 content-start">
            <h1 class="h1 text-gray-100">Boxes</h1>
            <p class="p text-gray-100">Box is something like user's personal bookshelf</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
            @forelse ($boxes as $box)
                <div class="h-fit max-h-72 p-4 grid grid-cols-1 gap-2 border border-gray-100 rounded-lg overflow-y-auto">
                    <div class="grid grid-cols-1 gap-2">
                        <h2 class="h2 text-gray-100 break-all"><a href="{{ route('boxes.show', $box->slug) }}">{{ $box->title }}</a></h2>
                        <p class="p text-gray-100 break-all">Box by <a href="{{ route('users.show', $box->user->username) }}">{{ $box->user->username }}</a></p>
                        <p class="p text-gray-100">{{ $box->novels->count() }} novels inside this box</p>
                        <p class="p text-gray-100">{{ $box->description }}</p>
                    </div>
                    <div class="grid grid-cols-1 gap-2">
                        <div class="w-full h-fit flex flex-row flex-wrap justify-start items-start">
                            <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Categories</p>
                            @foreach ($box->categories as $category)
                                <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-lg break-all">{{ $category->name }}</p>
                            @endforeach
                        </div>
                        <div class="w-full h-fit flex flex-row flex-wrap justify-start items-start">
                            <p class="w-fit h-fit mr-1 mb-1 p text-gray-100">Tags</p>
                            @foreach ($box->tags as $tag)
                                <p class="w-fit h-fit px-1 mr-1 mb-1 p text-gray-950 bg-gray-100 rounded-lg break-all">{{ $tag->name }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div>
                    <p class="p text-gray-100">There are no boxes have been created yet</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection