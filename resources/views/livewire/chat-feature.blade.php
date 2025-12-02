<div class="flex h-screen overflow-hidden bg-gray-100"> {{-- 1. Main Flex Container --}}
    
    {{-- 2. Left Sidebar (Conversation List) --}}
    <aside class="w-80 flex-shrink-0 bg-white border-r border-gray-200 flex flex-col z-20 shadow-lg h-full">
        
        {{-- Header --}}
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 flex-shrink-0 bg-white">
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Messages</h1>
            
            {{-- Optional: New Chat Button (Visual only for now) --}}
            <button class="group p-2 rounded-full hover:bg-orange-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </button>
        </div>

        {{-- Conversation List Component --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar">
             @livewire('component.conversation-list')
        </div>
    </aside>

    {{-- 3. Right Main Area (Chat Window) --}}
    <main class="flex-1 flex flex-col min-w-0 bg-gray-50 h-full">
        @livewire('component.chat-window')
    </main>

</div>