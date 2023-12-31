@extends('layout.app')
@section('title')
    Login
@endsection
@section('content')
    <div class="w-full max-w-lg h-fit flex flex-col space-y-2">
        <div class="w-full h-fit">
            <h1 class="h1 text-gray-100 text-left">Login</h1>
            <p class="p text-gray-100">Do not have an account yet ? please register <a href="{{ route('register.view') }}" class="text-sky-500">here</a></p>
            <p class="p text-gray-100">Forgot right ? <a href="{{ route('forgotpassword.view') }}" class="text-sky-500">unfortunately</a></p>
            <p class="p text-gray-100">Resend email verification link ? <a href="{{ route('emailverification.view') }}" class="text-sky-500">yes please</a></p>
        </div>
        <form action="{{ route('login.post') }}" method="post" class="w-full h-fit max-w-lg p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-lg">
            @csrf
            @method('POST')
            <div class="w-full max-w-md">
                <label for="username" class="form-lable w-full flex flex-col justify-between items-center">
                    <span class="w-full form-span">Username</span>
                    <input type="text" name="username" id="username" value="{{ old('username') }}">
                </label>
                @error('username')
                    <span class="w-full form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full max-w-md">
                <label for="password" class="form-lable w-full flex flex-col justify-between items-center">
                    <span class="w-full form-span">Password</span>
                    <div x-data="{type: false}" class="w-full flex flex-row justify-center items-center">
                        <input x-bind:type="type ? 'text' : 'password'" name="password" id="password" value="{{ old('password') }}">
                        <i class="material-icons" @click="type = ! type">remove_red_eye</i>
                    </div>
                </label>
                @error('password')
                    <span class="w-full form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="w-full max-w-md">
                <label for="remember" class="form-lable w-full flex flex-row space-x-2 justify-start items-center">
                    <input type="checkbox" name="remember" id="remember" value=1>
                    <span class="form-span">remember me</span>
                </label>
                @error('remember')
                    <span class="w-full form-error">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="form-button w-full h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Login</button>
        </form>
    </div>
@endsection