<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TuroMoko')</title>
    <link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- FontAwesome for icons (added to support fa-solid classes in the login form) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

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

    @livewireStyles
</head>
<body class="bg-gray-50 font-sans" style="font-family: 'Poppins', sans-serif;">

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>

    <!-- Init Lucide Icons -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if(window.lucide) lucide.createIcons();
        });
    </script>
    
<script>
    document.addEventListener("alpine:init", () => {
        // Recreate Lucide icons on Alpine state changes
        Alpine.effect(() => {
            lucide.createIcons();
        });
    });
</script>

    @livewireScripts
    <script>
        document.addEventListener('livewire:updated', () => {
            if (window.lucide) lucide.createIcons();
        });
    </script>
</body>
</html>
