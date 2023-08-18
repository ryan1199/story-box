@extends('layout.app')
@section('title')
    StoryBox
@endsection
@section('content')
    <div class="w-full h-fit grid grid-cols-1 gap-8">
        <div>
            <div>
                <h1 class="h1 text-gray-100">StoryBox</h1>
            </div>
        </div>
        {{-- new novel --}}
        <div class="p-4 grid grid-cols-1 gap-4 border border-gray-100 rounded-lg">
            <div>
                <h2 class="h2 text-gray-100">New Novels</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @forelse ($new_novels as $novel)
                    <div class="max-h-60 grid grid-cols-1 gap-2 content-start overflow-y-auto">
                        <img src="{{ asset('storage/novel/'.$novel->image->url) }}" alt="{{ $novel->title }}">
                        <p class="p text-gray-100 break-all"><a href="{{ route('novels.show', $novel->slug) }}">{{ $novel->title }}</a></p>
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
                @empty
                    <div>
                        <p class="p text-gray-100">There are no new novels</p>
                    </div>
                @endforelse
            </div>
            @if ($new_novels->hasPages())
                <div class="w-full p-2 bg-gray-100 rounded-lg">
                    {{ $new_novels->links() }}
                </div>
            @endif
        </div>
        {{-- update novel --}}
        <div class="p-4 grid grid-cols-1 gap-4 border border-gray-100 rounded-lg">
            <div>
                <h2 class="h2 text-gray-100">Update Novels</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @forelse ($update_novels as $novel)
                    <div class="max-h-60 grid grid-cols-1 gap-2 content-start overflow-y-auto">
                        <img src="{{ asset('storage/novel/'.$novel->image->url) }}" alt="{{ $novel->title }}">
                        <p class="p text-gray-100 break-all"><a href="{{ route('novels.show', $novel->slug) }}">{{ $novel->title }}</a></p>
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
                @empty
                    <div>
                        <p class="p text-gray-100">There are no update novels</p>
                    </div>
                @endforelse
            </div>
            @if ($update_novels->hasPages())
                <div class="w-full p-2 bg-gray-100 rounded-lg">
                    {{ $update_novels->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection