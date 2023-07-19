@if (session()->has('success'))
    <div x-data="{ open: true }" @click="open = false" x-show="open" class="w-screen h-screen inset-0 z-20 absolute bg-black/90 flex flex-col justify-center items-center">
        <div x-show="open" class="w-fit mx-auto self-center items-center">
            <span class="p text-white p-8">{{ session()->get('success') }}</span>
        </div>
    </div>
@endif
@if (session()->has('error'))
    <div x-data="{ open: true }" @click="open = false" x-show="open" class="w-screen h-screen inset-0 z-20 absolute bg-black/90 flex flex-col justify-center items-center">
        <div x-show="open" class="w-fit mx-auto self-center items-center">
            <span class="p text-white p-8">{{ session()->get('error') }}</span>
        </div>
    </div>
@endif
{{-- <div x-data="{ open: true }" @click="open = false" x-show="open" class="w-screen h-screen inset-0 z-20 absolute bg-black/90 flex flex-col justify-center items-center">
    <div x-show="open" class="w-fit mx-auto self-center items-center">
        <span class="p text-white p-8">Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test</span>
    </div>
</div> --}}