@extends('layout.app')
@section('title')
    Register
@endsection
@section('content')
    <div class="w-full h-fit">
        <h1 class="h1 text-white text-left">Register</h1>
        <p class="p text-red-500">Please note: After you do register, we send you an email verification, please confirm the email to keep using this account you have just created.</p>
    </div>
    <form action="{{ route('register.post') }}" method="post" class="w-full h-fit max-w-lg p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-3xl" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div class="w-full max-w-md">
            <label for="picture" class="w-full flex flex-col justify-between items-center">
                <span class="w-full">Profile picture</span>
                <input type="file" name="picture" id="picture" accept="image/*" value="{{ old('picture') }}" class="w-full">
            </label>
            @error('picture')
                <span class="w-full">{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full max-w-md">
            <label for="first_name" class="w-full flex flex-col justify-between items-center">
                <span class="w-full">First name</span>
                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}">
            </label>
            @error('first_name')
                <span class="w-full">{{ $message }}</span>
            @enderror
        </div>
        <div class="w-full max-w-md">
            <label for="last_name" class="w-full flex flex-col justify-between items-center">
                <span class="w-full">Last name</span>
                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}">
            </label>
            @error('last_name')
                <span class="w-full">{{ $message }}</span>
            @enderror
        </div>
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
            <label for="email" class="w-full flex flex-col justify-between items-center">
                <span class="w-full">E-mail</span>
                <input type="email" name="email" id="email" value="{{ old('email') }}">
            </label>
            @error('email')
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
            <label for="confirm_password" class="w-full flex flex-col justify-between items-center">
                <span class="w-full">Confirm password</span>
                <div x-data="{type: false}" class="w-full flex flex-row justify-center items-center">
                    <input x-bind:type="type ? 'text' : 'password'" name="confirm_password" id="confirm_password" value="{{ old('confirm_password') }}">
                    <i class="material-icons" @click="type = ! type">remove_red_eye</i>
                </div>
            </label>
            @error('confirm_password')
                <span class="w-full">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="w-full h-fit p-4 text-white bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-3xl">Register</button>
    </form>
@endsection