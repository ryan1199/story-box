@extends('layout.app')
@section('title')
    Forgot password
@endsection
@section('content')
    <div class="w-full h-fit">
        <h1 class="h1 text-white text-left">Reset password</h1>
        <p class="p text-white">Please remember your password next time.</p>
    </div>
    <form action="{{ route('resetpassword.reset', [$email, $ticket]) }}" method="post" class="w-full h-fit max-w-lg p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-3xl">
        @csrf
        @method('POST')
        <div class="w-full max-w-md">
            <label for="email" class="w-full flex flex-col justify-between items-center">
                <span class="w-full">E-mail</span>
                <input type="email" name="email" id="email" value="{{ $email }}" readonly disabled>
            </label>
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
        <button type="submit" class="w-full h-fit p-4 text-white bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-3xl">Change password</button>
    </form>
@endsection