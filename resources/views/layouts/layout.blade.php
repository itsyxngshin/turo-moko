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

<<body class="bg-gray-50 font-sans overflow-x-hidden">
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar Component (sticky) -->
        <div class="flex-shrink-0 h-full sticky top-0">
            <x-sidebar />
        </div>

        <!-- Main Section -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">
            
            <!-- Top Bar: Name + Navbar (sticky) -->
            <div class="flex justify-between items-center h-[60px] w-full sticky top-0 z-50 bg-gray-50 shadow-sm px-3">
                <x-namelayout />
            </div>

            <!-- Page Content (scrollable) -->
            <main class="@yield('main_class', 'flex-1 overflow-y-auto p-4')">
                @yield('content')
                {{ $slot ?? '' }}
            </main>

        </div>
    </div>

    <!-- Init Lucide -->
    <script>
        lucide.createIcons();
    </script>
    @livewireScripts

    <style>
        /* Hide scrollbar for all browsers */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
    </style>
    @livewireScripts
@livewireStyles

</body>

</html>