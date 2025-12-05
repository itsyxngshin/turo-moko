<div x-data="{ open: false }" class="relative px-8 py-4 flex items-center gap-4 bg-transparent">
    <!-- Search -->
    <input type="text"
        placeholder="Search courses"
        class="px-4 py-2 rounded-full border bg-white shadow-sm w-96 focus:outline-none focus:ring-2 focus:ring-indigo-500">

    <!-- Right Buttons -->
    <div class="flex gap-2 items-center relative">
        <!-- Notification Button -->
        <button 
            @click="open = !open"
            class="relative h-10 w-10 rounded-full bg-white flex items-center justify-center shadow hover:bg-gray-100 transition"
            aria-label="Notifications"
        >
            <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>
            <span class="absolute top-1 right-1 block h-2 w-2 bg-red-500 rounded-full"></span>
        </button>

        <!-- Dropdown Panel -->
        <div 
            x-show="open" 
            x-cloak
            @click.away="open = false"
            x-transition
            class="absolute top-12 right-0 w-80 bg-white rounded-2xl shadow-lg border z-50 overflow-hidden"
        >
            <div class="flex justify-between items-center px-4 py-3 border-b">
                <h3 class="font-semibold text-gray-700">Notifications</h3>
                <button @click="open = false" class="text-sm text-indigo-600 hover:underline">Mark all read</button>
            </div>

            <div class="max-h-80 overflow-y-auto">
                <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition">
                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i data-lucide="book-open" class="w-5 h-5 text-indigo-500"></i>
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-700 font-medium">New course published!</p>
                        <p class="text-gray-500 text-xs">“Introduction to AI” is now available.</p>
                        <p class="text-gray-400 text-xs mt-1">2 mins ago</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition">
                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-500"></i>
                    </div>
                    <div class="text-sm">
                        <p class="text-gray-700 font-medium">You completed “Module 2”.</p>
                        <p class="text-gray-400 text-xs mt-1">10 mins ago</p>
                    </div>
                </div>

            <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition">
                <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
                    <i data-lucide="graduation-cap" class="w-5 h-5 text-orange-500"></i>
                </div>
                <div class="text-sm">
                    <p class="text-gray-700 font-medium">You enrolled in “Web Development 101”.</p>
                    <p class="text-gray-400 text-xs mt-1">1 hour ago</p>
                </div>
            </div>

            </div>

        <a href="{{ route('learner.notifications') }}">
            <div class="text-center text-sm text-indigo-600 py-3 border-t hover:bg-indigo-50 cursor-pointer">
                View all notifications
            </div>
        </a>

        </div>

        <!-- Profile -->
        <a href="{{ route('learner.profile') }}">
            <button class="h-10 w-10 rounded-full bg-white flex items-center justify-center shadow hover:bg-gray-100" aria-label="Profile">
                <i data-lucide="user" class="w-5 h-5 text-gray-600"></i>
            </button>
        </a>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

