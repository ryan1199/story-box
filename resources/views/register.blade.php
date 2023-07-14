@extends('layout.app')
@section('title')
    Register
@endsection
@section('content')
    <form action="{{ route('register.post') }}" method="post" class="w-full max-w-lg mx-auto my-14 p-4 flex flex-col bg-sky-950" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="w-full mb-4 p-4 bg-sky-200">
            <label for="picture" class="w-full flex flex-row justify-between">
                <span>Profile picture</span>
                <input type="file" name="picture" id="picture" accept="image/*" value="{{ old('picture') }}">
            </label>
            @error('picture')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full mb-4 p-4 bg-sky-200">
            <label for="first_name" class="w-full flex flex-row justify-between">
                <span>First name</span>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}">
            </label>
            @error('first_name')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full mb-4 p-4 bg-sky-200">
            <label for="last_name" class="w-full flex flex-row justify-between">
                <span>Last name</span>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}">
            </label>
            @error('last_name')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full mb-4 p-4 bg-sky-200">
            <label for="username" class="w-full flex flex-row justify-between">
                <span>Username</span>
                <input type="text" name="username" id="username" value="{{ old('username') }}">
            </label>
            @error('username')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full mb-4 p-4 bg-sky-200">
            <label for="email" class="w-full flex flex-row justify-between">
                <span>E-mail</span>
                <input type="email" name="email" id="email" value="{{ old('email') }}">
            </label>
            @error('email')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full mb-4 p-4 bg-sky-200">
            <label for="password" class="w-full flex flex-row justify-between">
                <span>Password</span>
                <input type="password" name="password" id="password" value="{{ old('password') }}">
            </label>
            @error('password')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full mb-4 p-4 bg-sky-200">
            <label for="confirm_password" class="w-full flex flex-row justify-between">
                <span>Confirm password</span>
                <input type="password" name="confirm_password" id="confirm_password" value="{{ old('confirm_password') }}">
            </label>
            @error('confirm_password')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="w-fit mx-auto p-4 bg-sky-200">Register</button>
    </form>
@endsection