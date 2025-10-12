<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Turo-Moko')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    @livewireStyles
</head>
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <!-- Page Content -->
        <main class="p-6 w-full">
            @yield('content')
        </main>
    </div>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>

    <!-- Lucide Init + Livewire Hook -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            lucide.createIcons();
        });

        document.addEventListener("livewire:load", () => {
            lucide.createIcons();

            // Re-render Lucide icons whenever Livewire updates DOM
            Livewire.hook("morph.updated", () => lucide.createIcons());
        });
    </script>

    @livewireScripts
</body>
</html>
