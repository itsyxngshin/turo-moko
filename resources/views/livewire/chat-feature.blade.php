<div class="flex h-screen overflow-hidden bg-gray-100 p-6"> {{-- 1. Outer Container --}}
    
    <div class="flex w-full h-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200"> {{-- 2. The Card --}}

        {{-- NEW: Navigation Rail (Far Left) --}}
        {{-- ======================================================================= --}}
        {{-- 3. NAVIGATION RAIL (Your Specific Style)                                --}}
        {{-- ======================================================================= --}}
        <nav class="w-20 flex-shrink-0 bg-orange-500 border-r border-gray-200 flex flex-col items-center py-6 z-30 h-full">
    
            {{-- Get Role --}}
            @php $role = auth()->user()->role->role_name ?? 'learner'; @endphp

            {{-- Top Actions --}}
            {{-- CHANGED: Replaced 'custom-scrollbar' with 'no-scrollbar' --}}
            <div class="flex flex-col items-center space-y-4 w-full overflow-y-auto no-scrollbar px-2 pb-4">
                
                {{-- Logo / Brand --}}
                {{-- Fixed typo: bg-white-500 -> bg-white --}}
                <a href="{{ route('homepage') }}" class="w-10 h-10 mb-2 rounded-xl bg-white text-orange-500 flex items-center justify-center shadow-md hover:scale-105 transition transform flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </a>

                {{-- ========================================== --}}
                {{-- ADMIN LINKS                                --}}
                {{-- ========================================== --}}
                @if($role === 'admin')
                    <x-chat-sidebar-link route="admin.hub" label="Dashboard">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </x-chat-sidebar-link>

                    <x-chat-sidebar-link route="admin.enrollees" label="Learner Roster">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </x-chat-sidebar-link>

                    <x-chat-sidebar-link route="admin.implementors" label="Implementors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </x-chat-sidebar-link>

                    <x-chat-sidebar-link route="admin.courses" label="Courses">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </x-chat-sidebar-link>

                    <x-chat-sidebar-link route="admin.course-moderation" label="Moderation">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </x-chat-sidebar-link>

                    <x-chat-sidebar-link route="admin.settings" label="Settings">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </x-chat-sidebar-link>

                {{-- ========================================== --}}
                {{-- IMPLEMENTOR LINKS                          --}}
                {{-- ========================================== --}}
                @elseif($role === 'implementor' || $role === 'implementer')
                    <x-chat-sidebar-link route="implementor.hub" label="Home">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </x-chat-sidebar-link>

                    <x-chat-sidebar-link route="implementor.courses" label="My Classes">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    </x-chat-sidebar-link>

                    <x-chat-sidebar-link route="implementor.settings" label="Settings">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </x-chat-sidebar-link>

                {{-- ========================================== --}}
                {{-- LEARNER LINKS                              --}}
                {{-- ========================================== --}}
                @else
                    <x-chat-sidebar-link route="learner.hub" label="Home">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </x-chat-sidebar-link>

                    <x-chat-sidebar-link route="learner.classes" label="Courses">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </x-chat-sidebar-link>

                    <x-chat-sidebar-link route="learner.settings" label="Settings">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </x-chat-sidebar-link>
                @endif

                {{-- ========================================== --}}
                {{-- GLOBAL LINKS (Profile & Chat)              --}}
                {{-- ========================================== --}}

                {{-- Profile --}}
                <x-chat-sidebar-link :route="auth()->user()->profile_route ?? 'profile.edit'" label="Profile">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </x-chat-sidebar-link>

                {{-- Active Chat Indicator --}}
                <a href="#" class="p-3 rounded-xl bg-orange-100 text-orange-600 shadow-inner flex items-center justify-center cursor-default group relative">
                    <svg class="w-6 h-6 stroke-current" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    <span class="absolute left-14 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">Chat</span>
                </a>

            </div>

            {{-- Bottom Actions (Logout) --}}
            <div class="mt-auto pt-4 w-full flex justify-center border-t border-orange-400 bg-orange-500">
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="p-3 rounded-xl text-orange-100 hover:bg-orange-700 hover:text-white transition duration-200 group relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span class="absolute left-14 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none z-50">Logout</span>
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