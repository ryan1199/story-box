@if (session()->has('success'))
    <div x-show="open" class="container mx-auto">
        <span>{{ session()->get('success') }}</span>
    </div>
@endif
@if (session()->has('error'))
    <div x-show="open" class="container mx-auto">
        <span>{{ session()->get('error') }}</span>
    </div>
@endif