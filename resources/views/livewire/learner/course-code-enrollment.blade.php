<div
    x-data="{ openEnrollModal: false }"
    @enrolled.window="openEnrollModal = false"
>


    <!-- Enroll Button -->
    <button
        @click="openEnrollModal = true"
        class="p-2 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition flex items-center justify-center"
        title="Enroll by Course Code"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M12 4v16m8-8H4" />
        </svg>
    </button>

    <!-- ENROLL MODAL -->
    <div
        x-show="openEnrollModal"
        x-cloak
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div
            @click.away="openEnrollModal = false"
            class="bg-white rounded-lg shadow-lg w-full max-w-md p-6"
        >
            <h2 class="text-lg font-bold mb-4">
                Enroll in a Course
            </h2>

            <!-- Course Code Input -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">
                    Course Code
                </label>

                <input
                    type="text"
                    wire:model.defer="courseCode"
                    placeholder="Enter course code"
                    class="w-full border rounded-md p-2 focus:ring focus:border-orange-400"
                >

                @error('courseCode')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-2">
                <button
                    @click="openEnrollModal = false"
                    class="px-4 py-2 border rounded-md hover:bg-gray-100"
                >
                    Cancel
                </button>

                <button
                    wire:click="enrollByCode"
                    class="px-4 py-2 bg-orange-500 text-white rounded-md hover:bg-orange-600"
                >
                    Enroll
                </button>
            </div>
        </div>
    </div>

</div>
