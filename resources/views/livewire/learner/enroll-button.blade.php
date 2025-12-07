<div>
    @if($enrolled)
        <button class="bg-gray-500 text-white px-4 py-1 rounded-full" disabled>
            Enrolled
        </button>
    @else
        <button wire:click="enroll"
                class="bg-black text-white px-4 py-1 rounded-full hover:bg-gray-800">
            Enroll
        </button>
    @endif

    @if (session()->has('message'))
        <p class="text-green-600 mt-2">{{ session('message') }}</p>
    @endif
</div>
