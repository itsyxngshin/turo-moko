<div 
    x-data="{ open: false }" 
    x-on:open-edit-announcement.window="
        if ($event.detail.id == {{ $announcementId ?? 'null' }}) open = true
    "
>
    <!-- Trigger (in your table or list): -->
    <button 
        @click="$dispatch('open-edit-announcement', { id: {{ $announcementId }} })"
        class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100"
    >
        Edit
    </button>

    <!-- Modal -->
    <div 
        x-show="open" 
        x-transition.opacity 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
    >
        <div 
            @click.away="open = false"
            class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 text-left"
        > <!-- Modal Header -->
            <div class="flex justify-between border-b text-left pb-2 mb-4">
                <h2 class="text-xl font-semibold">{{ $course->course_title ?? '--' }}</h2>
                <button 
        @click="
            Swal.fire({
                title: 'Discard unsaved changes?',
                text: 'Your changes will not be saved.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, discard',
                cancelButtonText: 'No, keep editing'
            }).then((result) => {
                if (result.isConfirmed) {
                    open = false;
                    $wire.resetForm(); // Revert changes only
                }
            });
        " 
        class="text-gray-500 hover:text-black"
    >
        ✖
    </button>

            </div>

            <!-- Edit Form -->
            <form wire:submit.prevent="updateAnnouncement" enctype="multipart/form-data">
                <div class="flex justify-between w-full text-lg text-gray-700 font-semibold">
                    <h2>Edit Announcement</h2>
                </div>

                <div class="p-4 space-y-4">
                    <!-- Title -->
                    <div class="text-left">
                        <label class="block text-md text-black mb-1">Announcement Title</label>
                        <input 
                            type="text" 
                            wire:model="title"
                            placeholder="Topic"
                            class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-black focus:outline-none"
                        >
                        @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Details -->
                    <div class="text-left">
                        <label class="block text-md text-black mb-1">Announcement Details</label>
                        <textarea 
                            wire:model.defer="details"
                            rows="3" 
                            placeholder="Write your announcement here..."
                            class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-black focus:outline-none resize-none"
                        ></textarea>
                    </div>

                      <div x-data="fileUpload(@entangle('existingAttachment'))" class="w-full text-left">
                    <label class="text-black">Attachment</label>

                    <!-- Upload Box -->
                    <div 
                        x-bind:class="dragging ? 'bg-gray-100' : 'bg-gray-50'" 
                        class="relative mt-2 flex items-center justify-center w-full border rounded-md border-gray-300 p-4 cursor-pointer"
                        @click="$refs.fileInput.click()"
                        @dragover.prevent="dragging = true"
                        @dragleave.prevent="dragging = false"
                        @drop.prevent="handleDrop($event)"
                    >
                        <!-- Preview (existing or new file) -->
                        <template x-if="filePreview">
                            <div class="flex flex-col items-center text-gray-600">
                                <span class="text-4xl" x-text="fileIcon">📄</span>
                                <p class="text-sm mt-2 break-all" x-text="fileName"></p>
                                <button 
                                    @click.stop="removeFile" 
                                    class="mt-2 text-red-500 underline text-sm"
                                >Remove</button>
                            </div>
                        </template>

                        <!-- Placeholder (shown only if no file) -->
                        <template x-if="!filePreview">
                            <div class="flex flex-col items-center text-gray-600 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                                    <path fill="#4b5563" d="M23 18h-3v-3h-2v3h-3v2h3v3h2v-3h3M6 2a2 2 0 0 0-2 2v16c0 1.11.89 2 2 2h7.81c-.36-.62-.61-1.3-.73-2H6V4h7v5h5v4.08c.33-.05.67-.08 1-.08c.34 0 .67.03 1 .08V8l-6-6M8 12v2h8v-2m-8 4v2h5v-2Z"/>
                                </svg>
                                <p class="mt-2 text-sm">Drag & drop a file or <span class="text-blue-500">click here to upload</span></p>
                            </div>
                        </template>
                    </div>

                    <!-- Hidden Input -->
                    <input 
                        type="file"
                        wire:model="attachments"
                        wire:key="{{ $uploadKey }}"
                        x-ref="fileInput"
                        hidden
                        @change="showPreview($event)"
                        accept="image/*,video/*,.pdf,.doc,.docx"
                    >

                    @error('attachments') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
                </div>
                                
                                <!-- Footer Buttons -->
                            <div class="flex justify-end gap-x-3 mt-6">
                    <button 
                    type="button"
                    @click="
                        Swal.fire({
                            title: 'Discard unsaved changes?',
                            text: 'Your changes will not be saved.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, discard',
                            cancelButtonText: 'No, keep editing'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // ✅ Reloads the page completely
                            }
                        });
                    "
                    class="px-5 py-2 border border-gray-400 rounded-full hover:bg-gray-100"
                >
                    Cancel
                </button>


                    <button 
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="updateAnnouncement"
                        class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800 disabled:opacity-50"
                    >
                        Update
                    </button>


                </div>
            </form>
        </div>
    </div>
</div>
<script>
function fileUpload(lwExistingAttachment) {
    return {
        dragging: false,
        filePreview: false,
        fileName: '',
        fileIcon: '',
        existingAttachment: lwExistingAttachment,

        init() {
            // Show existing file on load
            if (this.existingAttachment && this.existingAttachment.name) {
                this.filePreview = true;
                this.fileName = this.existingAttachment.name;
                this.fileIcon = this.getFileIcon(this.fileName);
            }

            // Watch for Livewire updates
            this.$watch('existingAttachment', () => {
                if (this.existingAttachment && this.existingAttachment.name) {
                    this.filePreview = true;
                    this.fileName = this.existingAttachment.name;
                    this.fileIcon = this.getFileIcon(this.fileName);
                } else {
                    this.filePreview = false;
                    this.fileName = '';
                    this.fileIcon = '';
                }
            });
        },

        showPreview(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.filePreview = true;
            this.fileName = file.name;
            this.fileIcon = this.getFileIcon(file.name);
        },

        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (!file) return;

            this.$refs.fileInput.files = event.dataTransfer.files;
            this.showPreview({ target: { files: [file] } });
        },

        removeFile() {
            this.filePreview = false;
            this.fileName = '';
            this.fileIcon = '';
            this.$refs.fileInput.value = '';
            this.$dispatch('input', []);
            this.existingAttachment = null;
            @this.set('existingAttachment', null);
        },

        getFileIcon(fileName) {
            const ext = fileName.split('.').pop().toLowerCase();
            if (['jpg','jpeg','png','gif','webp'].includes(ext)) return '🖼️';
            if (['mp4','mov','avi','mkv'].includes(ext)) return '🎞️';
            if (ext === 'pdf') return '📕';
            if (['doc','docx'].includes(ext)) return '📘';
            return '📄';
        }
    }
}
</script>

<script>
window.addEventListener('announcement-updated', () => {
    Swal.fire({
        icon: 'success',
        title: 'Announcement updated!',
        showConfirmButton: true,
        timer: 3000,
        timerProgressBar: true,
    }).then(() => {
        // Reset the file upload box
        const uploadComponent = document.querySelector('[x-data="fileUpload()"]');
        if (uploadComponent && uploadComponent.__x) {
            uploadComponent.__x.$data.filePreview = null;
            uploadComponent.__x.$data.isImage = false;
            const fileInput = uploadComponent.querySelector('input[type="file"]');
            if (fileInput) fileInput.value = '';
        }
        // Refresh the page after SweetAlert
        location.reload();
    });
});
</script>
