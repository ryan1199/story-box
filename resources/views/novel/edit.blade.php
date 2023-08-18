@extends('layout.app')
@section('title')
    {{ $novel->title }} edit
@endsection
@section('content')
    @auth
        <div class="w-full max-w-lg h-fit flex flex-col space-y-2">
            <div class="w-full h-fit flex flex-col">
                <h1 class="h1 text-gray-100 text-left">Edit novel</h1>
                <h2 class="h2 text-gray-100 text-left break-all">{{ $novel->title }}</h2>
            </div>
            <form action="{{ route('novels.update', $novel) }}" method="post" class="w-full h-fit max-w-lg p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-lg" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="w-full max-w-md">
                    <img src="{{ asset('storage/novel/'.$novel->image->url) }}" alt="novel cover {{ $novel->title }}" class="w-full h-fit border border-gray-100 rounded-lg">
                </div>
                <div class="w-full max-w-md">
                    <label for="picture" class="form-lable w-full flex flex-col justify-between items-center">
                        <span class="w-full form-span">Novel picture</span>
                        <input type="file" name="picture" id="picture" accept="image/*" value="{{ old('picture') }}" class="w-full">
                    </label>
                    @error('picture')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full max-w-md">
                    <label for="title" class="form-lable w-full flex flex-col justify-between items-center">
                        <span class="w-full form-span">Title</span>
                        <input type="text" name="title" id="title" value="{{ old('title', $novel->title) }}">
                    </label>
                    @error('title')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full max-w-md">
                    <label for="description" class="form-lable w-full flex flex-col justify-between items-center">
                        <span class="w-full form-span">Description</span>
                        <textarea name="description" id="description" cols="30" rows="10">{{ old('description', $novel->description) }}</textarea>
                    </label>
                    @error('description')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full max-w-md flex flex-row flex-wrap space-x-2 space-y-2">
                    <div class="w-full">
                        <p class="w-full p text-gray-950 text-left">Categories</p>
                    </div>
                    @forelse ($categories as $i=>$category)
                        <label for="{{ $category->name }}" class="form-lable w-fit p-2 flex flex-row justify-center items-center space-x-2 border-2 border-gray-950 rounded-lg">
                            <input type="checkbox" name="categories[{{ $i }}]" id="{{ $category->name }}" value="{{ $category->name }}" @foreach ($novel_categories as $novel_category)
                                @checked($category->name == $novel_category->name)
                            @endforeach>
                            <span class="w-full form-span break-all">{{ $category->name }}</span>
                        </label>
                        @error("categories.$i")
                            <span class="w-full form-error">{{ $message }}</span>
                        @enderror
                    @empty
                        <div class="w-full">
                            <p class="p text-gray-100">There are no categories have been created yet</p>
                        </div>
                    @endforelse
                    @error('categories')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full max-w-md flex flex-row flex-wrap space-x-2 space-y-2">
                    <div class="w-full">
                        <p class="w-full p text-gray-950 text-left">Tags</p>
                    </div>
                    @forelse ($tags as $i=>$tag)
                        <label for="{{ $tag->name }}" class="form-lable w-fit p-2 flex flex-row justify-center items-center space-x-2 border-2 border-gray-950 rounded-lg">
                            <input type="checkbox" name="tags[{{ $i }}]" id="{{ $tag->name }}" value="{{ $tag->name }}" @foreach ($novel_tags as $novel_tag)
                                @checked($tag->name == $novel_tag->name)
                            @endforeach>
                            <span class="w-full form-span break-all">{{ $tag->name }}</span>
                        </label>
                        @error('tags.$i')
                            <span class="w-full form-error">{{ $message }}</span>
                        @enderror
                    @empty
                        <div class="w-full">
                            <p class="p text-gray-100">There are no tags have been created yet</p>
                        </div>
                    @endforelse
                    @error('tags')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="form-button w-full h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Edit novel</button>
            </form>
        </div>
    @endauth
@endsection