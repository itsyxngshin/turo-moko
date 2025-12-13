<div class="flex flex-col h-full bg-[#f0f2f5] relative" 
     x-data="{ 
        scrollToBottom() { 
            const container = $refs.messageContainer; 
            if(container) {
                setTimeout(() => {
                    container.scrollTop = container.scrollHeight; 
                }, 50);
            }
        } 
     }" 
     x-init="scrollToBottom()"
     @message-sent.window="scrollToBottom()"
>

    @if ($conversationId)
        {{-- ================= HEADER ================= --}}
        <div class="bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between shadow-sm z-20 sticky top-0">
            <div class="flex items-center gap-3">
                
                {{-- Mobile Back Button --}}
                <button class="md:hidden text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>

                {{-- Partner Avatar --}}
                <div class="relative">
                    @if($partner->profile && $partner->profile->photo)
                        <img 
                            src="{{ asset('storage/' . $partner->profile->photo->photos) }}" 
                            class="w-10 h-10 rounded-full object-cover border border-gray-100"
                        >
                    @else
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-orange-400 to-red-500 flex items-center justify-center text-white font-bold shadow-sm">
                            {{ strtoupper(substr($partner->profile->first_name ?? $partner->email, 0, 1)) }}
                        </div>
                    @endif
                    
                    {{-- Online Dot --}}
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                </div>

                {{-- Name & Status --}}
                <div class="flex flex-col">
                    <h3 class="font-bold text-gray-900 text-sm md:text-base leading-tight">
                        {{ $partner->profile ? $partner->profile->first_name . ' ' . $partner->profile->last_name : $partner->email }}
                    </h3>
                    <span class="text-xs text-gray-500">Active now</span>
                </div>
            </div>

            {{-- Header Actions --}}
            <div class="flex items-center gap-2">
                <button class="p-2 text-gray-400 hover:bg-gray-100 rounded-full transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                </button>
            </div>
        </div>


        {{-- ================= MESSAGES BODY ================= --}}
        <div x-ref="messageContainer" class="flex-1 overflow-y-auto p-4 space-y-2 bg-[#e5e5e5] scroll-smooth">
            
            @forelse ($messages as $index => $message)
                @php 
                    $isMe = $message->sender_id === auth()->id(); 
                    
                    // Date Separator Logic
                    $showDate = false;
                    if ($index === 0) {
                        $showDate = true;
                    } else {
                        $prevMessage = $messages[$index - 1];
                        if ($message->created_at->format('Y-m-d') !== $prevMessage->created_at->format('Y-m-d')) {
                            $showDate = true;
                        }
                    }
                @endphp

                {{-- Date Divider --}}
                @if($showDate)
                    <div class="flex justify-center my-4">
                        <span class="bg-gray-200 text-gray-600 text-[11px] font-medium px-3 py-1 rounded-full shadow-sm">
                            @if($message->created_at->isToday()) 
                                Today 
                            @elseif($message->created_at->isYesterday()) 
                                Yesterday 
                            @else 
                                {{ $message->created_at->format('M d, Y') }} 
                            @endif
                        </span>
                    </div>
                @endif

                <div class="flex w-full {{ $isMe ? 'justify-end' : 'justify-start' }} group mb-1">
                    <div class="flex {{ $isMe ? 'flex-row-reverse' : 'flex-row' }} items-end max-w-[85%] md:max-w-[70%] gap-2">
                        
                        {{-- Avatar next to incoming messages --}}
                        @if(!$isMe)
                            <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0 self-end mb-1">
                                @if($partner->profile && $partner->profile->photo)
                                    <img src="{{ asset('storage/' . $partner->profile->photo->photos) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center text-white text-[10px] font-bold">
                                        {{ strtoupper(substr($partner->profile->first_name ?? $partner->email, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Message Bubble --}}
                        <div class="relative px-4 py-2 shadow-sm text-sm md:text-[15px] leading-snug break-words
                            {{ $isMe 
                                ? 'bg-orange-600 text-white rounded-2xl rounded-tr-sm' 
                                : 'bg-white text-gray-900 rounded-2xl rounded-tl-sm' 
                            }}">
                            
                            {{-- Message Content --}}
                            <p>{{ $message->content }}</p>
                            
                            {{-- Timestamp --}}
                            <div class="text-[10px] mt-1 text-right {{ $isMe ? 'text-orange-100/80' : 'text-gray-400' }}">
                                {{ $message->created_at->format('g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center h-full pb-20">
                    <div class="bg-white p-6 rounded-full shadow-sm mb-4">
                        <img src="https://cdn-icons-png.flaticon.com/512/1041/1041916.png" class="w-16 h-16 opacity-50" alt="Chat">
                    </div>
                    <p class="text-gray-500 font-medium">No messages here yet...</p>
                    <p class="text-gray-400 text-sm">Send a message to start the conversation!</p>
                </div>
            @endforelse

            {{-- Typing indicator --}}
            @if ($typingUser)
                <div class="flex justify-start animate-fade-in-up ml-10 mt-2">
                    <div class="bg-white border border-gray-100 rounded-full px-4 py-2 flex items-center gap-1 shadow-sm">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-75"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce delay-150"></div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ================= INPUT AREA ================= --}}
        <div class="bg-white p-3 md:p-4 border-t border-gray-200 sticky bottom-0 z-20">
            <div class="flex items-end gap-2 max-w-4xl mx-auto">
                
                {{-- REMOVED ATTACHMENT BUTTON --}}

                {{-- Input Field --}}
                <div class="flex-1 bg-gray-100 rounded-2xl flex items-center px-4 py-2 focus-within:ring-2 focus-within:ring-orange-500/50 focus-within:bg-white transition-all">
                    <input 
                        type="text" 
                        wire:model.live="body" 
                        wire:keydown.enter="sendMessage"
                        placeholder="Type a message..." 
                        class="w-full bg-transparent border-none focus:ring-0 text-gray-700 placeholder-gray-500 max-h-32 py-1.5"
                    >
                </div>

                {{-- Send Button --}}
                <button 
                    wire:click="sendMessage" 
                    @if(trim($body) === '') disabled @endif
                    class="p-3 rounded-full flex items-center justify-center transition-all duration-200 mb-1 shadow-md
                        {{ trim($body) === '' ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-orange-600 text-white hover:bg-orange-700 hover:scale-105' }}"
                >
                    <svg class="w-5 h-5 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>
        </div>

    @else
        {{-- ================= NO CONVERSATION SELECTED ================= --}}
        <div class="flex-1 flex flex-col items-center justify-center bg-gray-50 p-6">
            <div class="w-32 h-32 bg-orange-100/50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                <svg class="w-16 h-16 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Your Messages</h3>
            <p class="text-gray-500 mt-2 text-center max-w-sm">Select a conversation from the sidebar to start chatting or search for a new connection.</p>
        </div>
    @endif
</div>

@script
<script>
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