<div x-data="{ open: false }">
    <!-- Trigger button -->
    <div 
        @click="open = true" 
        class="flex-none w-40 h-40 flex flex-col justify-center items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition"    >
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
                <h2 class="text-xl font-semibold">{{ $course->name ?? '--' }}</h2>
                <button @click="open = false" class="text-gray-500 hover:text-black">✖</button>
            </div>

            <!-- Form 
            <h1 class="text-lg text-left font-semibold mb-1">{{ $course->name ?? '--' }}</h1>-->

            <!-- General Section -->
          <form wire:submit.prevent="saveAnnouncement" enctype="multipart/form-data">

                <div class="flex justify-between w-full text-lg text-gray-700 font-semibold">
                    <h2>Create Announcement</h2>
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

                 <!-- File Upload -->
<div x-data="fileUpload()" class="w-full text-left" x-on:reset-upload-box.window="resetFileUpload()">
    <label class="text-black font-medium">Attachment</label>

    <!-- Upload Box -->
    <div 
        :class="dragging ? 'bg-gray-100 z-50' : 'bg-gray-50 z-50'"
        class="relative mt-2 my-3 flex flex-col items-center justify-center w-full min-h-[140px] border rounded-md border-gray-300 p-4 cursor-pointer"
        @click="$refs.fileInput.click()"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="handleDrop($event)"
    >
        <!-- Preview -->
        <div x-show="filePreview" class="flex flex-col items-center text-gray-600">
            <span class="text-4xl" x-text="fileIcon"></span>
            <p class="text-sm mt-2 break-all" x-text="fileName"></p>
            <button @click.stop="removeFile" class="mt-2 text-red-500 underline text-sm">Remove</button>
        </div>

        <!-- Placeholder -->
        <div x-show="!filePreview" class="flex flex-col items-center text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24">
                <path fill="#4b5563" d="M23 18h-3v-3h-2v3h-3v2h3v3h2v-3h3M6 2a2 2 0 0 0-2 2v16c0 1.11.89 2 2 2h7.81c-.36-.62-.61-1.3-.73-2H6V4h7v5h5v4.08c.33-.05.67-.08 1-.08c.34 0 .67.03 1 .08V8l-6-6M8 12v2h8v-2m-8 4v2h5v-2Z"/>
            </svg>
            <p class="mt-2 text-sm text-gray-600 text-center">
                Drag & drop a file or <span class="text-blue-500">click here to upload</span>
            </p>
        </div>
    </div>

    <!-- Hidden File Input -->
    <input 
        type="file" 
        wire:model="attachment"
        x-ref="fileInput"
        hidden
        @change="showPreview($event)"
        accept="image/*,video/*,.pdf,.doc,.docx"
    >

    @error('attachment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fileUpload', () => ({
        dragging: false,
        filePreview: false,
        fileName: '',
        fileIcon: '',

        showPreview(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.filePreview = true;
            this.fileName = file.name;

            const ext = file.name.split('.').pop().toLowerCase();
            if (['jpg','jpeg','png','gif','webp'].includes(ext)) this.fileIcon = '🖼️';
            else if (['mp4','mov','avi','mkv'].includes(ext)) this.fileIcon = '🎞️';
            else if (['pdf'].includes(ext)) this.fileIcon = '📕';
            else if (['doc','docx'].includes(ext)) this.fileIcon = '📘';
            else this.fileIcon = '📄';
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
            this.$dispatch('input', null);
        },

        resetFileUpload() {
            this.removeFile();
        }
    }));
});
</script>



                </div>
            

            <!-- Footer Buttons -->
            <div class="flex justify-end gap-x-3 mt-6">
                <button 
                    @click="open = false"
                    wire:click="resetForm"
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
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('announcement-saved', () => {
        document.querySelectorAll('[x-data]').forEach(el => {
            if (el.__x) el.__x.$data.open = false;
        });
    });
});


</script>

<script>
window.addEventListener('announcement-saved', () => {
    Swal.fire({
        icon: 'success',
        title: 'Announcement created!',
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



