<div x-data="{ open: false }" x-ref="createCourseModal">

    <!-- Trigger Button -->
    <button 
        @click="open = true"
    class="bg-black text-white px-5 py-2 rounded-full text-sm font-medium shadow-sm hover:bg-gray-900 transition">
        Create Course
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
            @click.away="open = false"
        >
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-3 px-6">
                <h2 class="text-xl font-semibold">Create New Course</h2>
                <button @click="open = false" class="text-gray-500 hover:text-red-500">&times;</button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="saveCourse" class="mt-4 grid grid-cols-2 gap-6 px-8">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-1">Course Title</label>
                        <input type="text" wire:model="course_title" class="w-full px-3 py-2 border rounded-lg" required>
                        @error('course_title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-1">Category</label>
                        <select wire:model="category_id" class="w-full px-3 py-2 border rounded-lg" required>
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

                    <div class="flex gap-3">
                        <div class="w-full">
                            <label class="block text-gray-700 mb-1">Start Date</label>
                            <input 
                                type="date" 
                                wire:model="start_date" 
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                                required>
                        </div>

                        <div class="w-full">
                            <label class="block text-gray-700 mb-1">End Date</label>
                            <input 
                                type="date" 
                                wire:model="end_date" 
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                                required>
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

                    <div>
                        <label class="block text-gray-700 mb-1">Student Limit (Max 20)</label>
                        <input 
                            type="number" 
                            wire:model="student_limit" 
                            min="1" 
                            max="20"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300"
                            required
                        >
                        @error('student_limit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-1">Course Description</label>
                        <textarea wire:model="background" rows="5" class="w-full px-3 py-2 border rounded-lg"></textarea>
                    </div>

                    <!-- File Upload -->
                    <div x-data="fileUpload()" class="w-full">
                        <label class="text-black">Cover Photo</label><br>

                        <!-- Upload Box -->
                        <div 
                            x-bind:class="dragging ? 'bg-gray-200' : 'bg-gray-50'"
                            class="relative mt-2 my-3 flex items-center justify-center w-full border-2 border-dashed border-gray-300 rounded-lg p-4 cursor-pointer"
                            :class="{'border-green-500 bg-green-50': imagePreview}"
                            @click="$refs.fileInput.click()"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="handleDrop($event)"
                        >
                            <!-- Uploading State -->
                            <div wire:loading wire:target="thumbnail" class="flex flex-col items-center justify-center space-y-2">
                                <svg class="animate-spin h-12 w-12 text-orange-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-orange-500 text-sm font-medium">Uploading...</span>
                            </div>

                            <!-- Content (hidden during upload) -->
                            <div wire:loading.remove wire:target="thumbnail" class="w-full">
                                <!-- Preview with Success Indicator -->
                                <template x-if="imagePreview">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div class="relative w-full h-40 flex items-center justify-center">
                                            <img :src="imagePreview" class="w-full h-full object-cover rounded-lg">
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-green-600 text-sm font-medium">Photo uploaded successfully</span>
                                        
                                        <!-- Remove File Button -->
                                        <button 
                                            type="button"
                                            @click.stop="removeImage(); $wire.clearThumbnail()" 
                                            class="mt-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm rounded-lg transition">
                                            Remove file
                                        </button>
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
                                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG (Max 2MB)</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- Hidden File Input -->
                        <input 
                            type="file" 
                            wire:model="thumbnail" 
                            x-ref="fileInput" 
                            hidden 
                            accept="image/*"
                            @change="showPreview($event)"
                        >

                        @error('thumbnail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Buttons (Full Width) -->
                <div class="col-span-2 flex justify-end space-x-2 pt-4 border-t mt-2">
                    <button type="button" @click="open = false" class="px-8 py-1 border border-black rounded-2xl">Cancel</button>
                    <button type="submit" class="px-8 py-1 bg-black text-white rounded-2xl">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <!-- Alpine listens for Livewire event -->
    
    <script>
function fileUpload() {
    return {
        dragging: false,
        imagePreview: null, // always empty at first
        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.showFile(file);
                this.$refs.fileInput.files = event.dataTransfer.files;
                this.$refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        },
        showPreview(event) {
            const file = event.target.files[0];
            if (file) {
                this.showFile(file);
            }
        },
        showFile(file) {
            const reader = new FileReader();
            reader.onload = e => {
                this.imagePreview = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        removeImage() {
            this.imagePreview = null;
            this.$refs.fileInput.value = null;
        }
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.addEventListener('course-saved', () => {
    document.querySelector('[x-ref=createCourseModal]').__x.$data.open = false;
});

window.addEventListener('swal:success', (event) => {
    Swal.fire({
        icon: 'success', // lowercase 'success' for a check icon
            title: 'Success!',
            text: 'Course Updated Successfully!', // optional message passed from Livewire
            confirmButtonText: 'OK',
            confirmButtonColor: '#000000ff', // green button
            background: '#ffffffff', // light green background
            iconColor: '#000000ff',
    }).then(() => {
        // Refresh the page after user clicks OK
        window.location.reload();
    });
});

window.addEventListener('swal:error', (event) => {
    Swal.fire({
        icon: 'error',
        title: event.detail.title || 'Error!',
        text: event.detail.text || 'Something went wrong',
        confirmButtonColor: '#000000ff', // green button
            background: '#ffffffff', // light green background
            iconColor: '#000000ff',
    });
});
</script>


</div>
