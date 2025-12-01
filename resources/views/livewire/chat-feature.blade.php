<div class="flex h-[80vh] border rounded-lg overflow-hidden shadow">
    <!-- Sidebar -->
    <div class="w-1/3 border-r bg-white">
        @livewire('conversation-list')
    </div>

    <!-- Chat window -->
    <div class="flex-1 bg-gray-50">
        @livewire('chat-window')
    </div>
</div>