<!-- Alpine.js for interactivity -->

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* Prevent flicker before Alpine initializes */
    [x-cloak] { display: none !important; }
</style>

<aside 
    x-data="{ expanded: false, windowWidth: window.innerWidth }" 
    x-init="lucide.createIcons()" 
    x-on:resize.window="windowWidth = window.innerWidth"
    :class="expanded ? 'w-56' : 'w-[70px]'" 
    class="transition-all duration-300 ease-in-out h-[750px] bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col py-6 ml-4 mt-2 overflow-hidden"
>
    {{-- Logo / Toggle --}}
    <div class="flex flex-col gap-8">
        <div @click="expanded = !expanded" class="flex items-center gap-2 cursor-pointer transition-all duration-300 px-2 justify-start">
            <img src="{{ asset('images/turo_moko_logo.png') }}" alt="TURO-MOKO Logo" class="h-10 w-10 rounded-full object-cover ml-2">
            <span x-show="expanded" x-transition.opacity.duration.300ms x-cloak class="text-gray-800 font-semibold text-base whitespace-nowrap">
                Turo-Moko
            </span>
        </div>

        {{-- Sidebar Links --}}
        @php $role = auth()->user()->role->role_name ?? 'learner'; @endphp
        <div class="flex flex-col w-full gap-3 mt-6 pl-5">
            {{-- ADMIN LINKS --}}
            @if($role === 'admin')
                <a href="{{ route('admin.hub') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.hub') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Dashboard</span>
                </a>

                <a href="{{ route('admin.enrollees') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.enrollees') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="users" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Enrollees</span>
                </a>

                <a href="{{ route('admin.implementors') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.implementors') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="bell-electric" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Implementors</span>
                </a>

                <a href="{{ route('admin.courses') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.courses') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="notebook-tabs" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Courses</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.course-moderation') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="bookmark-checked" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Moderation</span>
                </a>

                <a href="{{ route('auth.chat') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('auth.chat') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="message-circle" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Chat</span>
                </a>

                <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="settings" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Settings</span>
                </a>

            {{-- IMPLEMENTOR LINKS --}}
            @elseif($role === 'implementor' || $role === 'implementer')
                <a href="{{ route('implementor.dashboard') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('implementor.dashboard') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="home" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak>Home</span>
                </a>

                <a href="{{ route('implementor.all-courses') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('implementor.all-courses') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="presentation" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak>Courses</span>
                </a>

                <a href="{{ route('auth.chat') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('auth.chat') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="message-circle" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak>Chat</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('implementor.settings') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="settings" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak>Settings</span>
                </a>

            {{-- LEARNER LINKS --}}
            @else
                <a href="{{ route('learner.hub') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('learner.hub') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="home" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Home</span>
                </a>

                <a href="{{ route('learner.classes') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('learner.classes') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="book" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Courses</span>
                </a>

                <a href="{{ route('auth.chat') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('auth.chat') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="message-circle" class="w-6 h-6"></i>
                    <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-gray-700 text-sm font-medium">Chat</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Logout -->
    <div class="mt-auto mb-2 w-full flex justify-start px-2 ml-2">
        <form method="POST" action="{{ route('auth.logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 px-2 py-2 rounded-lg hover:bg-red-50 hover:text-red-600 text-gray-600 transition-colors" aria-label="Logout">
                <i data-lucide="log-out" class="w-6 h-6"></i>
                <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak>Logout</span>
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons(); // Render all Lucide icons
    });
</script>
