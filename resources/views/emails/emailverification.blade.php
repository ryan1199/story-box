<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <x-alpinejs/>
        <title>Email verification notification</title>
    </head>
    <body class="antialiased bg-gray-950 z-0 relative">
        <div class="w-full h-full p-8 z-10 flex flex-row justify-between items-center bg-gray-950 overflow-x-auto">
            <div class="w-full  max-w-sm md:max-w-lg mx-auto h-full">
                <div class="w-full h-fit flex flex-col space-y-4 justify-center items-center">
                    <h1 class="h1 text-white text-left">Email verification</h1>
                    <p class="p text-white">
                        Hi {{ $fullname }}.
                    </p>
                    <p class="p text-white">
                        We are from <a href="{{ route('home') }}" class="font-black">storybox</a>, 
                        just one step to finish your registration, you need to verify your email address using link below.
                    </p>
                    <a href="{{ $url }}" class="w-full h-fit p-2 text-white bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-3xl text text-center font-black">Verify me</a>
                </div>
            </div>
        </div>
    </body>
</html>