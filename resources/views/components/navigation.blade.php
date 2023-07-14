<nav class="container mx-auto flex flex-row">
    <div x-show="open" x-transition class="w-fit flex flex-row">
        <a href="{{ route('home') }}" class="w-fit mx-1 p-4 block bg-sky-800 text-white">Home</a>
        <a href="{{ route('register.view') }}" class="w-fit mx-1 p-4 block bg-sky-800 text-white">Register</a>
        <a href="{{ route('login.view') }}" class="w-fit mx-1 p-4 block bg-sky-800 text-white">Login</a>
        <a href="{{ route('logout') }}" class="w-fit mx-1 p-4 block bg-sky-800 text-white">Logout</a>
    </div>
    <span @click="open = ! open" x-text="open ? 'Close' : 'Open'" x-transition class="w-fit mx-1 p-4 block bg-sky-800 text-white">Hide</span>
</nav>