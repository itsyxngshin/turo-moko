<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>@yield('title', 'Turo-Moko')</title>
    <link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

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
</head>
<body class="bg-gray-50 font-sans m-0 p-0">
    <div class="flex h-screen w-full overflow-hidden">
        
        <!-- Sidebar Component -->
        <x-sidebar />

        <!-- Main Section -->
        <div class="flex-1 flex flex-col h-full pt-5">
            <!-- Navbar (Right-Aligned, Slight Top Padding) -->
            <div class="flex justify-end items-center h-[60px]">
                <x-navbar />
            </div>

            <!-- Page Content (No Padding/Margin) -->
            <main class="@yield('main_class', 'm-0 p-0 h-full w-full')">
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