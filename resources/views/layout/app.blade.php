<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <x-alpinejs/>
        <title>@yield('title') | StoryBox</title>
    </head>
    <body class="antialiased bg-gray-950 z-0 relative">
        <div class="w-full h-fit mt-4">
            <x-navigation/>
        </div>
        <x-alert/>
        <div class="w-full max-w-sm sm:max-w-7xl h-full mx-auto px-2 z-10 flex flex-row flex-wrap justify-center items-center">
            <div class="w-full h-full mx-0 mt-4 p-2 flex flex-row flex-wrap justify-evenly items-start">@yield('content')</div>
        </div>
    </body>
</html>
