
<div x-data="{ open: false }" x-init="open = false">
    <!-- View Product Button -->
    <button @click="open = true"
            class="px-4 py-2 bg-white rounded-lg shadow border flex items-center space-x-2 hover:bg-gray-100">
                <x-heroicon-o-plus class="w-5 h-5"/>
                Add New Course
    </button>

    <div x-show="open" x-cloak wire:ignore.self
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-40 z-50">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-lg p-6 relative">
                    <!-- Close Button -->
                    <button wire:click="close" class="absolute top-4 right-4 text-gray-500 hover:text-black">
                        ✕
                    </button>

                    <!-- Title -->
                    <h2 class="text-xl font-semibold mb-4">Add new course</h2>
                    <hr class="mb-4">

                    <!-- Form -->
                    <div class="space-y-4">
                        <!-- Course Name -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Course name</label>
                            <input type="text" placeholder="Enter the course name"
                                class="w-full border rounded-lg px-4 py-2 focus:ring focus:ring-gray-200">
                        </div>

                        <!-- Cover Image -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Cover image</label>
                            <div class="border rounded-lg px-4 py-6 flex flex-col items-center justify-center text-gray-400 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-2" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M3 7l9 6 9-6" />
                                </svg>
                                Upload cover image
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium mb-1">Description</label>
                            <textarea placeholder="Enter the description of the course"
                                class="w-full border rounded-lg px-4 py-2 h-24 focus:ring focus:ring-gray-200"></textarea>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button @click="open = false" class="px-4 py-2 border rounded-lg">Cancel</button>
                        <button class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Add</button>
                    </div>
            </div>
        
    </div>
</div>