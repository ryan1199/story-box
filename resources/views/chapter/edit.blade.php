@extends('layout.app')
@section('title')
    {{ $chapter->title }} edit
@endsection
@section('content')
    @auth
        <div class="w-full max-w-lg h-fit flex flex-col space-y-2">
            <div class="w-full h-fit flex flex-col">
                <h1 class="h1 text-gray-100 text-left">Edit chapter</h1>
                <h2 class="h2 text-gray-100 text-left">{{ $chapter->title }}</h2>
            </div>
            <form action="{{ route('chapters.update', [$novel, $chapter]) }}" method="post" class="w-full h-fit max-w-lg p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-lg" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="w-full max-w-md">
                    <label for="title" class="form-lable w-full flex flex-col justify-between items-center">
                        <span class="w-full form-span">Title</span>
                        <input type="text" name="title" id="title" value="{{ old('title', $chapter->title) }}">
                    </label>
                    @error('title')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full max-w-md">
                    <label for="content" class="form-lable w-full flex flex-col justify-between items-center">
                        <span class="w-full form-span">Content</span>
                        <textarea name="content" id="content" cols="30" rows="10">{{ old('content', $chapter->content) }}</textarea>
                    </label>
                    @error('content')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="form-button w-full h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Edit chapter</button>
            </form>
        </div>
    @endauth
@endsection