<div class="relative">
    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
    </span>
    
    <input 
        wire:model.live.debounce.300ms="searchQuery"
        type="text" 
        placeholder="Search users..." 
        class="w-full pl-10 pr-4 py-2 bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 rounded-lg text-sm transition duration-200 placeholder-gray-400"
    >

    @if(count($searchResults) > 0)
        <div class="absolute w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden">
            <ul>
                @foreach($searchResults as $user)
                    <li class="border-b last:border-0 border-gray-50">
                        <button 
                            wire:click="selectUser({{ $user->id }})"
                            class="w-full text-left px-4 py-3 hover:bg-orange-50 flex items-center transition group"
                        >
                            {{-- PROFILE PHOTO LOGIC --}}
                            <div class="shrink-0 mr-3">
                                @if($user->profile && $user->profile->photo)
                                    {{-- Image --}}
                                    <img 
                                        src="{{ asset('storage/' . $user->profile->photo->photos) }}" 
                                        alt="Profile"
                                        class="w-8 h-8 rounded-full object-cover border border-gray-200 shadow-sm"
                                    >
                                @else
                                    {{-- Fallback Initials --}}
                                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs group-hover:bg-orange-200 transition">
                                        {{ strtoupper(substr($user->profile?->first_name ?? $user->email, 0, 1)) }}
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">
                                    {{ $user->profile ? $user->profile->first_name . ' ' . $user->profile->last_name : explode('@', $user->email)[0] }}
                                </p>
                                <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                            </div>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    @elseif(strlen($searchQuery) > 1)
        <div class="absolute w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden px-4 py-3 text-sm text-gray-500 text-center">
            No users found.
        </div>
    @endif
</div>