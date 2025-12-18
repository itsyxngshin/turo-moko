<div> <!-- SINGLE ROOT ELEMENT -->

    <!-- Live Search -->
    <div class="flex items-start justify-between w-full mb-4 space-x-4">

    <!-- SEARCH BAR (LEFT) -->
    <div x-data="{ open: false }" class="relative flex-1" @click.away="open = false">
        <input
            type="text"
            wire:model.live="searchName"
            @focus="open = true"
            placeholder="Search student by name or username"
            class="border p-2 rounded-md w-1/2 focus:outline-none focus:ring focus:border-blue-300"
        >

        <!-- Search Dropdown -->
        <ul
            x-show="open"
            x-transition
            class="absolute z-10 bg-white border border-gray-300 rounded-md w-full mt-1 max-h-60 overflow-y-auto shadow-lg"
        >
            @forelse($searchResults as $user)
                <li class="flex justify-between items-center p-2 hover:bg-gray-100 cursor-pointer">
                    <span>{{ $user->profile->first_name }} {{ $user->profile->last_name }} ({{ $user->username }})</span>
                    <button
                        wire:click="addEnrollee({{ $user->id }})"
                        class="bg-black text-white px-3 py-1 rounded border hover:border-black"
                    >
                        Add
                    </button>
                </li>
            @empty
                <li class="p-2 text-gray-500 text-sm">No results found.</li>
            @endforelse
        </ul>
    </div>

    <!-- SHARE LINK (RIGHT) -->
    <div class="flex flex-col gap-1 w-full md:w-auto">
        <div class="flex items-center gap-2">
            <input 
                id="enrollLink"
                type="text" 
                value="{{ route('course.join', $course->course_code) }}" 
                readonly
                class="border rounded-l-lg px-3 py-2 w-full md:w-64 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <button 
                onclick="copyEnrollmentLink()"
                class="px-4 py-2 bg-black text-white text-sm rounded-r-lg hover:bg-blue-700"
            >
                Copy
            </button>
        </div>
        <p class="text-xs italic text-gray-500">
            Share this link to let learners join your class.
        </p>
    </div>

</div>
    
    <!-- Enrollees Table -->
    <div class="mt-6">
        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">No.</th>
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Username</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($this->enrollees as $enrollee)
                    <tr>
                        <td class="p-2 text-center">
                            {{ $loop->iteration }}
                        </td>

                        <td class="p-2">
                            {{ $enrollee->user->profile->first_name }}
                            {{ $enrollee->user->profile->last_name }}
                        </td>

                        <td class="p-2">
                            {{ $enrollee->user->username }}
                        </td>

                        <td class="p-2">
                            {{ $enrollee->status }}
                        </td>

                        <td class="p-2 text-center">
                            <button
                                wire:click="removeEnrollee({{ $enrollee->id }})"
                                class="bg-black text-white px-3 py-1 rounded border hover:border-black"
                            >
                                Remove
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- FULL-SCREEN LOADING OVERLAY -->
    <div
        wire:loading.flex
        wire:target="addEnrollee, removeEnrollee"
        x-data
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 bg-black bg-opacity-40 flex flex-col items-center justify-center"
    >
        <!-- Spinner -->
        <div class="loader mb-4"></div>

        <span class="text-white font-semibold italic">
            Processing...
        </span>
    
    <!-- Loader Styles -->
    <style>
        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #f97316;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
    
    <script>
function copyEnrollmentLink() {
    const linkInput = document.getElementById('enrollLink');
    linkInput.select();
    linkInput.setSelectionRange(0, 99999); // For mobile devices
    navigator.clipboard.writeText(linkInput.value)
        .then(() => {
            alert('Enrollment link copied to clipboard!');
        })
        .catch(() => {
            alert('Failed to copy. Please copy manually.');
        });
}
</script>

</div> <!-- END SINGLE ROOT -->
