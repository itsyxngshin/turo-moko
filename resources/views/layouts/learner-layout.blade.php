<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'TuroMoko')</title>
<link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">
<<<<<<< Updated upstream
=======
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" as="style" onload="this.rel='stylesheet'">

>>>>>>> Stashed changes

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

<<<<<<< Updated upstream
=======
    <style>
        html {
            font-family: 'Poppins', sans-serif;
        }
    </style>

>>>>>>> Stashed changes
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

<body x-data="{ logoutOpen: false }" class="bg-gray-50 font-sans overflow-x-hidden">

@php
    $user = Auth::user();
    $profile = $user->profile;
    $role = $user->role->role_name ?? null;
    $initials = strtoupper(substr($profile->first_name ?? 'U', 0, 1) . substr($profile->last_name ?? '', 0, 1));
@endphp

<div class="flex">

    <!-- MOBILE SIDEBAR -->
    <div x-data="{ open: false }" class="md:hidden">
        <!-- Peek Handle -->
        <div @click="open = !open"
            class="fixed top-4 left-0 bg-white border border-gray-200 rounded-r-full shadow cursor-pointer z-50 flex flex-col items-center justify-center w-10 h-16 gap-1.5">
            <span class="block w-6 h-0.5 bg-gray-700"></span>
            <span class="block w-6 h-0.5 bg-gray-700"></span>
            <span class="block w-6 h-0.5 bg-gray-700"></span>
        </div>

        <!-- Mobile Sidebar -->
        <aside x-show="open" x-cloak x-transition:enter="transition-transform duration-300"
               x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
               x-transition:leave="transition-transform duration-300"
               x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
               class="fixed top-0 left-0 h-full w-64 bg-white rounded-r-3xl border border-gray-200 shadow-xl p-6 z-50">

            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-3">
                    <!-- Hamburger -->
                    <div class="flex flex-col justify-between w-6 h-5">
                        <span class="block h-0.5 w-full bg-gray-700"></span>
                        <span class="block h-0.5 w-full bg-gray-700"></span>
                        <span class="block h-0.5 w-full bg-gray-700"></span>
                    </div>
                    <!-- Brand -->
                    <span class="text-orange-500 font-semibold text-lg">Turo-Moko</span>
                </div>
                <button @click="open = false" class="p-1 rounded-full hover:bg-gray-100">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Links -->
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

                <!-- Mobile Logout -->
                <button @click="logoutOpen = true" class="flex items-center gap-3 text-red-600 mt-6">
                    <i data-lucide="log-out" class="w-6 h-6"></i> Logout
                </button>
            </nav>
        </aside>

        <!-- Backdrop -->
        <div x-show="open" x-transition.opacity x-cloak @click="open = false"
             class="fixed inset-0 bg-black/40 z-40"></div>
    </div>

    <!-- DESKTOP SIDEBAR -->
<<<<<<< Updated upstream
    <aside x-data="{ expanded: true }"
           :class="expanded ? 'w-56' : 'w-[70px]'"
           class="hidden md:flex transition-all duration-300 ease-in-out h-[750px] bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col py-6 ml-4 mt-2 overflow-hidden sticky top-2 z-20">

        <!-- Logo / Toggle -->
        <div class="flex flex-col gap-8">
            <div @click="expanded = !expanded" class="flex items-center cursor-pointer px-2 ml-4">
                <div class="flex flex-col justify-between w-6 h-5">
                    <span class="block h-0.5 w-full bg-gray-700"></span>
                    <span class="block h-0.5 w-full bg-gray-700"></span>
                    <span class="block h-0.5 w-full bg-gray-700"></span>
                </div>
                <span x-show="expanded" x-cloak class="text-orange-500 font-semibold text-base whitespace-nowrap ml-4">
                    Turo-Moko
                </span>
=======
<aside x-data="{
        expanded: JSON.parse(localStorage.getItem('sidebarExpanded')) ?? true,
        toggle() {
            this.expanded = !this.expanded;
            localStorage.setItem('sidebarExpanded', this.expanded);
        }
    }"
    :class="expanded ? 'w-56' : 'w-[70px]'"
    class="hidden md:flex transition-all duration-300 ease-in-out h-[750px] bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col py-6 ml-4 mt-2 overflow-hidden sticky top-2 z-20">

    <!-- Logo / Toggle -->
    <div class="flex flex-col gap-8">
        <div @click="toggle()" class="flex items-center cursor-pointer px-2 ml-4">
            <div class="flex flex-col justify-between w-6 h-5">
                <span class="block h-0.5 w-full bg-gray-700"></span>
                <span class="block h-0.5 w-full bg-gray-700"></span>
                <span class="block h-0.5 w-full bg-gray-700"></span>
            </div>
            <span x-show="expanded" x-cloak class="text-orange-500 font-semibold text-base whitespace-nowrap ml-4">
                Turo-Moko
            </span>
>>>>>>> Stashed changes
            </div>

            <!-- Sidebar Links -->
            <div class="flex flex-col w-full gap-3 mt-6 pl-5">
                <a href="{{ route('learner.hub') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('learner.hub') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="home" class="w-6 h-6"></i>
                    <span x-show="expanded" x-cloak class="text-gray-700 text-sm font-medium">Home</span>
                </a>
                <a href="{{ route('learner.classes') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('learner.classes') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="book" class="w-6 h-6"></i>
                    <span x-show="expanded" x-cloak class="text-gray-700 text-sm font-medium">Courses</span>
                </a>
                <a href="{{ route('auth.chat') }}" class="flex items-center gap-3 px-2 py-2 rounded-lg transition-colors {{ request()->routeIs('auth.chat') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
                    <i data-lucide="message-circle" class="w-6 h-6"></i>
                    <span x-show="expanded" x-cloak class="text-gray-700 text-sm font-medium">Chat</span>
                </a>
            </div>
        </div>

        <!-- Desktop Logout -->
        <div class="px-3 mt-auto pt-4 shrink-0">
            <button @click="logoutOpen = true"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200 group"
                    title="Logout">
                <i data-lucide="log-out" class="w-5 h-5 shrink-0 group-hover:stroke-red-600"></i>
                <span x-show="expanded" x-cloak class="text-sm font-medium whitespace-nowrap">Logout</span>
            </button>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col h-screen">

        <!-- Top Nav -->
<<<<<<< Updated upstream
        <nav class="flex justify-between items-center h-16 px-6 sticky top-0 z-30 bg-gray-50 ml-0 md:ml-[calc(14rem+1rem)]">
=======
        <nav class="flex justify-between items-center h-16 px-6 sticky top-0 z-30 bg-gray-50">
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
        <main class="flex-1 overflow-y-auto p-4 scrollbar-hide pt-16">
=======
        <main class="flex-1 overflow-y-auto p-4 scrollbar-hide">
>>>>>>> Stashed changes
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>
</div>

<!-- LOGOUT MODAL -->
<template x-teleport="body">
    <div 
        x-show="logoutOpen" 
        x-transition.opacity 
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        style="display: none;"
    >
        <div 
            x-show="logoutOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            @click.away="logoutOpen = false"
            class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6 text-center"
        >
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i data-lucide="log-out" class="h-6 w-6 text-red-600"></i>
            </div>

            <h3 class="text-lg font-bold text-gray-900 mb-2">Confirm Logout</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to log out of your account?</p>

            <div class="flex gap-3 justify-center">
                <button 
                    @click="logoutOpen = false" 
                    class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                >
                    Cancel
                </button>

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

<script>
    document.addEventListener("alpine:init", () => lucide.createIcons());
</script>

@livewireScripts
</body>
</html>
