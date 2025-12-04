<div class="flex h-screen overflow-hidden bg-gray-100 p-6"> {{-- 1. Outer Container --}}
    
    <div class="flex w-full h-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200"> {{-- 2. The Card --}}

        {{-- NEW: Navigation Rail (Far Left) --}}
        <nav class="w-20 flex-shrink-0 bg-gray-50 border-r border-gray-200 flex flex-col items-center justify-between py-6 z-30">
            
            {{-- Top Actions --}}
            <div class="flex flex-col items-center space-y-6 w-full">
                {{-- Logo / Brand --}}
                <a href="{{ route('homepage') }}" class="w-10 h-10 rounded-xl bg-orange-500 text-white flex items-center justify-center shadow-md hover:scale-105 transition transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </a>

                {{-- Home / Dashboard --}}
                <a href="{{ route('homepage') }}" class="p-3 rounded-xl text-gray-500 hover:bg-white hover:text-orange-500 hover:shadow-sm transition duration-200 group relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    {{-- Tooltip (Optional visual only) --}}
                    <span class="absolute left-14 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">Home</span>
                </a>

                {{-- Profile --}}
                <a href="{{ route(auth()->user()->profile_route) }}" class="p-3 rounded-xl text-gray-500 hover:bg-white hover:text-orange-500 hover:shadow-sm transition duration-200 group relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="absolute left-14 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">Profile</span>
                </a>
            </div>

            {{-- Bottom Actions (Logout) --}}
            <div class="flex flex-col items-center w-full">
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="p-3 rounded-xl text-gray-400 hover:bg-red-50 hover:text-red-500 transition duration-200 group relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="absolute left-14 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">Logout</span>
                    </button>
                </form>
            </div>
        </nav>

        {{-- 3. Middle Sidebar (Conversation List) --}}
        <aside class="w-80 flex-shrink-0 bg-white border-r border-gray-100 flex flex-col z-20 h-full">
            
            {{-- Header --}}
            <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 flex-shrink-0 bg-white">
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Messages</h1>
                
                {{-- New Chat Button --}}
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

        {{-- 4. Right Main Area (Chat Window) --}}
        <main class="flex-1 flex flex-col min-w-0 bg-gray-50 h-full relative">
            @if($selectedConversationId)
                @livewire('component.chat-window', [
                    'conversationId' => $selectedConversationId
                ], key($selectedConversationId)) 
            @else
                {{-- Empty State --}}
                <div class="flex-1 flex flex-col items-center justify-center bg-gray-50">
                    <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Your Messages</h3>
                    <p class="text-gray-500 mt-2">Select a conversation to start chatting.</p>
                </div>
            @endif
        </main>

    </div>
</div>