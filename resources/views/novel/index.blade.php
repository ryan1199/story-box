@extends('layout.app')
@section('title')
    Novel list
@endsection
@section('content')
    <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-2">
        {{-- search --}}
        <div x-data='{search: true}' class="p-3 lg:col-span-1 grid grid-cols-1 gap-2 content-start">
            <h1 @click="search = ! search" class="h1 text-gray-100 text-left">Search</h1>
            <div class="bg-gray-100 rounded-lg">
                <form x-show='search' action="{{ route('novels.search') }}" method="post">
                    @csrf
                    @method('POST')
                    {{-- title --}}
                    <div class="w-full">
                        <label for="title" class="form-lable w-full p-3 flex flex-col justify-between items-start">
                            <h2 class="h2 text-gray-950 text-left">Title</h2>
                            <input type="text" name="title" id="title" value="{{ old('title') }}">
                        </label>
                        @error('title')
                            <span class="w-full m-2 form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- categories --}}
                    <div x-data='{categories: false}' class="w-full">
                        <div class="w-full pl-3">
                            <h2 @click="categories = ! categories" class="h2 text-gray-950">Categories</h2>
                        </div>
                        <div x-show='categories' class="w-full max-w-md p-1 flex flex-row flex-wrap">
                            @forelse ($categories as $i=>$category)
                                <label for="{{ $category->name }}" class="form-lable w-fit m-2 p-2 flex flex-row justify-center items-center space-x-2 border-2 border-gray-950 rounded-lg">
                                    <input type="checkbox" name="categories[{{ $i }}]" id="{{ $category->name }}" value="{{ $category->name }}">
                                    <span class="w-full form-span break-all">{{ $category->name }}</span>
                                </label>
                                @error("categories.$i")
                                    <span class="w-full m-2 form-error">{{ $message }}</span>
                                @enderror
                            @empty
                                <div class="w-full m-2">
                                    <p class="p text-gray-950">There are no categories on this novel</p>
                                </div>
                            @endforelse
                            @error('categories')
                                <span class="w-full m-2 form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    {{-- tags --}}
                    <div x-data='{tags: false}' class="w-full">
                        <div class="w-full pl-3">
                            <h2 @click="tags = ! tags" class="h2 text-gray-950">Tags</h2>
                        </div>
                        <div x-show='tags' class="w-full max-w-md p-1 flex flex-row flex-wrap">
                            @forelse ($tags as $i=>$tag)
                                <label for="{{ $tag->name }}" class="form-lable w-fit m-2 p-2 flex flex-row justify-center items-center space-x-2 border-2 border-gray-950 rounded-lg">
                                    <input type="checkbox" name="tags[{{ $i }}]" id="{{ $tag->name }}" value="{{ $tag->name }}">
                                    <span class="w-full form-span break-all">{{ $tag->name }}</span>
                                </label>
                                @error("tags.$i")
                                    <span class="w-full m-2 form-error">{{ $message }}</span>
                                @enderror
                            @empty
                                <div class="w-full m-2">
                                    <p class="p text-gray-950">There are no tags on this novel</p>
                                </div>
                            @endforelse
                            @error('tags')
                                <span class="w-full m-2 form-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="w-full p-3">
                        <button type="submit" class="form-button w-full h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Search</button>
                    </div>
                </form>
            </div>
        </div>
        {{-- novel --}}
        <div class="p-3 lg:col-span-2 grid grid-cols-1 gap-2 content-start">
            <div class="w-fit">
                <h1 class="h1 text-gray-100 text-left">Novels</h1>
            </div>
            <div class="w-fit h-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 grid-flow-row auto-rows-min">
                @forelse ($novels as $novel)
                    <div class="h-fit max-h-96 p-2 grid grid-cols-1 gap-2 content-start border border-gray-100 rounded-lg overflow-clip overflow-y-auto">
                        <div class="w-full h-fit grid grid-cols-1 gap-2">
                            <div class="max-h-60 overflow-y-auto">
                                <img src="{{ asset('storage/novel/'.$novel->image->url) }}" alt="novel cover {{ $novel->title }}" class="w-full h-fit border border-gray-100 rounded-lg">
                            </div>
                            <div class="w-full flex flex-col space-y-2 justify-start items-start">
                                <p class="p text-gray-100 text-left break-all"><a href="{{ route('novels.show', $novel) }}">{{ $novel->title }}</a></p>
                                <p class="p text-gray-100 text-left break-all">Added by <a href="{{ route('users.show', $novel->user->username) }}">{{ $novel->user->username }}</a></p>
                                <p class="p text-gray-100 text-left">{{ count($novel->chapters) }} chapters</p>
                                <p class="p text-gray-100 text-left">Updated {{ now()->sub($novel->updated_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="w-full h-fit grid grid-cols-1 gap-2">
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
                @empty
                    <div>
                        <p class="p text-gray-100 text-left">There are no novels have been created yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection