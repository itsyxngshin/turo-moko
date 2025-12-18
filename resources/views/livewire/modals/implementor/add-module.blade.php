<div x-data="{ open: false }" x-on:close-add-module-modal.window="open = false">

    <!-- Trigger Button -->
    <div 
        @click="open = true"
        class="flex-none w-40 h-40 flex flex-col justify-center items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition"
    >
        <div class="p-3 rounded-lg mb-2 text-yellow-500">
            <!-- Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#ef5350" d="M13 9h5.5L13 3.5zM6 2h8l6 6v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2m4.93 10.44c.41.9.93 1.64 1.53 2.15l.41.32c-.87.16-2.07.44-3.34.93l-.11.04l.5-1.04c.45-.87.78-1.66 1.01-2.4m6.48 3.81c.18-.18.27-.41.28-.66c.03-.2-.02-.39-.12-.55c-.29-.47-1.04-.69-2.28-.69l-1.29.07l-.87-.58c-.63-.52-1.2-1.43-1.6-2.56l.04-.14c.33-1.33.64-2.94-.02-3.6a.85.85 0 0 0-.61-.24h-.24c-.37 0-.7.39-.79.77c-.37 1.33-.15 2.06.22 3.27v.01c-.25.88-.57 1.9-1.08 2.93l-.96 1.8l-.89.49c-1.2.75-1.77 1.59-1.88 2.12c-.04.19-.02.36.05.54l.03.05l.48.31l.44.11c.81 0 1.73-.95 2.97-3.07l.18-.07c1.03-.33 2.31-.56 4.03-.75c1.03.51 2.24.74 3 .74c.44 0 .74-.11.91-.3m-.41-.71l.09.11c-.01.1-.04.11-.09.13h-.04l-.19.02c-.46 0-1.17-.19-1.9-.51c.09-.1.13-.1.23-.1c1.4 0 1.8.25 1.9.35M7.83 17c-.65 1.19-1.24 1.85-1.69 2c.05-.38.5-1.04 1.21-1.69zm3.02-6.91c-.23-.9-.24-1.63-.07-2.05l.07-.12l.15.05c.17.24.19.56.09 1.1l-.03.16l-.16.82z"/></svg>
        </div>
        <span class="text-sm font-medium text-gray-700">Module/Lesson</span>
    </div>

    <!-- MODAL BACKGROUND -->
    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
    >
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6">
            
            <!-- HEADER -->
            <div class="flex justify-between border-b pb-2 mb-4">
                <h2 class="text-xl font-semibold">Add Module</h2>
                <button @click="open = false" class="text-gray-500 hover:text-black">✖</button>
            </div>

            <!-- FORM -->
            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- LEFT COLUMN: Module Information -->
    <div>
        <h2 class="text-lg text-gray-700 font-semibold mb-4">Module Information</h2>

        <!-- MODULE NUMBER -->
        <div class="mb-4">
            <label class="block text-md mb-1 text-black">Module Number</label>
            <input 
                type="number" 
                wire:model="module_number"
                placeholder="e.g. 1"
                class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-black focus:outline-none"
            >
            @error('module_number') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>

        <!-- MODULE TITLE -->
        <div class="mb-3">
            <label class="block text-md mb-1 text-black">Module Title</label>
            <input 
                type="text" 
                wire:model="module_title"
                placeholder="Enter module title"
                class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-black focus:outline-none"
            >
            @error('module_title') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>
        <label class="block text-md mb-1 text-black">Attachment/s</label>
        <div x-data="fileUpload('attachments')" x-on:reset-upload-box.window="resetFileUpload()">

    <input 
        type="file" 
        wire:model="attachments"
        x-ref="fileInput"
        hidden
        @change="showPreview($event)"
        accept="image/*,video/*,.pdf,.doc,.docx"
    >

    
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
            <button type="button" @click.stop="removeFile()" class="mt-2 text-red-500 underline text-sm">Remove</button>
        </div>

        <!-- Placeholder -->
        <div x-show="!filePreview" class="flex flex-col items-center text-gray-600">
            <p class="mt-2 text-sm text-gray-600 text-center">Drag & drop a file or <span class="text-blue-500">click here to upload</span></p>
        </div>
    </div>

</div>
    </div>

    <!-- RIGHT COLUMN: Lesson Information -->
    <div>

        <!-- LESSON CONTENT -->
        <div class="mb-4">
            <label class="block text-md mb-1 text-black">Module Content</label>
            <textarea 
                wire:model="content"
                placeholder="Write your module content here..."
                class="w-full border rounded p-2 h-80"
            ></textarea>
            @error('content') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>

       
    <!-- FOOTER BUTTONS (Full Width) -->
    <div class="col-span-1 md:col-span-2 flex justify-end gap-x-3 mt-6">
        <button 
            @click="open = false"
            wire:click="resetForm"
            type="button"
            class="px-5 py-2 border border-gray-400 rounded-full hover:bg-gray-100"
        >
            Discard
        </button>

        <button type="submit" class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800">
            Create Module
        </button>
    </div>
</form>


        </div>
    </div>


</div>

</div>
<script>
window.addEventListener('module-modal-close', () => {
    // Close the Add Module modal
    document.querySelector('[x-data]').__x.$data.open = false;
});

window.addEventListener('swal:module-added', (event) => {
    Swal.fire({
        icon: 'success',
        title: event.detail.title || 'Success!',
        text: event.detail.text || '',
        confirmButtonText: 'OK',
        confirmButtonColor: '#000000',
        background: '#ffffff',
        iconColor: '#000000',
    }).then(() => {
        
       location.reload(); // your Livewire listener
    });
});

window.addEventListener('swal:module-error', (event) => {
    Swal.fire({
        icon: 'error',
        title: event.detail.title || 'Error!',
        text: event.detail.text || 'Something went wrong!',
        confirmButtonColor: '#000000',
        background: '#ffffff',
        iconColor: '#000000',
    });
});

window.addEventListener('swal:error', (event) => {
    Swal.fire({
        icon: 'error',
        title: event.detail.title || 'Error!',
        text: event.detail.text || 'Something went wrong!',
        confirmButtonColor: '#000000',
        background: '#ffffff',
        iconColor: '#000000',
    });
});
</script>


    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fileUpload', (wireProperty) => ({
        dragging: false,
        filePreview: false,
        fileName: '',
        fileIcon: '',
 uploading: false, // <-- new
        showPreview(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.filePreview = true;
            this.fileName = file.name;

            const ext = file.name.split('.').pop().toLowerCase();
            if (['jpg','jpeg','png','gif','webp'].includes(ext)) this.fileIcon = '🖼️';
            else if (['mp4','mov','avi','mkv'].includes(ext)) this.fileIcon = '🎞️';
            else if (ext === 'pdf') this.fileIcon = '📕';
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
              if (this.uploading) return;
              
            this.filePreview = false;
            this.fileName = '';
            this.fileIcon = '';
            this.$refs.fileInput.value = null;

            if (this.$wire && wireProperty) {
                this.$wire.set(wireProperty, null);
                this.$wire.set('attachments_removed', true); // important!
            }
        },

        resetFileUpload() {
            this.removeFile();
        }
    }));
});
</script>