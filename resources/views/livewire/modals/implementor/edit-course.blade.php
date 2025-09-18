<div x-data="{ open: false }">
    <!-- Trigger button -->
    <button 
        @click="open = true"
        class="bg-transparent text-black px-4 py-2 rounded-lg border hover:bg-black hover:text-white transform transition duration:300">
        +
    </button>

    <!-- Modal Background -->
    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
    >
        <!-- Modal Content -->
        <div 
            class="bg-white rounded-2xl shadow-lg w-full max-w-xl py-6"
            @click.away="open = false"
        >
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-3 px-6">
                <h2 class="text-xl font-semibold">Edit Course</h2>
                <button @click="open = false" class="text-gray-500 hover:text-red-500">&times;</button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveCourse" class="mt-4 space-y-4 px-8">
                <!-- Course Title -->
                <div>
                    <label class="block text-gray-700 mb-1">Course Title</label>
                    <input type="text" wire:model="title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300" placeholder="Enter course title">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-gray-700 mb-1">Description</label>
                    <textarea wire:model="description" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300" rows="3" placeholder="Enter course description"></textarea>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-gray-700 mb-1">Category</label>
                    <select wire:model="category" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                        <option>Select category</option>
                        <option>Technology</option>
                        <option>Business</option>
                        <option>Education</option>
                    </select>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-gray-700 mb-1">Upload Cover Photo</label>
                    <input type="file" wire:model="thumbnail" class="w-full text-gray-600">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" @click="open = false" class="px-8 py-1 bg-transparent border border-black shadow-md rounded-2xl transform transition duration-300 hover:scale-105 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-8 py-1 bg-black shadow-md text-white rounded-2xl transform transition duration-300 hover:scale-105">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
