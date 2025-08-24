<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Turo-Moko')</title>

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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans">

    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="h-[750px] w-[70px] bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col items-center py-6 ml-4 mt-2">
            <div class="flex flex-col items-center gap-8">
                <!-- Logo -->
                <div class="h-10 w-10 rounded-full bg-orange-300 flex items-center justify-center text-white font-bold text-sm">
                    TM
                </div>
                <!-- Home -->
                <button class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100" aria-label="Home">
                    <i data-lucide="home" class="w-6 h-6 text-gray-600"></i>
                </button>
                <!-- Courses -->
                <button class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100" aria-label="Courses">
                    <i data-lucide="book" class="w-6 h-6 text-gray-600"></i>
                </button>
                <!-- Settings -->
                <button class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100" aria-label="Settings">
                    <i data-lucide="settings" class="w-6 h-6 text-gray-600"></i>
                </button>
            </div>

            <!-- Logout -->
            <div class="mt-auto">
                <button class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100" aria-label="Logout">
                    <i data-lucide="log-out" class="w-6 h-6 text-gray-600"></i>
                </button>
            </div>
        </aside>

        <!-- Main Section -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar -->
            <header class="px-8 py-4 flex justify-between items-center bg-transparent">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-full bg-orange-300 flex items-center justify-center text-white font-bold text-sm">
                        PFP
                    </div>
                    <h1 class="text-xl font-semibold">Hello, Student!</h1>
                </div>

                <div class="flex items-center gap-4">
                    <input type="text" placeholder="Search courses" class="px-4 py-2 rounded-full border bg-white shadow-sm w-96">
                    <div class="flex gap-2">
                        <button class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200" aria-label="Notifications">
                            <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>
                        </button>
                        <button class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200" aria-label="Profile">
                            <i data-lucide="user" class="w-5 h-5 text-gray-600"></i>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
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
