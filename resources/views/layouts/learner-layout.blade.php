<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'TuroMoko')</title>
<link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Trix Editor -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.umd.min.js"></script>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    tailwind.config = {
        theme: {
            extend: { fontFamily: { sans: ['Poppins', 'sans-serif'] } }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@livewireStyles
</head>

<body class="bg-gray-50 font-sans overflow-x-hidden">

@php
    $user = Auth::user();
    $profile = $user->profile;
    $role = $user->role->role_name ?? null;
    $initials = strtoupper(substr($profile->first_name ?? 'U', 0, 1) . substr($profile->last_name ?? '', 0, 1));
@endphp

<div x-data="{ open: false }" class="flex overflow-x-hidden">

    <!-- MOBILE SIDEBAR -->
    <div x-data="{ open: false }" class="md:hidden">
        <!-- Peek Handle -->
        <div @click="open = !open"
            class="fixed top-1/2 left-0 transform -translate-y-1/2 bg-white border border-gray-200 rounded-r-full shadow cursor-pointer z-50 flex items-center justify-center w-10 h-16">
            <i data-lucide="menu" class="w-6 h-6 text-gray-700"></i>
        </div>

        <!-- Mobile Sidebar -->
        <aside x-show="open" x-transition:enter="transition-transform duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition-transform duration-300"
            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
            class="fixed top-0 left-0 h-full w-64 bg-white rounded-r-3xl border border-gray-200 shadow-xl p-6 z-50">

            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/turo_moko_logo.png') }}" class="h-10 w-10 rounded-full">
                    <span class="text-gray-800 font-semibold text-lg">Turo-Moko</span>
                </div>
                <button @click="open = false" class="p-1 rounded-full hover:bg-gray-100">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Learner Links -->
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
        <div x-show="open" x-transition.opacity @click="open = false" class="fixed inset-0 bg-black/40 z-40"></div>
    </div>

    <!-- DESKTOP SIDEBAR -->
    <aside x-data="{ expanded: false, windowWidth: window.innerWidth }"
           x-init="lucide.createIcons()"
           x-on:resize.window="windowWidth = window.innerWidth"
           :class="expanded ? 'w-56' : 'w-[70px]'"
           class="hidden md:flex transition-all duration-300 ease-in-out h-[750px] bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col py-6 ml-4 mt-2 overflow-hidden sticky top-2 z-20">

        <!-- Logo / Toggle -->
        <div class="flex flex-col gap-8">
            <div @click="expanded = !expanded" class="flex items-center gap-2 cursor-pointer transition-all duration-300 px-2 justify-start">
                <img src="{{ asset('images/turo_moko_logo.png') }}" alt="TURO-MOKO Logo" class="h-10 w-10 rounded-full object-cover ml-2">
                <span x-show="expanded" x-transition.opacity.duration.300ms x-cloak class="text-gray-800 font-semibold text-base whitespace-nowrap">
                    Turo-Moko
                </span>
            </div>

            <!-- Sidebar Links (Learner) -->
            <div class="flex flex-col w-full gap-3 mt-6 pl-5">
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
            </div>
        </div>

        <!-- Logout -->
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

    <!-- MAIN CONTENT -->
    <div class="flex-1 h-[750px] overflow-y-auto">
        <!-- Top Nav -->
        <nav class="flex justify-between items-center h-16 px-6 sticky top-0 z-30 bg-gray-50 pt-5 pb-5">
            <!-- Profile + Notifications -->
            <div class="flex items-center gap-4 pl-5">
                <a href="{{ route('learner.profile') }}">
                    <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-white shadow">
                        @if ($profile && $profile->photo)
                            <img src="{{ asset('storage/' . $profile->photo->photos) }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full bg-orange-100 flex items-center justify-center text-orange-500 font-bold">{{ $initials }}</div>
                        @endif
                    </div>
                </a>

                <div class="flex flex-col leading-tight">
<h1 class="text-lg font-bold text-gray-800">
    Hello, <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-600">{{ $profile->first_name ?? 'User' }}</span>!
</h1>
                    <span class="text-xs text-gray-400 uppercase">Learner</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <livewire:partials.nav-notif />
            </div>
        </nav>

        <!-- Page Content -->
        <main class="p-4 scrollbar-hide">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>
</div>

<!-- MODALS -->
<div class="relative z-[100]">
    @yield('modals')
</div>

<script>
    document.addEventListener("alpine:init", () => lucide.createIcons());
</script>

@livewireScripts
</body>
</html>
