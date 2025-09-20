<aside class="h-[750px] w-[70px] bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col items-center py-6 ml-4 mt-2">
    <div class="flex flex-col items-center gap-8">
        <!-- Logo -->
        <div class="h-10 w-10 rounded-full bg-orange-300 flex items-center justify-center text-white font-bold text-sm">
            TM
        </div>

        <!-- Home -->
        <a href="{{ route('learner.hub') }}" class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100" aria-label="Home">
            <i data-lucide="home" class="w-6 h-6 text-gray-600"></i>
        </a>

        <!-- Courses -->
        <a href="{{ route('learner.classes') }}" class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100" aria-label="Courses">
            <i data-lucide="book" class="w-6 h-6 text-gray-600"></i>
        </a>

        <!-- Settings -->
        <a href="{{ route('learner.settings') }}" class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100" aria-label="Settings">
            <i data-lucide="settings" class="w-6 h-6 text-gray-600"></i>
        </a>
    </div>


    <!-- Logout -->
    <div class="mt-auto">
        <button class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100" aria-label="Logout">
            <i data-lucide="log-out" class="w-6 h-6 text-gray-600"></i>
        </button>
    </div>
</aside>
