@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
@endpush

{{-- Use standard flex container --}}
<div class="flex h-screen overflow-hidden bg-gray-100 font-sans" style="font-family: 'Poppins', sans-serif;">

     @php
        $role = auth()->user()->role->role_name ?? 'learner'; 
    @endphp
    {{-- ======================================================================= --}}
    {{-- LEFT COLUMN: Conversation List                                          --}}
    {{-- Logic: On Mobile, hide this if a conversation is selected.              --}}
    {{--        On Desktop, always show it as w-80.                              --}}
    {{-- ======================================================================= --}}
    <aside class="flex flex-col bg-white border-r border-gray-100 z-20 h-full transition-all duration-300
                  {{ $selectedConversationId ? 'hidden md:flex md:w-80' : 'w-full md:w-80' }}">
        
        {{-- Header --}}
        <div class="h-16 flex items-center justify-between px-6 border-b border-gray-100 bg-white flex-shrink-0">
            <div class="flex items-center gap-2">
                {{-- Back to Dashboard (Browser Back) --}}
                @if($role === 'learner')
                    <a href="{{ route('learner.hub') }}" class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                @elseif($role === 'implementor')
                    <a href="{{ route('implementor.dashboard') }}" class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                @elseif($role === 'admin')
                    <a href="{{ route('homepage') }}" class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                @endif
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Messages</h1>
            </div>
            
            {{-- New Message / Action Button --}}
            <button class="group p-2 rounded-full hover:bg-orange-50 transition-colors duration-200">
                <svg class="w-5 h-5 text-gray-500 group-hover:text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </button>
        </div>

        {{-- Single Instance of List --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @livewire('component.conversation-list')
        </div>
    </aside>

    {{-- ======================================================================= --}}
    {{-- RIGHT COLUMN: Chat Window                                               --}}
    {{-- Logic: On Mobile, hide this if NO conversation is selected.             --}}
    {{--        On Desktop, always show (flex-1).                                --}}
    {{-- ======================================================================= --}}
    <main class="flex-col min-w-0 bg-gray-50 h-full relative
                 {{ $selectedConversationId ? 'flex w-full md:flex-1' : 'hidden md:flex md:flex-1' }}">
        
        @if($selectedConversationId)
            
            {{-- MOBILE ONLY: Header with "Back to List" button --}}
            <div class="md:hidden h-14 bg-white border-b border-gray-200 flex items-center px-4 sticky top-0 z-30">
                <button 
                    wire:click="$set('selectedConversationId', null)" 
                    class="mr-3 p-2 -ml-2 rounded-full hover:bg-gray-100 text-gray-600"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <span class="font-bold text-gray-800">Chat</span>
            </div>

            {{-- The Chat Component --}}
            @livewire('component.chat-window', [
                'conversationId' => $selectedConversationId
            ], key($selectedConversationId)) 
            
        @else
            {{-- Empty State (Desktop Only) --}}
            <div class="flex-1 flex flex-col items-center justify-center bg-gray-50 p-6">
                <div class="w-24 h-24 bg-orange-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Your Messages</h3>
                <p class="text-gray-500 mt-2 text-center">Select a conversation to start chatting.</p>
            </div>
        @endif
    </main>

</div>