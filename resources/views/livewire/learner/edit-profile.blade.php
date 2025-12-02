<!-- Modal Background / Overlay -->
<div
    x-data="{ open: @entangle('showProfileModal') }"
    x-show="open"
    x-cloak
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
>
    <!-- Modal Card -->
    <div
        x-show="open"
        x-transition
        class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative"
    >
        <!-- Close Button -->
        <button
            @click="open = false"
            class="absolute top-4 right-4 text-gray-500 hover:text-gray-800"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                      stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Modal Content -->
        <div class="flex flex-col gap-4">
            <h2 class="text-xl font-semibold text-center">View Learner Profile</h2>

            <!-- Profile Picture & Name -->
            <div class="flex items-center gap-4">
                <img 
                    src="{{ $profile_image ?? asset('img/default-profile.png') }}" 
                    alt="Profile" 
                    class="w-16 h-16 rounded-full object-cover">
                <div>
                    <p class="text-lg font-medium">{{ $username ?? 'Demo Student' }}</p>
                    <p class="text-sm text-gray-500">{{ $bio ?? 'No bio available.' }}</p>
                </div>
            </div>

            <!-- Details Grid -->
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="font-medium text-gray-700">Username</p>
                    <p class="text-gray-600">{{ $username ?? 'demouser' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Phone Number</p>
                    <p class="text-gray-600">{{ $phone ?? '09213456776' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Email</p>
                    <p class="text-gray-600">{{ $email ?? 'student@example.com' }}</p>
                </div>
                <div>
                    <p class="font-medium text-gray-700">Bio / Description</p>
                    <p class="text-gray-600">{{ $bio ?? 'No bio available.' }}</p>
                </div>
            </div>

            <!-- Close Button -->
            <div class="flex justify-end mt-4">
                <button
                    @click="open = false"
                    class="px-6 py-2 border border-gray-300 rounded-full hover:bg-gray-100"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
