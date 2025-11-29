<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Turo-Moko')</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs"></script>

    <!-- Alpine.js -->
     <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

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

.swal2-container {
    z-index: 9999 !important; /* or higher than your modal backdrop */
}
</style>

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
                <x-navbar />
            </div>

            <!-- Page Content -->
            <main class="@yield('main_class', 'm-0 pl-3 pr-8 h-full w-full')">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Init Lucide -->
    <script>
        lucide.createIcons();
    </script>
</body>
</html>