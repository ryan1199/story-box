@extends('layout.app')
@section('title')
    Login
@endsection
@section('content')
    <form action="{{ route('login.post') }}" method="post" class="w-full max-w-lg mx-auto my-14 p-4 flex flex-col bg-sky-950">
        @csrf
        @method('POST')
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
            <label for="password" class="w-full flex flex-row justify-between">
                <span>Password</span>
                <input type="password" name="password" id="password" value="{{ old('password') }}">
            </label>
            @error('password')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full mb-4 p-4 bg-sky-200">
            <label for="remember" class="w-full flex flex-row">
                <input type="checkbox" name="remember" id="remember" value=1
                class="rounded 
                place-self-center
                bg-gray-200
                border-transparent
                focus:border-transparent focus:bg-gray-200
                text-gray-700
                focus:ring-1 focus:ring-offset-2 focus:ring-gray-500">
                <span>remember me</span>
            </label>
            @error('remember')
                <span>{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="w-fit mx-auto p-4 bg-sky-200">Login</button>
    </form>
@endsection