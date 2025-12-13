<div class="h-full flex flex-col bg-white">
    <div class="p-4 border-b border-gray-100 bg-white sticky top-0 z-10">
        @livewire('component.user-search')
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar">
        @forelse ($conversations as $conversation)
            @php
                // 1. Identify Partner
                $partner = $conversation->user_one_id === auth()->id() 
                    ? $conversation->userTwo 
                    : $conversation->userOne;
                    
                // 2. Get Name
                $partnerName = $partner->profile 
                    ? ($partner->profile->first_name . ' ' . $partner->profile->last_name)
                    : $partner->email;
                
                // 3. Get Last Message (Requires 'messages' relationship loaded)
                $lastMessage = $conversation->messages->first();

                // 4. Check Active State
                $isActive = $selectedConversationId === $conversation->id;
            @endphp

            <button 
                wire:click="selectConversation({{ $conversation->id }})"
                class="w-full text-left flex items-center px-4 py-4 hover:bg-orange-50 transition duration-150 ease-in-out border-l-4 {{ $isActive ? 'border-orange-500 bg-orange-50/50' : 'border-transparent' }}"
            >
                {{-- PROFILE PHOTO SECTION --}}
                <div class="relative flex-shrink-0">
                    @if($partner->profile && $partner->profile->photo)
                        {{-- Has Photo --}}
                        <img 
                            src="{{ asset('storage/' . $partner->profile->photo->photos) }}" 
                            alt="{{ $partnerName }}"
                            class="w-12 h-12 rounded-full object-cover border border-gray-200 shadow-sm"
                        >
                    @else
                        {{-- Fallback Initials --}}
                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-orange-400 to-red-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                            {{ strtoupper(substr($partnerName, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- TEXT CONTENT SECTION --}}
                <div class="ml-4 flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        {{-- Name --}}
                        <h3 class="text-sm font-semibold text-gray-900 truncate">
                            {{ $partnerName }}
                        </h3>
                        
                        {{-- Time (e.g., "5m", "2h") --}}
                        <span class="text-xs text-gray-400 flex-shrink-0 ml-2">
                            {{ $lastMessage?->created_at->shortAbsoluteDiffForHumans() ?? '' }}
                        </span>
                    </div>

                    {{-- LAST MESSAGE PREVIEW --}}
                    <p class="text-sm {{ $isActive ? 'text-gray-800 font-medium' : 'text-gray-500' }} truncate">
                        @if($lastMessage)
                            @if($lastMessage->sender_id === auth()->id())
                                <span class="text-xs text-gray-400 mr-1">You:</span>
                            @endif
                            {{ $lastMessage->content }}
                        @else
                            <span class="italic text-gray-400">Start a conversation</span>
                        @endif
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