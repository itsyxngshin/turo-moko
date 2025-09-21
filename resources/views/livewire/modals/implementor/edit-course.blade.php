<div x-data="{ open: false }">
    <!-- Trigger button -->
    <button 
        @click="open = true"
        class="bg-black text-white px-4 py-2 rounded-lg">
        Edit
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
             @click.away="
                if (unsavedChanges) {
                    if (confirm('You have unsaved changes. Are you sure you want to close?')) {
                        open = false;
                        unsavedChanges = false;
                    }
                } else {
                    open = false;
                }
            "
        >
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-3 px-6">
                <h2 class="text-xl text-black font-semibold">Edit Course</h2>
                <button @click="open = false" class="text-gray-500 hover:text-red-500">&times;</button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveCourse" class="mt-2 space-y-1 px-8">
                <!-- Course Title -->
                <div>
                    <label class="block text-gray-700 mb-1">Course Title</label>
                    <input type="text" wire:model="name" class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300" placeholder="Enter course title">
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-gray-700 mb-1">Description</label>
                    <textarea wire:model="background" class="w-full px-3 py-2 border text-black rounded-lg focus:outline-none focus:ring focus:ring-blue-300" rows="3" placeholder="Enter course description"></textarea>
                </div>

                <!-- Category -->
                <div >
                    <label class="block text-gray-700 mb-1">Category</label>
                    <select wire:model="category" class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tagsSection" class="text-black">Tag/s</label>
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
                            class="flex-1 text-black border-0 focus:ring-0 p-1"
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
                        class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                    >
                </div>
                <div class="w-full">
                    <label class="block text-gray-700 mb-1">End Date</label>
                        <input 
                            type="date" 
                            wire:model="end_date" 
                            class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                        >
                    </div> 
                </div>


                <!-- File Upload -->
                <div x-data="fileUpload()" class="w-full">
                    <label for="courseThumbnail" class="text-black">Cover Photo</label><br>

                    <!-- Upload Box -->
                    <div 
                        x-bind:class="dragging ? 'bg-gray-200' : 'bg-gray-50'"
                        class="relative mt-2 my-3 flex items-center justify-center w-full border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer"
                        @click="$refs.fileInput.click()"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="handleDrop($event)">

                        <!-- Preview -->
                        <template x-if="imagePreview">
                            <div class="relative w-full h-40 flex items-center justify-center">
                                <img :src="imagePreview" class="w-full h-full object-cover rounded-lg">
                                <button @click.stop="removeImage" class="absolute top-2 right-2 text-white text-xl p-1 rounded-full shadow-md">&times;</button>
                            </div>
                        </template>

                        <!-- Placeholder -->
                        <template x-if="!imagePreview">
                            <div class="flex flex-col items-center">
                                <p class="mt-2 text-sm text-gray-600 text-center">
                                    Drag & drop a photo or <span class="text-blue-500">click here to upload</span>
                                </p>
                            </div>
                        </template>
                    </div>

                    <!-- Hidden File Input -->
                    <input 
                        type="file" 
                        wire:model="thumbnail" 
                        x-ref="fileInput" 
                        hidden 
                        accept="image/*"
                        @change="showPreview($event)">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2 pt-4">
                    <button type="button" @click="open = false" class="px-8 py-1 bg-transparent border border-black shadow-md rounded-2xl transform transition duration-300 text-black hover:scale-105 hover:bg-gray-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-8 py-1 bg-black shadow-md text-white rounded-2xl transform transition duration-300 hover:scale-105">
                        Save
                    </button>
                </div>
            </form>
           <div x-data="swalListener"></div>
        </div>
    </div>
</div>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fileUpload', () => ({
        dragging: false,
        imagePreview: @js($existingThumbnail ? asset('storage/' . $existingThumbnail) : null),

        showPreview(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = e => this.imagePreview = e.target.result;
            reader.readAsDataURL(file);
        },

        handleDrop(event) {
            event.preventDefault();
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (!file) return;

            this.$refs.fileInput.files = event.dataTransfer.files; // Update actual input
            this.showPreview({ target: { files: [file] } });
        },

        removeImage() {
            this.imagePreview = null;
            this.$refs.fileInput.value = null;
            @this.set('thumbnail', null);
        }
    }))
})

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('alpine:init', () => {
    window.addEventListener('swal', event => {
        Swal.fire({
           icon: 'success', // lowercase 'success' for a check icon
            title: 'Succes!',
            text: 'Course Updated Successfully!', // optional message passed from Livewire
            confirmButtonText: 'OK',
            confirmButtonColor: '#000000ff', // green button
            background: '#ffffffff', // light green background
            iconColor: '#000000ff', // check icon color
        }).then(() => {
            location.reload(); // refresh page after OK
        });
    });
});
</script>








