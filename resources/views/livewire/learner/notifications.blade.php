<div class="max-w-5xl mx-auto px-6 py-10">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-semibold text-gray-800">Notifications</h1>
        <button wire:click="markAllAsRead"
            class="text-sm px-4 py-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700">
            Mark all as read
        </button>
    </div>

    <!-- Notification List -->
    <div class="bg-white rounded-2xl shadow border border-gray-200 divide-y">

        @forelse ($notifications as $notif)
            <div class="flex items-start gap-4 p-5 hover:bg-gray-50 transition"
                wire:click="markAsRead({{ $notif['id'] }})"
                wire:key="notif-{{ $notif['id'] }}">
                
                <div
                    class="h-12 w-12 rounded-full flex items-center justify-center shrink-0 bg-{{ $notif['color'] }}-100">
                    <i data-lucide="{{ $notif['icon'] }}" class="w-6 h-6 text-{{ $notif['color'] }}-500"></i>
                </div>

                <div class="flex-1">
                    <p class="text-gray-800 text-sm {{ $notif['read'] ? 'opacity-60' : 'font-medium' }}">
                        {{ $notif['message'] }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">{{ $notif['time'] }}</p>
                </div>

                @if (!$notif['read'])
                    <span class="h-3 w-3 bg-indigo-500 rounded-full mt-2"></span>
                @endif
            </div>
        @empty
            <p class="text-center text-gray-500 py-10">No notifications found.</p>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("livewire:navigated", () => {
        lucide.createIcons();
    });
    document.addEventListener("livewire:load", () => {
        lucide.createIcons();
    });
</script>
@endpush
