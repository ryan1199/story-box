@extends('layout.app')
@section('title')
    Forgot password
@endsection
@section('content')
    <div class="w-full h-fit">
        <h1 class="h1 text-gray-100 text-left">Forgot password</h1>
        <p class="p text-gray-100">We send a link to reset your password, to your registered email that recorded on our system.</p>
    </div>
    <form action="{{ route('forgotpassword.send') }}" method="post" class="w-full h-fit max-w-lg p-4 flex flex-col space-y-4 justify-center items-center bg-gray-100 rounded-lg">
        @csrf
        @method('POST')
        <div class="w-full max-w-md">
            <label for="email" class="form-lable w-full flex flex-col justify-between items-center">
                <span class="w-full form-span">Email</span>
                <input type="email" name="email" id="email" value="{{ old('email') }}">
            </label>
            @error('email')
                <span class="w-full form-error">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="form-button w-full h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Send me an email</button>
    </form>
@endsection