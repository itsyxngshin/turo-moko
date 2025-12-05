<div> 
    @if($isOpen)
        <div class="fixed inset-0 z-[100] flex items-center justify-center px-4 sm:px-6">
            
            {{-- Backdrop --}}
            <div wire:click="closeModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm transition-opacity"></div>

            {{-- Modal Panel --}}
            {{-- FIX: Changed 'max-h-[85vh]' to 'h-[80vh]' to force fixed height --}}
            {{-- FIX: Added 'w-full' and 'max-w-2xl' to keep width consistent --}}
            <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden transform transition-all relative flex flex-col h-[80vh]">
                
                {{-- Header (Fixed at top) --}}
                <div class="flex-none flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-white z-10">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Notifications</h2>
                        <p class="text-xs text-gray-500">View all your system alerts and updates</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <button wire:click="markAllAsRead" class="text-xs font-medium text-orange-500 hover:text-orange-600 transition-colors">
                            Mark all as read
                        </button>
                        <button wire:click="closeModal" class="p-1 rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition-colors">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                {{-- Scrollable List (Takes remaining space) --}}
                <div class="flex-1 overflow-y-auto bg-gray-50/50 p-0">
                    <ul class="divide-y divide-gray-100">
                        @forelse($allNotifications as $notification)
                            <li wire:key="{{ $notification->id }}" 
                                class="group relative flex gap-4 p-4 hover:bg-white transition-colors {{ $notification->read_at ? 'opacity-75' : 'bg-orange-50/30' }}">
                                
                                {{-- Icon --}}
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->read_at ? 'bg-gray-100 text-gray-400' : 'bg-orange-100 text-orange-500' }}">
                                        <i data-lucide="{{ $notification->data['icon'] ?? 'info' }}" class="w-5 h-5"></i>
                                    </div>
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <p class="text-sm font-semibold text-gray-900 truncate pr-4">
                                            {{ $notification->data['title'] ?? 'Notification' }}
                                        </p>
                                        <span class="text-[10px] text-gray-400 whitespace-nowrap">
                                            {{ $notification->created_at->shortAbsoluteDiffForHumans() }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mt-0.5 line-clamp-2">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>

                                    {{-- Action Buttons --}}
                                    <div class="mt-2 flex items-center gap-3">
                                        @if(!empty($notification->data['url']) && $notification->data['url'] !== '#')
                                            <a href="{{ $notification->data['url'] }}" 
                                               wire:click="markAsRead('{{ $notification->id }}')"
                                               class="text-xs font-medium text-orange-600 hover:underline flex items-center gap-1">
                                                View Details <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                            </a>
                                        @endif

                                        @if(!$notification->read_at)
                                            <button wire:click="markAsRead('{{ $notification->id }}')" class="text-xs text-gray-500 hover:text-gray-700">
                                                Mark as Read
                                            </button>
                                        @endif

                                        <button wire:click="delete('{{ $notification->id }}')" class="text-xs text-red-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                            Delete
                                        </button>
                                    </div>
                                </div>

                                {{-- Unread Indicator Dot --}}
                                @if(!$notification->read_at)
                                    <span class="absolute top-4 right-4 w-2 h-2 bg-red-500 rounded-full"></span>
                                @endif
                            </li>
                        @empty
                            <li class="flex flex-col items-center justify-center h-full text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-400">
                                    <i data-lucide="bell-off" class="w-8 h-8"></i>
                                </div>
                                <h3 class="text-gray-900 font-medium">No notifications</h3>
                                <p class="text-gray-500 text-sm mt-1">You're all caught up!</p>
                            </li>
                        @endforelse
                    </ul>
                </div>

                {{-- Footer / Pagination (Fixed at bottom) --}}
                <div class="flex-none bg-white px-6 py-3 border-t border-gray-100">
                    @if($allNotifications->hasPages())
                        {{ $allNotifications->links(data: ['scrollTo' => false]) }}
                    @else
                        <p class="text-xs text-center text-gray-400">End of notifications</p>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div> 