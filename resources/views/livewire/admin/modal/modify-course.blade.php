<div x-data="{ open: false }">
    <!-- Trigger button -->
    <button 
        @click="open = true"
        class="text-blue px-4 py-2 rounded-lg hover:underline">
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
                       wire:model="name" 
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

            {{-- ENTER adds tag --}}
            wire:keydown.enter.prevent="addTag"

            {{-- SPACE adds tag --}}
            x-data
            x-on:keydown.space.prevent="$wire.addTag()"

            class="flex-1 border-0 text-black focus:ring-0 p-1"
            placeholder="Type a tag and press space/enter"
        >
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
                    <option value="public">Public</option>
                    <option value="private">Private</option>
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


            <div class="relative"> 
                <label class="block text-gray-700">Assigned Implementer</label> 
                <!-- Disabled input to show assigned implementer --> 
                 <input type="text" class="w-full text-gray-500 border rounded-lg px-3 py-2 bg-gray-100 cursor-not-allowed" 
                    value="{{ 
                            ($course->implementer->profile->first_name ?? '') . ' ' . ($course->implementer->profile->middle_name ?? '') . ' ' . 
                            ($course->implementer->profile->last_name ?? '') . ' (' . 
                            ($course->implementer->username ?? '') . ')' }}" 
                            disabled /> 
                 <!-- Hidden field to keep the implementer ID --> 
                  <input type="hidden" wire:model="assignedImplementer" /> </div>


            <div x-data="fileUpload(@entangle('existingThumbnail'))">
    
            <!--COVER PHOTO-->
            <label class="text-black">Cover Photo</label>
            <div 
                x-bind:class="dragging ? 'bg-gray-200' : 'bg-gray-50'"
                class="relative mt-2 my-3 flex items-center justify-center w-full border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer"
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
                        <p class="mt-2 text-sm text-gray-600 text-center">
                            Drag & drop a photo or <span @click.stop="$refs.fileInput.click()" class="text-blue-500">click here to upload</span>
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
                @change="showPreview($event)">
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
function fileUpload(existingImage) {
    return {
        imagePreview: existingImage, // start with Livewire value
        dragging: false,

        init() {
            // Watch Livewire property changes
            this.$watch('$wire.existingThumbnail', value => {
                if (value) this.imagePreview = value;
            });
        },

        showPreview(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => this.imagePreview = e.target.result;
            reader.readAsDataURL(file);
            @this.set('thumbnail', file); // set Livewire property
        },

        removeImage() {
            this.imagePreview = null;
            @this.set('thumbnail', null);
        },

        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (!file) return;
            this.showPreview({ target: { files: [file] } });
        }
    }
}
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








