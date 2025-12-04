<div 
    x-data="{ open: false }" 
    x-on:open-delete-announcement.window="
        if ($event.detail.id == {{ $announcementId ?? 'null' }}) open = true
    "
>
    <!-- Trigger Button -->
    <button 

        @click="$dispatch('open-delete-announcement', { id: {{ $announcementId }} })"
        class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-100"
    >
        Delete
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
            class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 text-center"
        >
            <h2 class="text-lg font-semibold mb-3">Delete Announcement</h2>
            <p class="text-gray-600 mb-5">Are you sure you want to delete this announcement?</p>

            <div class="flex justify-center gap-3">
                <button 
                    wire:click="deleteAnnouncement({{ $announcementId }})"
                    @click="open = false"
                    class="px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700"
                >
                    Yes, Delete
                </button>

                <button 
                    @click="open = false"
                    class="px-4 py-2 bg-gray-200 rounded-full hover:bg-gray-300"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    window.addEventListener('swal:success', event => {
        Swal.fire({
        icon: 'success',
        title: 'Announcement deleted!',
        showConfirmButton: true,
        timer: 3000,
        timerProgressBar: true,
    }).then(() => {
        // Refresh the page after SweetAlert
        location.reload();
    });
    });

    window.addEventListener('swal:error', event => {
        Swal.fire({
            icon: 'error',
            title: 'Something Went Wrong! Try Again!',
            showConfirmButton: true,
            timer: 3000,
            timerProgressBar: true,
        });
    });
</script>
