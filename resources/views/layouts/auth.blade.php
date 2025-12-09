<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'TuroMoko' }}</title>
    <link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">

    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @livewireStyles
</head>
<body class="bg-gray-50 font-sans text-gray-800">

    {{ $slot }}

    <!-- Lucide Icons -->
    <script>
        lucide.createIcons();
    </script>

    @livewireScripts
    @stack('scripts')

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal:alert', (data) => {
                const options = data[0]; 
                Swal.fire({
                    icon: options.type,
                    title: options.title,
                    text: options.text,
                    timer: options.timer,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    willClose: () => {
                        if (options.redirectUrl) {
                            window.location.href = options.redirectUrl;
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
