<div x-data="{ open: false }" x-on:section-header-modal-close.window="open = false">

    <!-- Trigger Button -->
    <div 
        @click="open = true"
        class="flex-none w-40 h-40 flex flex-col justify-center items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition"
    >
        <div class="p-3 rounded-lg mb-2 text-purple-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700">Section Header</span>
    </div>

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
                <h2 class="text-xl font-semibold">Add Section Header</h2>
                <button @click="open = false" class="text-gray-500 hover:text-black">✖</button>
            </div>

            <!-- FORM -->
            <form wire:submit.prevent="save" class="space-y-6">

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
                <div class="flex justify-end gap-3 pt-4 border-t">
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
                        Add Section Header
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('swal:section-header-added', (event) => {
        Swal.fire({
            icon: 'success',
            title: event.title || 'Success!',
            text: event.text || 'Section Header added successfully!',
            confirmButtonColor: '#000000'
        }).then(() => {
            window.location.reload();
        });
    });
});
</script>
