@extends('layout.app')
@section('title')
    Category list
@endsection
@section('content')
    @auth
        <div class="w-full h-fit p-4 grid grid-cols-1 gap-2 border-2 border-gray-100 rounded-lg">
            <div class="w-full flex flex-row justify-between items-center">
                <h1 class="h1 text-gray-100 text-left">Category list</h1>
                @auth
                    <a href="{{ route('categories.create') }}"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">add_circle</i></a>
                @endauth
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                @forelse ($categories as $category)
                    <div class="w-full p-2 flex flex-row justify-start items-center space-x-2 border border-gray-100 rounded-lg">
                        @auth
                            <div class="w-fit flex flex-row justify-center items-center space-x-2">
                                <a href="{{ route('categories.edit', $category) }}"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">edit</i></a>
                                <form action="{{ route('categories.destroy', $category) }}" method="post" onclick="return confirm('Delete category {{ $category->name }} ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"><i class="material-icons p text-gray-950 text-center bg-gray-100 rounded-full">delete</i></button>
                                </form>
                            </div>
                        @endauth
                        <div class="w-full">
                            <p class="p text-gray-100 text-left break-all">{{ $category->name }}</p>
                        </div>
                    </div>
                @empty
                    <div>
                        <p class="p text-gray-100">There are no categories have been created yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endauth
@endsection