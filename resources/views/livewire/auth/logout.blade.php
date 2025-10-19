<button 
    type="button" 
    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
    onclick="confirmLogout()">
    Logout
</button>

@once
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of your account.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, log me out'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('logoutConfirmed'); // dispatch event to Livewire
            }
        });
    }
    </script>
    @endpush
@endonce