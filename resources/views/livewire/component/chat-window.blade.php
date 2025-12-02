<div class="flex flex-col h-full bg-gray-50" 
     x-data="{ 
        scrollToBottom() { 
            const container = $refs.messageContainer; 
            // Small timeout ensures the DOM has updated with the new message before scrolling
            setTimeout(() => {
                container.scrollTop = container.scrollHeight; 
            }, 50);
        } 
     }" 
     x-init="scrollToBottom()"
     {{-- This listens for the dispatch('message-sent') we added in the PHP component --}}
     @message-sent.window="scrollToBottom()"
>
    @if ($conversationId)
        <div class="bg-white border-b border-gray-200 px-6 py-4 flex items-center shadow-sm z-10">
    
            @if($partner) 
                {{-- 1. Dynamic Avatar / Initials --}}
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-400 to-red-500 flex items-center justify-center text-white font-bold mr-3 shadow-sm">
                    <span class="text-lg">
                        {{-- Now safe to access email because we checked if $partner exists --}}
                        {{ strtoupper(substr($partner->profile->first_name ?? $partner->email, 0, 1)) }}
                    </span> 
                </div>

                {{-- 2. Dynamic Name --}}
                <div>
                    <h3 class="font-bold text-gray-800">
                        @if($partner->profile)
                            {{ $partner->profile->first_name }} {{ $partner->profile->last_name }}
                        @else
                            {{ $partner->email }}
                        @endif
                    </h3>
                    
                    <span class="text-xs text-green-500 flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Active now
                    </span>
                </div>
            @else
                {{-- Fallback if user is deleted or loading --}}
                <div class="text-gray-500 font-bold">Loading User...</div>
            @endif
        </div>

        <div x-ref="messageContainer" class="flex-1 overflow-y-auto p-6 space-y-4">
            @forelse ($messages as $message)
                @php $isMe = $message->sender_id === auth()->id(); @endphp
                
                <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }}">
                    <div class="flex max-w-[75%] {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}">
                        <div class="relative px-5 py-3 shadow-sm
                            {{ $isMe 
                                ? 'bg-orange-600 text-white rounded-l-2xl rounded-tr-2xl rounded-br-none' 
                                : 'bg-white text-gray-800 border border-gray-100 rounded-r-2xl rounded-tl-2xl rounded-bl-none' 
                            }}">
                            
                            <p class="text-sm leading-relaxed">{{ $message->content }}</p>
                            
                            <div class="text-[10px] mt-1 {{ $isMe ? 'text-orange-100' : 'text-gray-400' }} text-right">
                                {{ $message->created_at->format('g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-full text-center opacity-60">
                    <img src="https://illustrations.popsy.co/gray/surr-messaging-girl.svg" class="w-48 h-48 mb-4" alt="Empty">
                    <p class="text-gray-500">No messages yet. Say hello! 👋</p>
                </div>
            @endforelse

            @if ($typingUser)
                <div class="flex justify-start animate-fade-in-up">
                    <div class="bg-white border border-gray-100 rounded-2xl rounded-bl-none px-4 py-3 flex items-center space-x-1 shadow-sm">
                        <span class="text-xs text-gray-400 mr-2">{{ $typingUser }} is typing</span>
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce delay-75"></div>
                        <div class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce delay-150"></div>
                    </div>
                </div>
            @endif
        </div>

        <div class="p-4 bg-white border-t border-gray-100">
            <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-full border border-gray-200 focus-within:border-orange-400 focus-within:ring-2 focus-within:ring-orange-200 transition-all">
                
                <button class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                </button>

                {{-- 1. Update Input to .live so the button reacts instantly --}}
                <input type="text" 
                    wire:model.live="body" 
                    wire:keydown.enter="sendMessage"
                    placeholder="Type your message..." 
                    class="flex-1 bg-transparent border-none focus:ring-0 text-gray-700 placeholder-gray-400"
                >
                
                {{-- 2. Update Button with disabled logic --}}
                <button 
                    wire:click="sendMessage" 
                    {{-- Disable if body is empty or just spaces --}}
                    @if(trim($body) === '') disabled @endif
                    class="p-2 rounded-full text-white shadow-lg flex items-center justify-center transition transform duration-150
                        {{-- Change color/cursor based on whether it is disabled --}}
                        {{ trim($body) === '' 
                            ? 'bg-gray-300 cursor-not-allowed' 
                            : 'bg-orange-500 hover:bg-orange-600 hover:scale-105' 
                        }}"
                >
                    <svg class="w-5 h-5 translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </button>
            </div>
        </div>
    @else
        <div class="flex-1 flex flex-col items-center justify-center bg-gray-50">
            <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Your Messages</h3>
            <p class="text-gray-500 mt-2">Select a conversation from the sidebar to start chatting.</p>
        </div>
    @endif
</div>

@script
<script>
    // Note: We use $wire.on instead of @this.on in the new syntax, 
    // but @this works too.
    $wire.on('conversationSelected', (conversationId) => {
        
        if (window.currentChatChannel) {
            window.Echo.leave(window.currentChatChannel);
        }
        window.currentChatChannel = `conversation.${conversationId}`;

        window.Echo.private(window.currentChatChannel)
            .listen('MessageSent', (e) => {
                $wire.call('loadConversation', conversationId); 
                window.dispatchEvent(new CustomEvent('message-sent')); 
            })
            .listenForWhisper('typing', (e) => {
                $wire.set('typingUser', e.name);
                setTimeout(() => $wire.set('typingUser', null), 2000);
                window.dispatchEvent(new CustomEvent('message-sent')); 
            });
    });
</script>
@endscript