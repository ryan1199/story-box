@props(['id', 'type'])
@auth
    <form action="{{ route('reports.add', [$id, $type]) }}" method="post" class="p-4 bg-gray-100 rounded-lg grid grid-cols-1 gap-2">
        @csrf
        @method('POST')
        <label for="reason" class="form-lable">
            <span class="form-span">Reason</span>
            <textarea name="reason" id="reason" cols="30" rows="5"></textarea>
        </label>
        @error('reason')
            <div>
                <span class="form-error">{{ $message }}</span>
            </div>
        @enderror
        <button type="submit" class="form-button w-full h-fit p-4 text-gray-100 bg-sky-800 hover:bg-sky-700 active:bg-sky-500 rounded-lg">Report</button>
    </form>
@endauth