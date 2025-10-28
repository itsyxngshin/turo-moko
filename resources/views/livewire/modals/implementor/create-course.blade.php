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
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border rounded-lg" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                            @click="$refs.fileInput.click()"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="handleDrop($event)"
                        >

                            <!-- Preview -->
                            <template x-if="imagePreview">
                                <div class="relative w-full h-40 flex items-center justify-center">
                                    <img :src="imagePreview" class="w-full h-full object-cover rounded-lg">
                                    <button 
                                        @click.stop="removeImage" 
                                        class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xl p-1 rounded-full shadow-md">
                                        &times;
                                    </button>
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
                            @change="showPreview($event)"
                        >

                        <div wire:loading wire:target="thumbnail" class="text-sm text-gray-500 mt-1">
                            Uploading...
                        </div>
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
        window.addEventListener('course-saved', () => {
            document.querySelector('[x-data]').__x.$data.open = false;
        });
    </script>
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
    document.addEventListener('DOMContentLoaded', () => {
        // Close modal after save
        Livewire.on('course-saved', () => {
            document.querySelector('[x-ref=createCourseModal]').__x.$data.open = false;
        });

        // SweetAlert success popup
        Livewire.on('swal:success', (event) => {
            Swal.fire({
                icon: 'success',
                iconColor: '#000000',
                title: 'Success!',
                text: 'Course Created Successfully!',
                confirmButtonColor: '#000000',
                confirmButtonText: 'OK'
            });
        });

        // SweetAlert error popup
        Livewire.on('swal:error', (event) => {
            Swal.fire({
                icon: 'error',
                iconColor: '#000000',
                title: 'Error!',
                text: 'Something Went Wrong! Please Try Again.',
                confirmButtonColor: '#000000',
                confirmButtonText: 'Retry'
            });
        });
    });
</script>

</div>
