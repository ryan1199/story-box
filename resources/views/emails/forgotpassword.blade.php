<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <x-alpinejs/>
        <title>Forgot password notification</title>
    </head>
    <body class="antialiased bg-gray-950 z-0 relative">
        <div class="w-full h-full p-8 z-10 flex flex-row justify-between items-center bg-gray-950 overflow-x-auto">
            <div class="w-full  max-w-sm md:max-w-lg mx-auto h-full">
                <div class="w-full h-fit flex flex-col space-y-4 justify-center items-center">
                    <h1 class="h1 text-gray-100 text-left">Forgot password</h1>
                    <p class="p text-gray-100">
                        Hi {{ $fullname }}.
                    </p>
                    <p class="p text-gray-100">
                        We are from <a href="{{ route('home') }}" class="font-black">storybox</a>, 
                        we have just got <span class="font-black">request to change your account password</span> on <a href="{{ route('home') }}" class="font-black">storybox</a>, 
                        if you do not think that you are request this, just ignore this message, 
                        if you do, you can click link below to change your password.
                    </p>
                    <a href="{{ $url }}" class="w-full h-fit p-2 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg text text-center font-black">Change password</a>
                </div>
            </div>
        </div>
    </body>
</html>