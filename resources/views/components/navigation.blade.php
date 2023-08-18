<nav x-data="{ nav: false }" class="nav w-full h-fit p-0 flex flex-row flex-wrap justify-center items-center">
    <span @click="nav = ! nav" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">StoryBox</span>
    <a x-show="nav" x-transition href="{{ route('home') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">Home</a>
    <a x-show="nav" x-transition href="{{ route('novels.index') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">Novel</a>
    <a x-show="nav" x-transition href="{{ route('boxes.index') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">Box</a>
    @auth
        <a x-show="nav" x-transition href="{{ route('reports.index') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">Reports</a>
        <a x-show="nav" x-transition href="{{ route('tags.index') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">Tags</a>
        <a x-show="nav" x-transition href="{{ route('categories.index') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">Categories</a>
        <a x-show="nav" x-transition href="{{ route('users.show', Auth::user()->username) }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">{{ Auth::user()->username }}</a>
        <a x-show="nav" x-transition href="{{ route('logout') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">Logout</a>
    @endauth
    @guest
        <a x-show="nav" x-transition href="{{ route('register.view') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">Register</a>
        <a x-show="nav" x-transition href="{{ route('login.view') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">Login</a>
    @endguest
    <a x-show="nav" x-transition href="{{ route('about') }}" class="w-fit p-4 block bg-sky-800 hover:bg-sky-700 active:bg-sky-500  p text-center text-gray-100 rounded-lg">About</a>
</nav>