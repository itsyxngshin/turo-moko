<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'TURO-MOKO' }}</title>
    <link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

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
    
    <script>
        lucide.createIcons();
    </script>
    @livewireScripts
    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('livewire:init', () => {
            
            // Listener for SweetAlert events
            Livewire.on('swal:alert', (data) => {
                // The 'data' comes as an array, so we access the first item [0]
                const options = data[0]; 

                Swal.fire({
                    icon: options.type,       // 'success', 'error', 'info'
                    title: options.title,
                    text: options.text,
                    timer: options.timer,
                    showConfirmButton: false, // Hide button if we are using a timer
                    timerProgressBar: true,
                    willClose: () => {
                        // This is the CRITICAL part:
                        // If a redirectUrl is provided, go there when the alert closes
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