<div class="flex flex-col h-full bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-3 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-orange-400 flex items-center justify-center text-white font-semibold text-lg">
                A
            </div>
            <div>
                <p class="font-semibold text-gray-800">Advisor</p>
                <p class="text-xs text-gray-400">Online</p>
            </div>
        </div>
        <button class="p-2 rounded-full hover:bg-gray-100">
            <i data-lucide="more-vertical" class="w-5 h-5 text-gray-600"></i>
        </button>
    </div>

    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50">
        <!-- Received Message -->
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-full bg-orange-400 flex items-center justify-center text-white font-semibold text-sm">
                A
            </div>
            <div class="bg-white border border-gray-200 rounded-2xl px-4 py-2 max-w-[70%] shadow-sm">
                <p class="text-gray-700 text-sm">Hi there! How can I help you today?</p>
                <span class="text-[10px] text-gray-400 mt-1 block">10:02 AM</span>
            </div>
        </div>

        <!-- Sent Message -->
        <div class="flex justify-end">
            <div class="bg-orange-400 text-white rounded-2xl px-4 py-2 max-w-[70%] shadow-sm">
                <p class="text-sm">Good afternoon! I have a question about the module.</p>
                <span class="text-[10px] text-orange-100 mt-1 block text-right">10:03 AM</span>
            </div>
        </div>
    </div>

    <!-- Input Area -->
    <div class="border-t border-gray-200 px-4 py-2 flex items-center gap-3 bg-white">
        <input
            type="text"
            placeholder="Type your message..."
            class="flex-1 border border-gray-200 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300"
        />
        <button class="bg-orange-400 hover:bg-orange-500 text-white rounded-full p-2 transition">
            <i data-lucide="send" class="w-5 h-5"></i>
        </button>
    </div>
</div>

<script>
    document.addEventListener("livewire:navigated", () => {
        lucide.createIcons();
    });
</script>
