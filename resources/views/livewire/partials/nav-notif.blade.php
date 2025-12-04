<div x-data="{ open: false }" class="relative" wire:poll.10s>
    
    <!-- Bell Button -->
    <button @click="open = !open" class="relative p-2 rounded-full hover:bg-gray-100 text-gray-500 transition-colors focus:outline-none">
        <i data-lucide="bell" class="w-6 h-6"></i>
        
        @if($this->unreadCount > 0)
            <span class="absolute top-2 right-2.5 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
        @endif
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
         style="display: none;">

        <!-- Header -->
        <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="font-semibold text-gray-800 text-sm">Notifications</h3>
            @if($this->unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-orange-500 hover:text-orange-600 font-medium">
                    Mark all read
                </button>
            @endif
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto">
            @forelse($this->notifications as $notification)
                <div class="px-4 py-3 border-b border-gray-50 hover:bg-orange-50/30 transition-colors group relative">
                    <div class="flex gap-3">
                        <!-- Icon based on type (Optional logic) -->
                        <div class="mt-1 flex-shrink-0 h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-500">
                            <i data-lucide="info" class="w-4 h-4"></i>
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <span class="text-[10px] text-gray-400 mt-1 block">
                                {{ $notification->created_at->diffForHumans() }}
                            </span>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button wire:click="markAsRead('{{ $notification->id }}')" title="Mark as read" class="text-gray-400 hover:text-orange-500">
                                <i data-lucide="check" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-8 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-2 text-gray-400">
                        <i data-lucide="bell-off" class="w-6 h-6"></i>
                    </div>
                    <p class="text-sm text-gray-500">No new notifications.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer -->
        <a href="#" class="block bg-gray-50 text-center py-2 text-xs font-medium text-gray-600 hover:text-orange-500 hover:bg-gray-100 transition-colors">
            View All Notifications
        </a>
    </div>
</div>