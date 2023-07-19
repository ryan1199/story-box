@extends('layout.app')
@section('title')
    Login
@endsection
@section('content')
    <div class="w-full h-fit">
        <h1 class="h1 text-white text-left">Login</h1>
        <p class="p text-white">Do not have an account yet ? please register <a href="{{ route('register.view') }}" class="text-sky-500">here</a></p>
        <p class="p text-white">Forgot right ? <a href="{{ route('forgotpassword.view') }}" class="text-sky-500">unfortunately</a></p>
        <p class="p text-white">Resend email verification link ? <a href="{{ route('emailverification.view') }}" class="text-sky-500">yes please</a></p>
    </div>
    <form action="{{ route('login.post') }}" method="post" class="w-full h-fit max-w-lg p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-3xl">
        @csrf
        @method('POST')
        <div class="w-full max-w-md">
            <label for="username" class="w-full flex flex-col justify-between items-center">
                <span class="w-full">Username</span>
                <input type="text" name="username" id="username" value="{{ old('username') }}">
            </label>
            @error('username')
                <span class="w-full">{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full max-w-md">
            <label for="password" class="w-full flex flex-col justify-between items-center">
                <span class="w-full">Password</span>
                <div x-data="{type: false}" class="w-full flex flex-row justify-center items-center">
                    <input x-bind:type="type ? 'text' : 'password'" name="password" id="password" value="{{ old('password') }}">
                    <i class="material-icons" @click="type = ! type">remove_red_eye</i>
                </div>
            </label>
            @error('password')
                <span class="w-full">{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full max-w-md">
            <label for="remember" class="w-full flex flex-row space-x-2 justify-start items-center">
                <input type="checkbox" name="remember" id="remember" value=1>
                <span>remember me</span>
            </label>
            @error('remember')
                <span class="w-full">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="w-full h-fit p-4 text-white bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-3xl">Login</button>
    </form>
@endsection