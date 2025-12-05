<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TURO-MOKO')</title>
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

    <!-- ✅ ADD THIS: Livewire Styles -->
    @livewireStyles
</head>
<body class="bg-gray-50 font-sans m-0 p-0">
    <div class="flex h-screen w-full ">
        
        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Section -->
        <div class="flex-1 flex flex-col h-full pt-5">
            <!-- Top Bar: Name + Navbar -->
            <div class="flex justify-between items-center h-[60px] w-full">
                <x-namelayout />
            </div>

            <!-- Page Content -->
            <main class="@yield('main_class', 'm-0 pl-3 pr-8 h-full w-full')">
                {{ $slot }}
            </main>
        </div>
    </div>

    <!-- Init Lucide -->
    <script>
        lucide.createIcons();
    </script>
    @livewireScripts
    <script>
        // 1. Initial Load
        lucide.createIcons();

        // 2. Re-run when Livewire updates content (e.g., polling notifications)
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', ({ el, component }) => {
                lucide.createIcons();
            });
        });

        // 3. Re-run when navigating pages (if using wire:navigate)
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>