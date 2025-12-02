<div>
    <!-- Trigger Button -->
    <div>
        <button wire:click="$set('showProfileModal', true)" 
                class="px-4 py-2 bg-black text-white rounded-full text-sm hover:bg-gray-800 transition">
            Edit Profile
        </button>
    </div>

    <!-- Modal -->
    @if($showProfileModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">
                
                <!-- Close Button -->
                <button wire:click="$set('showProfileModal', false)" 
                        class="absolute top-4 right-4 text-gray-500 hover:text-gray-800">
                    ✕
                </button>

                <h2 class="text-xl font-semibold text-center mb-4">View Learner Profile</h2>

                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <img src="{{ $user->profile_image ?? asset('img/default-profile.png') }}" 
                             class="w-16 h-16 rounded-full object-cover" alt="Profile">
                        <div>
                            <p class="text-lg font-medium">{{ $user->username }}</p>
                            <p class="text-sm text-gray-500">{{ $user->bio ?? 'No bio available' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="font-medium text-gray-700">Username</p>
                            <p class="text-gray-600">{{ $user->username }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Phone Number</p>
                            <p class="text-gray-600">{{ $user->phone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Email</p>
                            <p class="text-gray-600">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700">Bio / Description</p>
                            <p class="text-gray-600">{{ $user->bio ?? 'No bio available' }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button wire:click="$set('showProfileModal', false)"
                                class="px-6 py-2 border border-gray-300 rounded-full hover:bg-gray-100">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
