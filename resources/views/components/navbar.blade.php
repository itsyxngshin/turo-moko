<div class="px-8 py-4 flex items-center gap-4 bg-transparent">
    <input type="text" placeholder="Search courses" class="px-4 py-2 rounded-full border bg-white shadow-sm w-96">
    <div class="flex gap-2">
        <!-- Notifications Button -->
        <button class="h-10 w-10 rounded-full bg-white flex items-center justify-center shadow hover:bg-gray-100" aria-label="Notifications">
            <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>
        </button>

        <!-- Profile Button linked to learner profile -->
        <a href="{{ route('learner.profile') }}">
            <button class="h-10 w-10 rounded-full bg-white flex items-center justify-center shadow hover:bg-gray-100" aria-label="Profile">
                <i data-lucide="user" class="w-5 h-5 text-gray-600"></i>
            </button>
        </a>
    </div>
</div>
