@extends('layout.app')
@section('title')
    Add tag
@endsection
@section('content')
    @auth
        <div class="w-full max-w-sm h-fit grid grid-cols-1 gap-2">
            <div class="w-full h-fit">
                <h1 class="h1 text-gray-100 text-left">Add tag</h1>
            </div>
            <form action="{{ route('tags.store') }}" method="post" class="h-fit p-4 grid grid-cols-1 gap-2 bg-gray-100 rounded-lg">
                @csrf
                @method('POST')
                <label for="name" class="form-lable">
                    <span class="form-span">Name</span>
                    <input type="text" name="name" id="name" value="{{ old('name') }}">
                </label>
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
                <button type="submit" class="form-button h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Add tag</button>
            </form>
        </div>
    @endauth
@endsection