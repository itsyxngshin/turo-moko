<div class="h-full flex flex-col bg-white">
    <div class="p-4 border-b border-gray-100 bg-white sticky top-0 z-10">
        <div class="relative" x-data="{ open: true }" @click.outside="open = false">
        
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </span>
            
            {{-- INPUT FIELD --}}
            <input 
                wire:model.live.debounce.300ms="searchQuery"
                @focus="open = true"
                @input="open = true"
                type="text" 
                placeholder="Search users..." 
                class="w-full pl-10 pr-4 py-2 bg-gray-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 rounded-lg text-sm transition duration-200 placeholder-gray-400"
            >

            {{-- LIVE DROPDOWN RESULTS --}}
            @if(strlen($searchQuery) > 1 && count($searchResults) > 0)
                <div 
                    x-show="open" 
                    class="absolute w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden"
                    style="display: none;" {{-- Prevents flash of content --}}
                >
                    <ul>
                        @foreach($searchResults as $user)
                            <li class="border-b last:border-0 border-gray-50">
                                <button 
                                    wire:click="startConversation({{ $user->id }})"
                                    class="w-full text-left px-4 py-3 hover:bg-orange-50 flex items-center transition group"
                                >
                                    {{-- Initials (Profile Safe) --}}
                                    <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-xs mr-3 group-hover:bg-orange-200 transition">
                                        {{ strtoupper(substr($user->profile?->first_name ?? $user->email, 0, 1)) }}
                                    </div>
                                    
                                    <div>
                                        {{-- Name (Profile Safe) --}}
                                        <p class="text-sm font-semibold text-gray-800">
                                            @if($user->profile)
                                                {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                                            @else
                                                <span class="text-gray-500">{{ explode('@', $user->email)[0] }}</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @elseif(strlen($searchQuery) > 1)
                {{-- "No Results" State --}}
                <div x-show="open" class="absolute w-full mt-2 bg-white rounded-lg shadow-xl border border-gray-100 z-50 overflow-hidden px-4 py-3 text-sm text-gray-500 text-center">
                    No users found.
                </div>
            @endif
        </div>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar">
        @forelse ($conversations as $conversation)
            @php
                $partner = $conversation->user_one_id === auth()->id() 
                    ? $conversation->userTwo 
                    : $conversation->userOne;
                    
                // Safely get the name from the profile relation
                $partnerName = $partner->profile 
                    ? ($partner->profile->first_name . ' ' . $partner->profile->last_name)
                    : $partner->email; // Fallback to email if no profile
                
                // 3. Get the last message
                $lastMessage = $conversation->messages->first();

                // 4. THIS WAS MISSING: Check if this is the active conversation
                $isActive = $selectedConversationId === $conversation->id;
            @endphp

            <button 
                wire:click="selectConversation({{ $conversation->id }})"
                class="w-full text-left flex items-center px-4 py-4 hover:bg-orange-50 transition duration-150 ease-in-out border-l-4 {{ $isActive ? 'border-orange-500 bg-orange-50/50' : 'border-transparent' }}"
            >
                <div class="relative flex-shrink-0">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-orange-400 to-red-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                       {{ strtoupper(substr($partnerName, 0, 1)) }}
                    </div>
                </div>

                <div class="ml-4 flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                            {{ $partnerName }}
                        </h3>
                        <span class="text-xs text-gray-400">
                            {{ $lastMessage?->created_at->shortAbsoluteDiffForHumans() ?? '' }}
                        </span>
                    </div>
                    <p class="text-sm {{ $isActive ? 'text-gray-800 font-medium' : 'text-gray-500' }} truncate">
                        {{ $lastMessage?->content ?? 'Start a conversation' }}
                    </p>
                </div>
            </button>
        @empty
            <div class="flex flex-col items-center justify-center h-64 text-center px-4">
                <div class="bg-gray-100 rounded-full p-4 mb-3">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <p class="text-gray-500 text-sm">No conversations yet.</p>
            </div>
        @endforelse
    </div>
</div>