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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        .swal2-container { z-index: 9999 !important; }
    </style>

    @livewireStyles
</head>

<body x-data="{ logoutOpen: false }" class="bg-gray-50 font-sans overflow-x-hidden">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Component (sticky for desktop) -->
        <div class="flex-shrink-0 h-full sticky top-0">
            <x-sidebar />
        </div>

        <!-- Main Section -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            
            <!-- Top Bar: Name + Navbar (sticky) -->
            <div class="flex justify-between items-center h-[60px] w-full sticky top-0 z-50 bg-gray-50 px-3">
                <x-namelayout />
            </div>

            <!-- Page Content (scrollable) -->
            <main class="@yield('main_class', 'flex-1 overflow-y-auto p-4')">
                @yield('content')
                {{ $slot ?? '' }}
            </main>

        </div>
    </div>

<div x-data="{ open: false, toggleOpacity: 1 }" class="md:hidden">

    <!-- Peek Handle - Top Left -->
    <div 
        x-show="!open"
    x-cloak
        @click="open = !open"
        x-bind:style="'opacity:' + toggleOpacity"
        x-init="setTimeout(() => toggleOpacity = 0.6, 1000)"
        class="fixed top-4 left-0 bg-white border border-gray-200 rounded-r-full shadow cursor-pointer z-[10001] flex flex-col items-center justify-center w-10 h-16 gap-1.5 transition-opacity duration-500"
    >
        <!-- Three-line Hamburger -->
        <span class="block w-6 h-0.5 bg-gray-700"></span>
        <span class="block w-6 h-0.5 bg-gray-700"></span>
        <span class="block w-6 h-0.5 bg-gray-700"></span>
    </div>

    <!-- Sidebar -->
    <aside 
        x-show="open"
        x-cloak
        x-transition:enter="transition-transform duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed top-0 left-0 h-full w-64 bg-white rounded-r-3xl border border-gray-200 shadow-xl p-6 z-[10000]"
    >
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
                <!-- Mobile Logout -->
<button @click="logoutOpen = true" class="flex items-center gap-3 text-red-600 mt-6">
    <i data-lucide="log-out" class="w-6 h-6"></i> Logout
</button>
            </form>
        </nav>
    </aside>

    <!-- Backdrop -->
    <div 
        x-show="open"
        x-cloak
        x-transition.opacity
        @click="open = false"
        class="fixed inset-0 bg-black/40 z-[9999]"
    ></div>
</div>


    <!-- Init Lucide -->
    <script>
        lucide.createIcons();
    </script>
</body>


</html>