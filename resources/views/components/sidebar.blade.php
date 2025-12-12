<!-- Alpine.js for interactivity -->

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<style>
    /* Prevent flicker before Alpine initializes */
    [x-cloak] { display: none !important; }
</style>

<!-- MOBILE SIDEBAR WITH TOGGLE -->
<div x-data="{ open: false }" class="md:hidden">
    <!-- Toggle button -->
    <div @click="open = !open"
         class="fixed top-1/2 left-0 transform -translate-y-1/2 bg-white border border-gray-200 rounded-r-full shadow cursor-pointer z-[10001] flex items-center justify-center w-10 h-16">
        <i data-lucide="menu" class="w-6 h-6 text-gray-700"></i>
    </div>


  <!-- Sidebar -->
    <aside x-show="open"
           x-transition:enter="transition-transform duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition-transform duration-300"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed top-0 left-0 h-full w-64 bg-white rounded-r-3xl border border-gray-200 shadow-xl p-6 z-[10000]">
        
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/turo_moko_logo.png') }}" class="h-10 w-10 rounded-full">
        <span class="text-gray-800 font-semibold text-lg">Turo-Moko</span>
      </div>
      <button @click="open = false" class="p-1 rounded-full hover:bg-gray-100">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <!-- Sidebar Links (Learner Only) -->
    <nav class="flex flex-col gap-4 text-gray-700">
      <a href="{{ route('learner.hub') }}" class="flex items-center gap-3">
        <i data-lucide="home" class="w-6 h-6"></i> Home
      </a>

      <a href="{{ route('learner.classes') }}" class="flex items-center gap-3">
        <i data-lucide="book" class="w-6 h-6"></i> Courses
      </a>

      <a href="{{ route('auth.chat') }}" class="flex items-center gap-3">
        <i data-lucide="message-circle" class="w-6 h-6"></i> Chat
      </a>

      <form method="POST" action="{{ route('auth.logout') }}">
        @csrf
        <button class="flex items-center gap-3 text-red-600 mt-6">
          <i data-lucide="log-out" class="w-6 h-6"></i> Logout
        </button>
      </form>
    </nav>
  </aside>

  
  <!-- Backdrop -->
    <div x-show="open"
         @click="open = false"
         x-transition.opacity
         class="fixed inset-0 bg-black/40 z-[9999]"></div>
</div>

<aside 
    x-data="{ expanded: false, windowWidth: window.innerWidth }" 
    x-init="lucide.createIcons()" 
    x-on:resize.window="windowWidth = window.innerWidth"
    :class="expanded ? 'w-56' : 'w-[70px]'" 
    class="hidden md:flex transition-all duration-300 ease-in-out h-[750px] bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col py-6 ml-4 mt-2 overflow-hidden"
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

    <!-- Logout -->
    {{-- Logout (Pushed to bottom) --}}
    <div class="px-3 mt-auto pt-4 shrink-0">
        <form method="POST" action="{{ route('auth.logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200 group" title="Logout">
                <i data-lucide="log-out" class="w-5 h-5 shrink-0 group-hover:stroke-red-600"></i>
                <span x-show="expanded" x-transition.opacity.duration.200ms x-cloak class="text-sm font-medium whitespace-nowrap">Logout</span>
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons(); // Render all Lucide icons
    });
</script>

<script>
  document.addEventListener("alpine:init", () => {
    Alpine.data('mobileSidebar', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
            document.querySelector('[x-data="{ mobileOpen: false }"]').__x.$data.mobileOpen = this.open;
        }
    }));
  });

  document.addEventListener("DOMContentLoaded", () => {
      lucide.createIcons();
  });
</script>