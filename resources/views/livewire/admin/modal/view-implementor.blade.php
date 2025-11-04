<div 
    x-data="{ open: @entangle('showModal') }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
>
    <div 
        @click.away="open = false"
        class="bg-white w-full max-w-2xl rounded-2xl shadow-lg overflow-hidden transform transition-all"
    >
        <!-- Header -->
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                View Implementor Profile
            </h2>
            <button @click="open = false" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M18 6L6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-6 space-y-5">
            <!-- Profile Header -->
            <div class="flex items-center gap-4">
                
                    @if ($implementor && $implementor->profile && $implementor->profile->photo && $implementor->profile->photo->photos)
                        <img 
                            src="{{ asset('storage/' . $implementor->profile->photo->photos) }}" 
                            alt="Profile Photo" 
                            class="w-32 h-32 rounded-full object-cover border"
                        >
                    @else
                        <img 
                            src="{{ asset('images/default-profile.png') }}" 
                            alt="Default Photo" 
                            class="w-32 h-32 rounded-full object-cover border"
                        >
                    @endif
               



                <div>
                    <h3 class="text-xl font-semibold text-gray-800">
                        {{ $implementor->profile->first_name ?? 'N/A' }} {{ $implementor->profile->last_name ?? '' }}
                    </h3>
                    <p class="text-gray-500">{{ $implementor->role->name ?? 'Implementor' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-4">
                <div>
                    <p class="text-sm text-gray-500">Username</p>
                    <p class="font-medium">{{ $implementor->username ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-medium">{{ $implementor->email ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Phone Number</p>
                    <p class="font-medium">{{ $implementor->phonenum ?? '—' }}</p>
                </div>
                
            </div>

            <div class="mt-6">
                <p class="text-sm text-gray-500">Bio / Description</p>
                <p class="mt-1 text-gray-700">
                    {{ $implementor->profile->bio ?? 'No bio available.' }}
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50">
            <button 
                @click="open = false"
                class="px-5 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100"
            >
                Close
            </button>
        </div>
    </div>
</div>
