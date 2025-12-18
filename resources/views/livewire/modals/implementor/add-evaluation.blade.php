<div x-data="{ open: false }" x-on:close-add-evaluation-modal.window="open = false">

    <!-- Trigger Button -->
    <div 
        @click="open = true"
        class="flex-none w-40 h-40 flex flex-col justify-center items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition"
    >
        <div class="p-3 rounded-lg mb-2 text-indigo-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="currentColor" d="M22 16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4c0-1.11.89-2 2-2h12a2 2 0 0 1 2 2zm-6 4v2H4a2 2 0 0 1-2-2V7h2v13zm-3-6l7-7l-1.41-1.41L13 11.17L9.91 8.09L8.5 9.5z"/></svg>
        </div>
        <span class="text-sm font-medium text-gray-700">Evaluation</span>
    </div>

    <!-- MODAL BACKGROUND -->
    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
    >
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
            
            <!-- HEADER -->
            <div class="flex justify-between border-b pb-2 mb-4">
                <h2 class="text-xl font-semibold">Add Evaluation</h2>
                <button @click="open = false" class="text-gray-500 hover:text-black">✖</button>
            </div>

            <!-- CONTENT -->
            <div class="text-center py-6">
                <p class="text-gray-600 mb-6">This will create a course evaluation and implementor evaluation for this course.</p>
                
                <div class="flex gap-3 justify-center">
                    <button 
                        @click="open = false"
                        class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800 disabled:opacity-60"
                    >
                        <span wire:loading.remove>Add Evaluation</span>
                        <span wire:loading>Adding...</span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
window.addEventListener('evaluation-modal-close', () => {
    // Close the Add Evaluation modal
    const modals = document.querySelectorAll('[x-on\\:close-add-evaluation-modal\\.window]');
    modals.forEach(modal => {
        if (modal.__x) {
            modal.__x.$data.open = false;
        }
    });
});

window.addEventListener('swal:evaluation-added', (event) => {
    Swal.fire({
        icon: 'success',
        title: event.detail.title || 'Success!',
        text: event.detail.text || '',
        confirmButtonText: 'OK',
        confirmButtonColor: '#000000',
        background: '#ffffff',
        iconColor: '#000000',
    }).then(() => {
        location.reload();
    });
});

window.addEventListener('swal:evaluation-exists', (event) => {
    const detail = event.detail[0] || event.detail;
    Swal.fire({
        icon: detail.icon || 'info',
        title: detail.title || 'Info',
        text: detail.text || '',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3B82F6',
        background: '#ffffff',
    });
});
</script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

