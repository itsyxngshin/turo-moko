<!-- Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<style>
    /* Prevent flicker before Alpine initializes */
    [x-cloak] {
        display: none !important;
    }
</style>

<aside 
    x-data="{ expanded: false, windowWidth: window.innerWidth }"
    x-init="$watch('windowWidth', value => { expanded = value >= 1024 })"
    x-on:resize.window="windowWidth = window.innerWidth"
    :class="expanded ? 'w-56' : 'w-[70px]'"
    class="transition-all duration-300 ease-in-out h-[750px] bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col py-6 ml-4 mt-2 overflow-hidden"
>
    <!-- Sidebar Content -->
    <div class="flex flex-col gap-8 w-full px-4">
        <!-- Logo (Left aligned, expands on click for large screens only) -->
        <div 
            @click="if (windowWidth >= 1024) expanded = !expanded" 
            class="flex items-center gap-2 cursor-pointer transition-all duration-300"
        >
            <img src="{{ asset('images/turo_moko_logo.png') }}" 
     alt="TURO-MOKO Logo" 
     class="h-10 w-10 rounded-full object-cover">

            <span 
                x-show="expanded" 
                x-transition.opacity.duration.300ms 
                x-cloak
                class="text-gray-800 font-semibold text-base whitespace-nowrap"
            >
                Turo-Moko
            </span>
        </div>

        <!-- Navigation Links -->
        <div class="flex flex-col items-start w-full gap-3 mt-6">
            <a href="{{ route('learner.dashboard') }}" class="flex items-center gap-3 w-full px-2 py-2 rounded-lg hover:bg-gray-100 transition">
                <i data-lucide="home" class="w-7 h-7 text-gray-600"></i>
                <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Home</span>
            </a>

            <a href="{{ route('learner.classes') }}" class="flex items-center gap-3 w-full px-2 py-2 rounded-lg hover:bg-gray-100 transition">
                <i data-lucide="book" class="w-7 h-7 text-gray-600"></i>
                <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Courses</span>
            </a>

            <a href="{{ route('learner.dashboard') }}" class="flex items-center gap-3 w-full px-2 py-2 rounded-lg hover:bg-gray-100 transition">
                <i data-lucide="message-circle" class="w-7 h-7 text-gray-600"></i>
                <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Chat</span>
            </a>

        </div>
    </div>

    <!-- Logout -->
    <div class="mt-auto mb-2 w-full px-4">
        <button class="flex items-center gap-3 w-full px-2 py-2 rounded-lg hover:bg-gray-100 transition" aria-label="Logout">
            <i data-lucide="log-out" class="w-7 h-7 text-gray-600"></i>
            <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Logout</span>
        </button>
    </div>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();
    });
</script>