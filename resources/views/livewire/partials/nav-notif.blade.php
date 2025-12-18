<div x-data="{ open: false }" wire:ignore.self>
    {{-- TRIGGER BUTTON (Bell Icon) --}}
    <button @click="open = !open" class="relative p-2 rounded-full hover:bg-gray-100 text-gray-500 transition-colors focus:outline-none">
        <i data-lucide="bell" class="w-6 h-6"></i>
        @if($this->unreadCount > 0)
            <span class="absolute top-2 right-2.5 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
        @endif
    </button>

    {{-- DROPDOWN --}}
    <template x-teleport="body">
        <div x-show="open"
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed right-4 top-20 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-[9000] overflow-hidden"
             style="display: none;"
        >
            <div class="px-4 py-3 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-semibold text-gray-800 text-sm">Notifications</h3>
                @if($this->unreadCount > 0)
                    <button wire:click="markAllAsRead" class="text-xs text-orange-500 hover:text-orange-600 font-medium">
                        Mark all read
                    </button>
                @endif
            </div>

            <div class="max-h-80 overflow-y-auto">
                @forelse($this->notifications as $notification)
                    <div class="px-4 py-3 border-b border-gray-50 hover:bg-orange-50/30 transition-colors group relative {{ $notification->read_at ? 'opacity-75' : '' }}">
                        <div class="flex gap-3">
                            <div class="mt-1 flex-shrink-0 h-8 w-8 rounded-full {{ $notification->read_at ? 'bg-gray-100 text-gray-400' : 'bg-orange-100 text-orange-500' }} flex items-center justify-center">
                                <i data-lucide="info" class="w-4 h-4"></i>
                            </div>
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
                            {{-- Mark as read button (only if unread) --}}
                            @if(!$notification->read_at)
                                <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="markAsRead('{{ $notification->id }}')" title="Mark as read" class="text-gray-400 hover:text-orange-500">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            @endif
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

            <button 
                @click="open = false" 
                wire:click="openModal" 
                class="block w-full bg-gray-50 text-center py-2 text-xs font-medium text-gray-600 hover:text-orange-500 hover:bg-gray-100 transition-colors border-t border-gray-100"
            >
                View All Notifications
            </button>
        </div>
    </template>

    {{-- 
        ========================================
        FULL NOTIFICATIONS MODAL
        ========================================
    --}}
    @if($showAllNotificationsModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center px-4 py-6 sm:px-6">
            {{-- Backdrop --}}
            <div wire:click="closeModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity"></div>

            {{-- Modal Content --}}
            <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl flex flex-col max-h-[90vh]">
                
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 rounded-t-xl">
                    <h2 class="text-lg font-bold text-gray-800">All Notifications</h2>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                {{-- Scrollable List --}}
                <div class="flex-1 overflow-y-auto p-0">
                    @forelse($allNotifications as $notification)
                        <div class="px-6 py-4 border-b border-gray-50 hover:bg-gray-50 transition-colors {{ $notification->read_at ? 'bg-white' : 'bg-orange-50/10' }}">
                            <div class="flex gap-4">
                                {{-- Icon --}}
                                <div class="mt-1 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full flex items-center justify-center {{ $notification->read_at ? 'bg-gray-100 text-gray-400' : 'bg-orange-100 text-orange-600' }}">
                                        @if($notification->read_at)
                                            <i data-lucide="mail-open" class="w-5 h-5"></i>
                                        @else
                                            <i data-lucide="bell" class="w-5 h-5"></i>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Content --}}
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm font-semibold text-gray-900">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </p>
                                        <span class="text-xs text-gray-400 whitespace-nowrap ml-2">
                                            {{ $notification->created_at->format('M d, h:i A') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>
                                </div>

                                {{-- Action --}}
                                @if(!$notification->read_at)
                                    <button 
                                        wire:click="markAsRead('{{ $notification->id }}')" 
                                        class="flex-shrink-0 text-xs font-medium text-orange-600 hover:text-orange-700 hover:underline mt-1"
                                    >
                                        Mark Read
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-gray-500">
                            No notifications found.
                        </div>
                    @endforelse
                </div>

                {{-- Footer / Pagination --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                    {{-- This uses default Laravel Pagination. You might need to publish vendor views if you want to customize it --}}
                    {{ $allNotifications->links() }} 
                </div>
            </div>
        </div>
    @endif
</div>