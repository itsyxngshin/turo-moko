<div>
    {{-- CRITICAL: This @if ensures the modal code doesn't exist until requested --}}
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4">
            
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg overflow-hidden transform transition-all relative max-h-[90vh] overflow-y-auto">
                
                <!-- Header -->
                <div class="flex justify-between items-center px-6 py-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">
                        View Implementor Profile
                    </h2>
                    <button wire:click="closeModal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M18 6L6 18M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-6 py-6 space-y-6">
                    <!-- Profile Header -->
                    <div class="flex items-center gap-6">
                        
                        <div class="flex-shrink-0">
                            @if ($implementor && $implementor->profile && $implementor->profile->photo && $implementor->profile->photo->photos)
                                {{-- Use Storage::url for public disk files --}}
                                <img 
                                    src="{{ Storage::url($implementor->profile->photo->photos) }}" 
                                    alt="Profile Photo" 
                                    class="w-24 h-24 rounded-full object-cover border-2 border-orange-100 shadow-sm"
                                >
                            @else
                                <div class="w-24 h-24 rounded-full bg-gray-100 border-2 border-gray-200 flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold text-gray-800">
                                {{ $implementor->profile->first_name ?? 'N/A' }} 
                                {{ $implementor->profile->middle_name ?? '' }}
                                {{ $implementor->profile->last_name ?? '' }}
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-1">
                                {{ $implementor->role->role_name ?? 'Implementor' }}
                            </span>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Username</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $implementor->username ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $implementor->email ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Phone Number</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $implementor->phonenum ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Join Date</p>
                            <p class="font-medium text-gray-900 mt-1">{{ $implementor->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Bio Section -->
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Bio / Description</p>
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-sm text-gray-600 leading-relaxed">
                            {{ $implementor->profile->bio ?? 'No bio description provided.' }}
                        </div>
                    </div>
                    
                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50">
                    <button 
                        wire:click="closeModal"
                        class="px-5 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 hover:bg-gray-100 transition shadow-sm font-medium"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>