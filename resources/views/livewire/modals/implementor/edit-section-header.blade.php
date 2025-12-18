<div x-data="{ open: false, confirmDelete: false }" x-on:edit-section-header-modal-close.window="open = false; confirmDelete = false">

    <!-- Trigger Button -->
    <button 
        @click="open = true"
        class="w-full text-left px-4 py-2 hover:bg-gray-100"
    >
        Edit Section Header
    </button>

    <!-- MODAL BACKGROUND -->
    <div 
        x-show="open"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]"
        style="display: none;"
    >
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-lg p-6">
            
            <!-- HEADER -->
            <div class="flex justify-between border-b pb-2 mb-4">
                <h2 class="text-xl font-semibold">Edit Section Header</h2>
                <button @click="open = false" class="text-gray-500 hover:text-black">✖</button>
            </div>

            <!-- FORM -->
            <form wire:submit.prevent="update" class="space-y-6">

                <!-- TITLE -->
                <div>
                    <label class="block text-md mb-2 text-black font-semibold">Section Title</label>
                    <input 
                        type="text" 
                        wire:model="title"
                        placeholder="e.g. Week 1, Introduction, Chapter 1"
                        class="w-full border rounded-md px-3 py-2 focus:ring focus:ring-black focus:outline-none"
                    >
                    @error('title') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- ACTION BUTTONS -->
                <div class="flex justify-between pt-4 border-t">
                    <button 
                        type="button"
                        @click="confirmDelete = true"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                    >
                        Delete
                    </button>
                    <div class="flex gap-3">
                        <button 
                            type="button"
                            @click="open = false"
                            class="px-4 py-2 border rounded-lg hover:bg-gray-100"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800"
                        >
                            Update Section Header
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- DELETE CONFIRMATION MODAL -->
    <div 
        x-show="confirmDelete"
        x-transition
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[10000]"
        style="display: none;"
    >
        <div class="bg-white p-6 rounded-xl shadow-lg w-96 text-center">
            <h3 class="text-lg font-semibold mb-4">Confirm Deletion</h3>
            <p class="mb-6">Are you sure you want to delete this section header? This action cannot be undone.</p>

            <div class="flex justify-center gap-3">
                <button 
                    @click="confirmDelete = false" 
                    class="px-4 py-2 rounded-xl border hover:bg-gray-100"
                >
                    Cancel
                </button>
                <button 
                    wire:click="delete"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('swal:section-header-updated', (event) => {
        Swal.fire({
            icon: 'success',
            title: event.title || 'Success!',
            text: event.text || 'Section Header updated successfully!',
            confirmButtonColor: '#000000'
        }).then(() => {
            window.location.reload();
        });
    });

    Livewire.on('swal:section-header-deleted', (event) => {
        Swal.fire({
            icon: 'success',
            title: event.title || 'Deleted!',
            text: event.text || 'Section Header deleted successfully!',
            confirmButtonColor: '#000000'
        }).then(() => {
            window.location.reload();
        });
    });
});
</script>
