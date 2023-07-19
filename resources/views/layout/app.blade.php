<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <x-alpinejs/>
        <title>@yield('title')</title>
    </head>
    <body x-data="{ nav: false }" class="antialiased bg-gray-950 z-0 relative">
        <x-alert/>
        <div x-show="nav" x-data="{ open: true }" x-transition class="w-full h-fit bg-gray-950">
            <x-navigation/>
        </div>
        <div class="w-full h-full p-8 z-10 flex flex-row justify-between items-center bg-gray-950 overflow-x-auto">
            <div @click.outside="nav = ! nav" class="w-full  max-w-sm md:max-w-lg mx-auto h-full flex flex-col space-y-4 justify-center items-center">@yield('content')</div>
        </div>
    </body>
</html>
