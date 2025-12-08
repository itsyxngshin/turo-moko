<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TuroMoko')</title>
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
<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">

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

        <!-- Main Section -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Page Content: Sticky Scrollable -->
            <main class="p-6 overflow-y-auto flex-1">
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