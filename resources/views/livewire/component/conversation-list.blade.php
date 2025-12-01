<div class="h-full overflow-y-auto">
    <div class="p-3 text-lg font-semibold border-b bg-white">
        Conversations
    </div>

    <div class="divide-y">
        @forelse ($conversations as $conversation)
            @php
                $partner = $conversation->user_one_id === auth()->id()
                    ? $conversation->userTwo
                    : $conversation->userOne;
                $lastMessage = $conversation->messages->first();
            @endphp

            <button 
                wire:click="selectConversation({{ $conversation->id }})"
                class="w-full text-left flex items-center px-4 py-3 hover:bg-gray-100 transition @if($selectedConversationId === $conversation->id) bg-gray-200 @endif"
            >
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-700 font-bold">
                    {{ strtoupper(substr($partner->name, 0, 1)) }}
                </div>
                <div class="ml-3 flex-1">
                    <div class="font-semibold text-gray-800">
                        {{ $partner->name }}
                    </div>
                    <div class="text-sm text-gray-500 truncate">
                        {{ $lastMessage?->body ?? 'No messages yet' }}
                    </div>
                </div>
            </button>
        @empty
            <div class="p-4 text-gray-500 text-center">
                No conversations yet.
            </div>
        @endforelse
    </div>
</div>
