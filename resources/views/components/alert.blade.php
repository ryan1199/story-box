@if (session()->has('success'))
    <div x-data="{ open: true }" @click="open = false" x-show="open" class="w-screen h-fit flex flex-col justify-center items-center">
        <div x-show="open" class="w-fit p-8 border-b-8 border-green-200">
            <span class="p text-gray-100">{{ session()->get('success') }}</span>
        </div>
    </div>
@endif
@if (session()->has('error'))
    <div x-data="{ open: true }" @click="open = false" x-show="open" class="w-screen h-fit flex flex-col justify-center items-center">
        <div x-show="open" class="w-fit p-8 border-b-8 border-red-200">
            <span class="p text-gray-100">{{ session()->get('error') }}</span>
        </div>
    </div>
@endif
{{-- <div x-data="{ open: true }" @click="open = false" x-show="open" class="w-screen h-fit flex flex-col justify-center items-center">
    <div x-show="open" class="w-fit p-8 border-b-8 border-green-200">
        <p class="p text-gray-100">Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test Test</p>
    </div>
</div> --}}