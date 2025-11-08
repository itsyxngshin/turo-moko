<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Turo-Moko')</title>
    <link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

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
<body>
    {{ $slot }}
    
    <!-- Init Lucide -->
    <script>
        lucide.createIcons();
    </script>
    @livewireScripts

    <script>
        // Wait for Livewire to initialize
        document.addEventListener('livewire:initialized', () => {
            
            // Listen for the 'swal:alert' event
            @this.on('swal:alert', (event) => {
                let data = event[0]; // Get the event data
                Swal.fire({
                    icon: data.type,
                    title: data.title,
                    text: data.text,
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        });
    </script>
    @stack('scripts')
    
</body>
</html>
