<nav class="w-full p-0 flex flex-row p text-center justify-center items-center">
    <div x-show="open" x-transition class="w-full flex flex-row flex-wrap justify-center items-center">
        <a href="{{ route('home') }}" class="w-fit p-4 block bg-sky-800 text-white rounded-3xl">Home</a>
        <a href="{{ route('register.view') }}" class="w-fit p-4 block bg-sky-800 text-white rounded-3xl">Register</a>
        <a href="{{ route('login.view') }}" class="w-fit p-4 block bg-sky-800 text-white rounded-3xl">Login</a>
        <a href="{{ route('logout') }}" class="w-fit p-4 block bg-sky-800 text-white rounded-3xl">Logout</a>
    </div>
    {{-- <span @click="open = ! open" x-transition class="w-fit p-4 block bg-sky-800 text-white rounded-3xl">Menu</span> --}}
</nav>