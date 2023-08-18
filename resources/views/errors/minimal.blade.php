@extends('layout.app')
{{-- @yield('title') --}}
@section('title')
    @yield('title')
@endsection
@section('content')
    <div class="w-full h-[30rem] p-4 flex flex-row justify-center items-center">
        <h1 class="h1 text-gray-100">@yield('code') @yield('message')</h1>
    </div>
@endsection