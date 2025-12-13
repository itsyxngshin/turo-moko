<div x-data="{ open: @entangle('open') }">
    <button @click="open = true" class="text-gray-500 hover:text-blue-600 transition">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
    </button>

    <div x-show="open" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
        
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl py-6" @click.away="open = false">
            
            <div class="flex justify-between items-center border-b pb-3 px-6">
                <h2 class="text-xl text-black font-semibold">Edit Course</h2>
                <button @click="open = false" class="text-gray-500 hover:text-red-500">&times;</button>
            </div>

            <form wire:submit.prevent="saveCourse" class="mt-2 space-y-6 px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-1">Course Title</label>
                            <input type="text" wire:model="course_title" class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                            @error('course_title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-1">Category</label>
                            <select wire:model="category" class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <div class="w-1/2">
                                <label class="block text-gray-700 mb-1">Start Date</label>
                                <input type="date" wire:model="start_date" class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 mb-1">End Date</label>
                                <input type="date" wire:model="end_date" class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-1">Visibility</label>
                            <select wire:model="visibility" class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                                <option value="visible">Visible</option>
                                <option value="hidden">Hidden</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-1">Student Limit</label>
                            <input type="number" wire:model="student_limit" class="w-full text-black px-3 py-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-300">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 mb-1">Description</label>
                            <textarea wire:model="background" class="w-full px-3 py-2 border text-black rounded-lg focus:outline-none focus:ring focus:ring-blue-300" rows="5"></textarea>
                        </div>

                        <div x-data="{
                            imagePreview: @entangle('existingThumbnail'),
                            dragging: false,
                            handleDrop(event) {
                                this.dragging = false;
                                const file = event.dataTransfer.files[0];
                                if (!file) return;
                                this.uploadFile(file);
                            },
                            handleFileSelect(event) {
                                const file = event.target.files[0];
                                if (!file) return;
                                this.uploadFile(file);
                            },
                            uploadFile(file) {
                                const reader = new FileReader();
                                reader.onload = (e) => { this.imagePreview = e.target.result; };
                                reader.readAsDataURL(file);
                                @this.upload('thumbnail', file);
                            },
                            removeImage() {
                                this.imagePreview = null;
                                @this.set('thumbnail', null);
                                @this.set('existingThumbnail', null); // Clear existing if removed
                            }
                        }">
                            <label class="block text-gray-700 mb-1">Cover Photo</label>
                            
                            <div 
                                class="relative flex items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition"
                                :class="{ 'bg-gray-100 border-blue-400': dragging }"
                                @dragover.prevent="dragging = true"
                                @dragleave.prevent="dragging = false"
                                @drop.prevent="handleDrop($event)"
                                @click="$refs.fileInput.click()"
                            >
                                <template x-if="imagePreview">
                                    <div class="relative w-full h-full group">
                                        <img :src="imagePreview" class="w-full h-full object-cover rounded-lg">
                                        
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition rounded-lg">
                                            <span class="text-white font-medium">Change Photo</span>
                                        </div>

                                        <button 
                                            @click.stop="removeImage" 
                                            type="button"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 shadow-md z-10"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                </template>

                                <template x-if="!imagePreview">
                                    <div class="text-center p-4">
                                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="mt-2 text-sm text-gray-600">Click to upload or drag & drop</p>
                                        <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                    </div>
                                </template>

                                <div wire:loading wire:target="thumbnail" class="absolute inset-0 bg-white bg-opacity-80 flex flex-col items-center justify-center rounded-lg z-20">
                                    <svg class="animate-spin h-8 w-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                    <span class="text-sm font-medium text-gray-700">Uploading...</span>
                                </div>
                            </div>

                            <input 
                                type="file" 
                                x-ref="fileInput"
                                class="hidden" 
                                accept="image/png, image/jpeg, image/jpg"
                                @change="handleFileSelect($event)"
                            >
                            @error('thumbnail') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t mt-4">
                    <button type="button" @click="open = false" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                    
                    <button type="submit" 
                            wire:loading.attr="disabled" 
                            class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                        <span wire:loading.remove wire:target="saveCourse">Save Changes</span>
                        <span wire:loading wire:target="saveCourse">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>