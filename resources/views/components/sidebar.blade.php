<script src="https://unpkg.com/lucide@latest"></script>
<style> [x-cloak] { display: none !important; } </style>

{{-- 
    1. Update x-data to include 'showLogoutModal: false' 
--}}
<aside 
    x-data="{ expanded: true, showLogoutModal: false }"
    x-init="
        lucide.createIcons();
        init = true;
    "
    x-cloak
    x-on:resize.window="windowWidth = window.innerWidth"
    :class="expanded ? 'w-56' : 'w-[70px]'"
    class="hidden md:flex transition-all duration-300 ease-in-out h-[750px] bg-white 
           rounded-3xl border border-gray-200 shadow-sm flex flex-col py-6 ml-4 mt-2 
           overflow-hidden"
>

    {{-- Logo / Toggle --}}
    <div class="flex flex-col gap-8">
        <div 
            @click="expanded = !expanded"
            class="flex items-center gap-3 cursor-pointer px-2 ml-4"
        >
            <div class="flex flex-col justify-between w-6 h-5">
                <span class="block h-0.5 w-full bg-gray-700"></span>
                <span class="block h-0.5 w-full bg-gray-700"></span>
                <span class="block h-0.5 w-full bg-gray-700"></span>
            </div>

            <span 
                x-show="expanded"
                x-cloak
                class="text-orange-500 font-semibold text-base whitespace-nowrap"
            >
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

    {{-- 
        2. UPDATED LOGOUT BUTTON 
        Removed the <form> wrapper here. Instead, it just toggles the modal.
    --}}
    <div class="px-3 mt-auto pt-4 shrink-0">
        <button 
            type="button" 
            @click="showLogoutModal = true" 
            class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200 group" 
            title="Logout"
        >
            <i data-lucide="log-out" class="w-5 h-5 shrink-0 group-hover:stroke-red-600"></i>
            <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-sm font-medium whitespace-nowrap">Logout</span>
        </button>
    </div>

    {{-- 
        3. MODAL COMPONENT (Teleported to Body)
        Using x-teleport moves this HTML to the bottom of the <body> tag, 
        fixing z-index/overflow issues.
    --}}
    <template x-teleport="body">
        <div 
            x-show="showLogoutModal" 
            x-transition.opacity 
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            style="display: none;" {{-- Prevents FOUC --}}
        >
            {{-- Modal Box --}}
            <div 
                x-show="showLogoutModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                @click.away="showLogoutModal = false"
                class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6 text-center"
            >
                {{-- Icon --}}
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i data-lucide="log-out" class="h-6 w-6 text-red-600"></i>
                </div>

                {{-- Text --}}
                <h3 class="text-lg font-bold text-gray-900 mb-2">Confirm Logout</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Are you sure you want to log out of your account?
                </p>

                {{-- Actions --}}
                <div class="flex gap-3 justify-center">
                    {{-- Cancel --}}
                    <button 
                        @click="showLogoutModal = false" 
                        class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                    >
                        Cancel
                    </button>

                    {{-- Confirm (Actual Form) --}}
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-medium transition-colors"
                        >
                            Yes, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>

</aside>

{{-- Scripts --}}
<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();
    });
    
    // Re-initialize icons when modal opens (optional, usually handled by Alpine/Livewire)
    document.addEventListener("alpine:initialized", () => {
        Alpine.effect(() => {
            // If the modal state changes, we might need to refresh icons inside the teleported template
            // typically not strictly necessary for static icons inside modal but good practice
            setTimeout(() => lucide.createIcons(), 50);
        });
    });
</script>
