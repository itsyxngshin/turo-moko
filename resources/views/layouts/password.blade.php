<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TURO-MOKO' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen flex-col items-center justify-center bg-gray-100 p-4">
        
        <div>
            <a href="/" wire:navigate>
                <div class="flex items-center gap-5">
                    <img src="/images/turo_moko_logo.png" alt="TuroMoko Logo" class="h-10 w-10">
                    <span class="text-xl font-semibold text-orange-500">TuroMoko</span>
                </div>
            </a>
        </div>

        <div class="w-full max-w-md sm:rounded-lg mt-6 bg-white px-6 py-8 shadow-md">
            {{ $slot }}
        </div>
    </div>
</body>
</html>