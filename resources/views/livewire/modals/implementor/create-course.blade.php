<div x-data="{ open: false }">
    <!-- Trigger Button -->
    <button 
        @click="open = true"
        class="bg-transparent text-black px-4 py-2 rounded-lg border hover:bg-black hover:text-white transform transition duration-300">
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
                <h2 class="text-xl font-semibold">Create New Course</h2>
                <button @click="open = false" class="text-gray-500 hover:text-red-500">&times;</button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveCourse" class="mt-4 space-y-4 px-8">
    <div>
        <label class="block text-gray-700 mb-1">Course Title</label>
        <input type="text" wire:model="name" class="w-full px-3 py-2 border rounded-lg">
        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-gray-700 mb-1">Category</label>
        <select wire:model="category_id" class="w-full px-3 py-2 border rounded-lg">
            <option value="">Select category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
            @endforeach
        </select>
        @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

<div>
    <label class="block text-gray-700 mb-1">Tags</label>
    <div class="flex flex-wrap items-center gap-2 p-2 border rounded-lg">
        @foreach($tags as $tag)
            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-sm flex items-center gap-1">
                {{ $tag }}
                <button type="button" wire:click="removeTag('{{ $tag }}')" class="text-red-500">&times;</button>
            </span>
        @endforeach

        <input 
            type="text" 
            wire:model="tagInput" 
            wire:keydown.enter.prevent="addTag"
            wire:keydown.space.prevent="addTag"
            class="flex-1 border-0 focus:ring-0 p-1"
            placeholder="Type a tag and press space/enter"
        >
    </div>
</div>



<div class="flex w-full gap-2 justify-between">
    <div class="w-full">
    <label class="block text-gray-700 mb-1">Start Date</label>
    <input 
        type="date" 
        wire:model="start_date" 
        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
    >
</div>
   <div class="w-full">
    <label class="block text-gray-700 mb-1">End Date</label>
        <input 
            type="date" 
            wire:model="end_date" 
            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
        >
    </div> 
</div>



    <div>
        <label class="block text-gray-700 mb-1">Course Description</label>
        <textarea wire:model="background" class="w-full px-3 py-2 border rounded-lg"></textarea>
    </div>



    <div>
        <label class="block text-gray-700 mb-1">Upload Cover Photo</label>
        <input type="file" wire:model="thumbnail" class="w-full text-gray-600">
        @error('thumbnail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Buttons -->
    <div class="flex justify-end space-x-2 pt-4">
        <button type="button" @click="open = false" class="px-8 py-1 border border-black rounded-2xl">Cancel</button>
        <button type="submit" class="px-8 py-1 bg-black text-white rounded-2xl">Save</button>
    </div>
</form>

        </div>
    </div>

    <!-- Alpine listens for Livewire event -->
    <script>
        window.addEventListener('course-saved', () => {
            document.querySelector('[x-data]').__x.$data.open = false;
        });
    </script>
</div>
