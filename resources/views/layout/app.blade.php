<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <x-alpinejs/>
        <title>@yield('title')</title>
    </head>
    <body class="antialiased">
        <div>
            <div x-data="{ open: true }">
                <x-navigation/>
            </div>
            <div x-data="{ open: true }" @click.outside="open = false">
                <x-alert/>
            </div>
            <div class="container mx-auto">@yield('content')</div>
        </div>
    </body>
</html>
