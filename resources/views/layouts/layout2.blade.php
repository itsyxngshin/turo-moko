<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Turo-Moko')</title>
    <link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Icons-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind Config -->
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
        body {
    background-color: transparent;
}
    </style>
</head>
<body class="bg-gray-50 bg-opacity-0 font-sans">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (fixed) -->
        <div class="sticky top-0 h-screen">
            <x-sidebar />
        </div>
<!-- Main Section -->
<div class="flex-1 flex flex-col overflow-hidden bg-gray-50 bg-opacity-50">
    <!-- Header (sticky) -->
    <div class="sticky top-0 z-10 bg-transparent">
        <div class="flex justify-between items-center">
            <x-namelayout />
            <x-navbar />
        </div>
    </div>

    <!-- Page Content (scrollable) -->
    <main class="flex-1 overflow-y-auto p-6 bg-transparent">
        @yield('content')
    </main>
</div>

    </div>

    <!-- Init Lucide -->
    <script>
        lucide.createIcons();
    </script>
    @livewireScripts
</body>
</html>
