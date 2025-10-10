<div x-data="{ open: false }">
    <!-- Trigger button -->
    <div 
        @click="open = true" 
        class="flex flex-col items-center justify-center border border-gray-200 p-8 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition"
    >
        <!-- Chat Bubble Icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-blue-500 mb-2" viewBox="0 0 48 48" fill="currentColor">
            <path fill-rule="evenodd" d="M33 18.535c1.163.348 2 .465 2 .465v-3h2a5 5 0 0 0 0-10h-5.764A5.236 5.236 0 0 0 26 11.236c0 4.518 4.348 6.506 7 7.299M40 11a3 3 0 0 1-3 3h-4v2.435a13 13 0 0 1-1.603-.667C29.414 14.774 28 13.36 28 11.236A3.236 3.236 0 0 1 31.236 8H37a3 3 0 0 1 3 3m-25.183 6.993A4.998 4.998 0 0 1 14.998 8h3.169A4.833 4.833 0 0 1 23 12.833c0 4.042-3.63 5.89-6 6.667c-1.148.376-2 .5-2 .5v-2zM17 16.071l-2.11-.076A2.998 2.998 0 0 1 14.997 10h3.169A2.833 2.833 0 0 1 21 12.833c0 1.915-1.217 3.17-2.924 4.06c-.36.188-.725.348-1.076.484zM28 24c0 2.21-1.79 4-4 4s-4-1.79-4-4s1.79-4 4-4s4 1.79 4 4m-2 0a1.999 1.999 0 1 1-4 0a1.999 1.999 0 1 1 4 0m-7 2c0 2.21-1.79 4-4 4s-4-1.79-4-4s1.79-4 4-4s4 1.79 4 4m-2 0a1.999 1.999 0 1 1-4 0a1.999 1.999 0 1 1 4 0M6 36.546C6 33.522 11.996 32 15 32c.585 0 1.284.058 2.03.173C18.371 31.19 20.827 30 24 30s5.629 1.19 6.971 2.173A13.6 13.6 0 0 1 33 32c3.004 0 9 1.523 9 4.545V42H6zm15.652-.523c.348.324.348.493.348.522V40H8v-3.455c0-.03 0-.198.348-.522c.363-.339.962-.7 1.776-1.03C11.756 34.333 13.75 34 15 34s3.244.333 4.876.993c.814.33 1.413.691 1.776 1.03m6.49-3.167A10.4 10.4 0 0 0 24 32c-1.656 0-3.064.386-4.141.856C22.074 33.6 24 34.832 24 36.546c0-1.714 1.926-2.945 4.142-3.69M40 36.546c0-.03 0-.199-.348-.523c-.363-.339-.962-.7-1.776-1.03C36.244 34.333 34.25 34 33 34s-3.244.333-4.876.993c-.814.33-1.413.691-1.776 1.03c-.348.324-.348.493-.348.522V40h14zM33 30c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m0-2a1.999 1.999 0 1 0 0-4a1.999 1.999 0 1 0 0 4" clip-rule="evenodd"/>
        </svg>
        <span class="text-sm mt-2 font-medium text-gray-700">Announcement</span>
    </div>
    

    <!-- Modal Background -->
    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
    >
        <!-- Modal Content -->
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6">
            <!-- Modal Header -->
            <div class="flex justify-between border-b text-left pb-2 mb-4">
                <h2 class="text-xl font-semibold">Create Announcement</h2>
                <button @click="open = false" class="text-gray-500 hover:text-black">✖</button>
            </div>

            <!-- Form -->
            <h1 class="text-lg text-left font-semibold mb-1">{{ $course->name ?? '--' }}</h1>

            <!-- General Section -->
            <div class="border rounded-lg">
                <div class="flex justify-between w-full px-4 py-3 text-gray-700 font-semibold border-b">
                    <span>General</span>
                </div>

                <div class="p-4 space-y-4">
                    <!-- Title -->
                    <div class="text-left">
                        <label class="block text-sm text-black mb-1">Title</label>
                        <input 
                            type="text" 
                            wire:model="title"
                            placeholder="Topic"
                            class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-black focus:outline-none"
                        >
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="text-left">
                        <label class="block text-sm text-black mb-1">Description</label>
                        <textarea 
                            rows="3" 
                            placeholder="Description"
                            class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-black focus:outline-none resize-none"
                        ></textarea>
                    </div>

                   <!-- File Upload -->
                        <div x-data="fileUpload()" class="w-full text-left">
                            <label class="text-black">Additional Files</label><br>

                            <!-- Upload Box -->
                            <div 
                                x-bind:class="dragging ? 'bg-gray-100' : 'bg-gray-50'"
                                class="relative mt-2 my-3 flex items-center justify-center w-full border rounded-md border-gray-300 p-4 cursor-pointer"
                                @click="$refs.fileInput.click()"
                                @dragover.prevent="dragging = true"
                                @dragleave.prevent="dragging = false"
                                @drop.prevent="handleDrop($event)"
                            >

                                <!-- Preview (if file is an image) -->
                                <template x-if="filePreview && isImage">
                                    <div class="relative w-full h-40 flex items-center justify-center">
                                        <img :src="filePreview" class="w-full h-full object-cover rounded-lg">
                                        <button 
                                            @click.stop="removeFile" 
                                            class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xl p-1 rounded-full shadow-md">
                                            &times;
                                        </button>
                                    </div>
                                </template>

                                <!-- Preview (if file is not an image) -->
                                <template x-if="filePreview && !isImage">
                                    <div class="flex flex-col items-center text-gray-600">
                                        <span class="text-4xl">📄</span>
                                        <p class="text-sm mt-2">File selected</p>
                                        <button 
                                            @click.stop="removeFile" 
                                            class="mt-2 text-red-500 underline text-sm">
                                            Remove
                                        </button>
                                    </div>
                                </template>

                                <!-- Placeholder (shows when no file selected) -->
                                <template x-if="!filePreview">
                                    <div class="flex flex-col items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="#4b5563" d="M23 18h-3v-3h-2v3h-3v2h3v3h2v-3h3M6 2a2 2 0 0 0-2 2v16c0 1.11.89 2 2 2h7.81c-.36-.62-.61-1.3-.73-2H6V4h7v5h5v4.08c.33-.05.67-.08 1-.08c.34 0 .67.03 1 .08V8l-6-6M8 12v2h8v-2m-8 4v2h5v-2Z"/></svg>
                                        <p class="mt-2 text-sm text-gray-600 text-center">
                                            Drag & drop a file or <span class="text-blue-500">click here to upload</span>
                                        </p>
                                    </div>
                                </template>
                            </div>

                            <!-- Hidden File Input -->
                            <input 
                                type="file" 
                                wire:model="attachment" 
                                x-ref="fileInput" 
                                hidden 
                                @change="showPreview($event)"
                            >

                            <!-- Livewire Upload Progress -->
                            <div wire:loading wire:target="attachment" class="text-sm text-gray-500 mt-1">
                                Uploading...
                            </div>
                            @error('attachment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <script>
                            function fileUpload() {
                                return {
                                    dragging: false,
                                    filePreview: null,
                                    isImage: false,
                                    showPreview(event) {
                                        const file = event.target.files[0];
                                        if (!file) return;

                                        this.isImage = file.type.startsWith('image/');
                                        if (this.isImage) {
                                            this.filePreview = URL.createObjectURL(file);
                                        } else {
                                            this.filePreview = true; // just flag that a file exists
                                        }
                                    },
                                    handleDrop(event) {
                                        this.dragging = false;
                                        const file = event.dataTransfer.files[0];
                                        if (!file) return;

                                        this.$refs.fileInput.files = event.dataTransfer.files;
                                        this.showPreview({ target: { files: [file] } });
                                    },
                                    removeFile() {
                                        this.filePreview = null;
                                        this.isImage = false;
                                        this.$refs.fileInput.value = '';
                                    }
                                };
                            }
                        </script>

                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="flex justify-end gap-x-3 mt-6">
                <button 
                    @click="open = false"
                    class="px-5 py-2 border border-gray-400 rounded-full hover:bg-gray-100"
                >
                    Discard
                </button>
                <button 
                    class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800"
                >
                    Save and display
                </button>
            </div>
        </div>
    </div>
</div>

