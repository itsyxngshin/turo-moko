@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
@endpush

<div x-data="{ open: true }" class="flex h-screen overflow-hidden bg-gray-100 font-sans" style="font-family: 'Poppins', sans-serif;">

{{-- Mobile hamburger button --}}
<button 
    @click="open = true" 
    class="md:hidden fixed top-3 left-3 p-2 bg-white rounded-full shadow-md z-30"
>
    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
    </svg>
</button>


    {{-- Sidebar for desktop --}}
    <aside class="hidden md:flex w-80 flex-shrink-0 bg-white border-r border-gray-100 flex-col z-20 h-full">
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 bg-white flex-shrink-0">
            <div class="flex items-center gap-2">
                <button onclick="window.history.back()" class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Messages</h1>
            </div>
            <button class="group p-2 rounded-full hover:bg-orange-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @livewire('component.conversation-list')
        </div>
    </aside>

    {{-- Mobile sidebar --}}
    <div x-show="open" x-transition class="fixed inset-0 z-30 flex md:hidden">
        <div class="fixed inset-0 bg-black/50" @click="open = false"></div>
        <aside class="relative flex flex-col w-64 bg-white border-r border-gray-100 overflow-y-auto">
            <div class="h-16 flex items-center justify-between px-4 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <button onclick="window.history.back()" class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h1 class="text-lg font-bold">Messages</h1>
                </div>
                <button @click="open = false" class="p-2 rounded-full hover:bg-gray-100">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            @livewire('component.conversation-list')
        </aside>
    </div>

    {{-- Chat Window --}}
    <main class="flex-1 flex flex-col min-w-0 bg-gray-50 h-full relative">
        @if($selectedConversationId)
            @livewire('component.chat-window', [
                'conversationId' => $selectedConversationId
            ], key($selectedConversationId)) 
        @else
            <div class="flex-1 flex flex-col items-center justify-center bg-gray-50 p-6">
                <div class="w-24 h-24 bg-orange-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Your Messages</h3>
                <p class="text-gray-500 mt-2 text-center">Select a conversation to start chatting.</p>
            </div>
        @endif
    </main>

</div>
