<div x-data="editModuleModal()" x-init="init()" class="relative">
    <!-- Three-dot Button -->
    <button @click="showModal = true" class="w-full text-left px-4 py-2 hover:bg-gray-100">
        Edit Module
    </button>

    <!-- Edit Module Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div 
            x-show="showModal" 
            @click.away="showModal = false"
            class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6"
        >

            <!-- HEADER -->
            <div class="flex justify-between border-b pb-2 mb-4">
                <h2 class="text-xl font-semibold">Edit Module</h2>
                <button @click="showModal = false" class="text-gray-500 hover:text-black">✖</button>
            </div>

            <!-- FORM -->
            <form wire:submit.prevent="updateModule" class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- LEFT COLUMN -->
                <div>
                    <h2 class="text-lg text-gray-700 font-semibold mb-4">Module Information</h2>

                    <!-- MODULE NUMBER -->
                    <div class="mb-4">
                        <label class="block text-md mb-1 text-black">Module Number</label>
                        <input 
                            type="number" 
                            wire:model="module_number"
                            class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-black focus:outline-none"
                        >
                        @error('module_number') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- MODULE TITLE -->
                    <div class="mb-4">
                        <label class="block text-md mb-1 text-black">Module Title</label>
                        <input 
                            type="text" 
                            wire:model="module_title"
                            class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-black focus:outline-none"
                        >
                        @error('module_title') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>

<!-- ATTACHMENT UPLOAD -->
<div x-data="editFileUpload(@js($existingAttachment))" class="w-full text-left">
    <label class="text-black font-medium">Attachment</label>

    <!-- Upload Box -->
    <div 
        :class="dragging ? 'bg-gray-100' : 'bg-gray-50'"
        class="relative mt-2 my-3 flex flex-col items-center justify-center w-full min-h-[140px] border rounded-md border-gray-300 p-4 cursor-pointer"
        @click="$refs.fileInput.click()"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="handleDrop($event)"
    >
        <!-- Preview -->
        <template x-if="filePreview || currentFile">
    <div class="flex flex-col items-center text-gray-600">
        <span class="text-4xl" x-text="fileIcon"></span>
<p class="text-sm mt-2 break-all" x-text="displayName()"></p>

        <button 
            @click.stop="removeFile()" 
            class="mt-2 text-red-500 underline text-sm"
        >
            Remove
        </button>
    </div>
</template>


        <!-- Placeholder -->
        <div x-show="!filePreview && !currentFile" class="flex flex-col items-center text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                <path fill="#4b5563" d="M23 18h-3v-3h-2v3h-3v2h3v3h2v-3h3M6 2a2 2 0 0 0-2 2v16c0 1.11.89 2 2 2h7.81c-.36-.62-.61-1.3-.73-2H6V4h7v5h5v4.08c.33-.05.67-.08 1-.08c.34 0 .67.03 1 .08V8l-6-6M8 12v2h8v-2m-8 4v2h5v-2Z"/>
            </svg>
            <p class="mt-2 text-sm text-gray-600 text-center">
                Drag & drop a file or <span class="text-blue-500">click here to upload</span>
            </p>
        </div>

        <!-- Uploading Indicator -->
<p x-show="uploading" class="mt-2 text-gray-500 italic text-sm">Uploading...</p>
    </div>

    <!-- Hidden File Input -->
    <input 
        type="file" 
        wire:model="attachments"
        x-ref="fileInput"
        hidden
        @change="showPreview($event)"
        accept="image/*,video/*,.pdf,.doc,.docx"
    >
    @error('attachments') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('editFileUpload', (existingFile, existingOriginalName = null) => ({
        
        // STATE
        dragging: false,
        filePreview: false,
        fileName: '',       // New file name
        fileIcon: '',
        uploading: false,

        // Existing file info
        currentFile: existingFile || null,                        // URL/path stored in DB
        currentFileOriginalName: existingOriginalName || (existingFile ? existingFile.split('/').pop() : ''),

        init() {
            // If there’s an existing file, show it
            if (this.currentFile) {
                this.filePreview = true;
                this.fileName = ''; // No new file yet
                this.fileIcon = this.getIcon(this.currentFileOriginalName);
            }

            // Listen to Livewire upload events
            this.$wire.on('upload:started', () => { this.uploading = true; });
            this.$wire.on('upload:finished', () => { this.uploading = false; });
            this.$wire.on('upload:errored', () => { this.uploading = false; });
        },

        // Get icon based on file extension
        getIcon(name) {
            const ext = name.split('.').pop().toLowerCase();
            if (['jpg','jpeg','png','gif','webp'].includes(ext)) return '🖼️';
            if (['mp4','mov','avi','mkv','webm'].includes(ext)) return '🎞️';
            if (['pdf'].includes(ext)) return '📕';
            if (['doc','docx'].includes(ext)) return '📘';
            return '📄';
        },

        // Show preview of selected file
        showPreview(event) {
    const file = event.target.files[0];
    if (!file) return;

    this.filePreview = true;
    this.fileName = file.name;
    this.fileIcon = this.getIcon(file.name);

    this.currentFile = null;
    this.currentFileOriginalName = '';

    this.uploading = true;

    // Use Livewire upload with proper callbacks
    this.$wire.upload('attachments', file, {
        start: () => { this.uploading = true },
        finish: () => { this.uploading = false },
        error: () => { 
            this.uploading = false;
            console.error('Upload failed'); 
        },
    });

    this.$wire.set('removeAttachment', false);
},

        // Drag & drop
        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (!file) return;

            this.$refs.fileInput.files = event.dataTransfer.files;
            this.showPreview({ target: { files: [file] } });
        },

        // Remove file
        removeFile() {
    if (this.uploading) return; // prevent removal during upload

    this.filePreview = false;
    this.fileName = '';
    this.fileIcon = '';

    this.$refs.fileInput.value = null;

    if (this.currentFile) {
        this.$wire.set('removeAttachment', true);
    }

    this.currentFile = null;
    this.currentFileOriginalName = '';

    this.$wire.set('attachments', null);
},

        // Get displayed name in preview
        displayName() {
            return this.fileName || this.currentFileOriginalName;
        }
    }));
});
</script>


                </div>

                <!-- RIGHT COLUMN -->
                <div>
                    <!-- MODULE CONTENT -->
                    <div class="mb-4">
                        <label class="block text-md mb-1 text-black">Module Content</label>
                        <textarea 
                            wire:model="content"
                            class="w-full border rounded p-2 h-80"
                        ></textarea>
                        @error('content') 
                            <span class="text-red-500 text-sm">{{ $message }}</span> 
                        @enderror
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="col-span-1 md:col-span-2 flex justify-end gap-x-3 mt-6">
                    <button 
                        @click="showModal = false"
                        type="button"
                        class="px-5 py-2 border border-gray-400 rounded-full hover:bg-gray-100"
                    >
                        Cancel
                    </button>

                    <!-- UPDATE BUTTON -->
                    <button 
                        type="submit" 
                        wire:click="updateModule"
                       x-bind:disabled="uploading"
                        class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Update Module
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('editModuleModal', () => ({
        showModal: false,

        init() {
            // Listen for Livewire dispatch event
            this.$root.addEventListener('module-updated', (e) => {
                const { status, message } = e.detail;

                Swal.fire({
                    icon: status || 'success',               // success or error
                    title: status === 'success' ? 'Success' : 'Error',
                    text: message || '',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                }).then(() => {
                    if (status === 'success') {
                        location.reload(); // reload page after successful update
                    }
                });
            });
        }
    }));
});
</script>
