@extends('layout.app')
@section('title')
    Edit {{ $user->username }}
@endsection
@section('content')
    @auth
        <div class="w-full max-w-lg h-fit flex flex-col space-y-2">
            <div class="w-full h-fit flex flex-col">
                <h1 class="h1 text-gray-100 text-left">Edit user</h1>
                <h2 class="h2 text-gray-100 text-left">{{ $user->username }}</h2>
            </div>
            <form action="{{ route('users.update', $user->username) }}" method="post" class="w-full h-fit max-w-lg p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-lg" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="w-full max-w-md">
                    <label for="picture" class="form-lable w-full flex flex-col justify-between items-center">
                        <span class="w-full form-span">Profile picture</span>
                        <input type="file" name="picture" id="picture" accept="image/*" class="w-full">
                    </label>
                    @error('picture')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full max-w-md">
                    <label for="first_name" class="form-lable w-full flex flex-col justify-between items-center">
                        <span class="w-full form-span">First name</span>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $user->first_name) }}">
                    </label>
                    @error('first_name')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full max-w-md">
                    <label for="last_name" class="form-lable w-full flex flex-col justify-between items-center">
                        <span class="w-full form-span">Last name</span>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}">
                    </label>
                    @error('last_name')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full max-w-md">
                    <label for="username" class="form-lable w-full flex flex-col justify-between items-center">
                        <span class="w-full form-span">Username</span>
                        <input type="text" name="username" id="username" value="{{ old('username', $user) }}">
                    </label>
                    @error('username')
                        <span class="w-full form-error">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="form-button w-full h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Update</button>
            </form>
        </div>
    @endauth
@endsection