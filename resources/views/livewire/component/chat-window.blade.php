<div class="flex flex-col h-full">
    @if ($conversationId)
        <div class="flex-1 overflow-y-auto p-4 space-y-2 bg-gray-50">
            @foreach ($messages as $message)
                <div class="flex {{ $message->isMine() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs px-4 py-2 rounded-lg 
                        {{ $message->isMine() ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
                        {{ $message->body }}
                        <div class="text-xs mt-1 opacity-75">
                            {{ $message->created_at->diffForHumans() }}
                            @if ($message->isMine() && $message->is_read)
                                <span class="ml-2 text-green-200">✔ Seen</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($typingUser)
            <div class="px-4 py-1 text-sm text-gray-500 italic">
                {{ $typingUser }} is typing...
            </div>
        @endif

        <div class="p-3 border-t bg-white flex items-center">
            <input type="text" 
                wire:model.debounce.500ms="body" 
                wire:keydown.enter="sendMessage"
                placeholder="Type a message..." 
                class="flex-1 border rounded-lg px-3 py-2 mr-2 focus:outline-none focus:ring focus:border-blue-300">

            <button wire:click="sendMessage" 
                class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Send
            </button>
        </div>
    @else
        <div class="flex-1 flex items-center justify-center text-gray-500">
            Select a conversation to start chatting.
        </div>
    @endif
</div>

<script>
document.addEventListener('livewire:initialized', () => {
    @this.on('conversationSelected', conversationId => {
        window.Echo.private(`conversation.${conversationId}`)
            .listen('MessageSent', (e) => {
                Livewire.dispatch('messageReceived', e.message);
            })
            .listen('UserTyping', (e) => {
                Livewire.find('{{ $this->id }}').set('typingUser', e.userName);
                setTimeout(() => Livewire.find('{{ $this->id }}').set('typingUser', null), 2000);
            });
    });
});
</script>