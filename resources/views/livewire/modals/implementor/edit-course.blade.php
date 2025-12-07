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
            class="bg-white rounded-2xl shadow-lg w-full max-w-4xl py-6"
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
            <form wire:submit.prevent="saveCourse" class="mt-2 space-y-6 px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- LEFT COLUMN -->
        <div class="space-y-4">
            <!-- Course Title -->
            <div>
                <label class="block text-gray-700 mb-1">Course Title</label>
                <input type="text" 
                       wire:model="course_title" 
                       class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300" 
                       placeholder="Enter course title">
            </div>

            <!-- Category -->
            <div>
                <label class="block text-gray-700 mb-1">Category</label>
                <select wire:model="category" 
                        class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tags -->
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
                        placeholder="Type a tag and press space/enter">
                </div>
            </div>

            <!-- Start & End Dates -->
            <div class="flex gap-2">
                <div class="w-1/2">
                    <label class="block text-gray-700 mb-1">Start Date</label>
                    <input type="date" 
                           wire:model="start_date" 
                           class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                </div>
                <div class="w-1/2">
                    <label class="block text-gray-700 mb-1">End Date</label>
                    <input type="date" 
                           wire:model="end_date" 
                           class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                </div>
            </div>

            <!-- Visibility -->
            <div>
                <label class="block text-gray-700 mb-1">Visibility</label>
                <select wire:model="visibility" 
                        class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                    <option value="visible">Visible</option>
                    <option value="hidden">Hidden</option>
                </select>
            </div>

            <!-- Student Limit -->
            <div>
                <label class="block text-gray-700 mb-1">Student Limit</label>
                <input type="number" 
                       wire:model="student_limit" 
                       class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300" 
                       placeholder="e.g. 50">
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-4">
            <!-- Description -->
            <div>
                <label class="block text-gray-700 mb-1">Description</label>
                <textarea wire:model="background" 
                          class="w-full px-3 py-2 border text-black rounded-lg focus:outline-none focus:ring focus:ring-blue-300" 
                          rows="5" 
                          placeholder="Enter course description"></textarea>
            </div>

            <!-- Cover Photo Upload -->
<div x-data="{open: false, imagePreview: '{{$existingThumbnail}}', dragging: false,
        removeImage() { 
            this.imagePreview = null; 
            @this.removeExistingThumbnail = true; <!-- optionally mark removal -->
        } }">
                <label for="courseThumbnail" class="text-black">Cover Photo</label>
                <div x-data="{ dragging: false }"
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
                            <button @click.stop="removeImage" 
                                    class="absolute top-2 right-2 text-white text-xl p-1 rounded-full shadow-md">&times;</button>
                        </div>
                    </template>

                    <!-- Placeholder -->
                    <template x-if="!imagePreview">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" class="text-gray-500">
                                <path fill="currentColor" d="M13 19c0 .7.13 1.37.35 2H5a2 2 0 0 1-2-2V5c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v8.35c-.63-.22-1.3-.35-2-.35V5H5v14zm.96-6.71l-2.75 3.54l-1.96-2.36L6.5 17h6.85c.4-1.12 1.12-2.09 2.05-2.79zM20 18v-3h-2v3h-3v2h3v3h2v-3h3v-2z"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600 text-center">
                                Drag & drop a photo or <span class="text-blue-500">click here to upload</span>
                            </p>
                        </div>
                    </template>
                </div>

                <input 
                    type="file" 
                    wire:model="thumbnail" 
                    x-ref="fileInput" 
                    hidden 
                    accept="image/*"
                   @change="imagePreview = URL.createObjectURL($event.target.files[0]); @this.removeExistingThumbnail = false">
            </div>
        </div>
    </div>

    <!-- BUTTONS -->
    <div class="flex justify-end space-x-2 pt-4">
        <button type="button" 
                @click="open = false" 
                class="px-8 py-1 bg-transparent border border-black shadow-md rounded-2xl transform transition duration-300 text-black hover:scale-105 hover:bg-gray-200">
            Cancel
        </button>
        <button type="submit" 
                class="px-8 py-1 bg-black shadow-md text-white rounded-2xl transform transition duration-300 hover:scale-105">
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
    Alpine.data('fileUpload', (initialImage = null) => ({
    dragging: false,
    imagePreview: initialImage,

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

        this.$refs.fileInput.files = event.dataTransfer.files; // Update input
        this.showPreview({ target: { files: [file] } });
    },

    removeImage() {
        this.imagePreview = null;
        this.$refs.fileInput.value = null;

        @this.set('thumbnail', null);
        @this.set('removeExistingThumbnail', true);
    }
}));

});

</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
function swalListener() {
    return {}
}

document.addEventListener('alpine:init', () => {
    window.addEventListener('swal', event => {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Course Updated Successfully!',
            confirmButtonText: 'OK',
            confirmButtonColor: '#000000ff',
            background: '#ffffffff',
            iconColor: '#000000ff',
        }).then(() => {
            location.reload();
        });
    });
});
</script>








