<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'TURO-MOKO' }}</title>
    <link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
</head>
<body class="h-full overflow-hidden">
    
    {{ $slot }}

    @livewireScripts
</body>
<style>
            /* Tooltip Base Style */
            .tooltip-trigger:hover::after {
                content: attr(data-tooltip); /* Grab the text from the attribute */
                
                /* Positioning */
                position: absolute;
                left: 100%;       /* Push it to the right of the button */
                top: 50%;         /* Center vertically */
                transform: translateY(-50%); /* Adjust for height */
                margin-left: 12px; /* Space between button and tooltip */
                
                /* Appearance */
                background-color: #1f2937; /* Tailwind gray-800 */
                color: white;
                padding: 4px 8px;
                font-size: 12px;
                border-radius: 4px;
                white-space: nowrap;
                z-index: 50;
                pointer-events: none; /* Let clicks pass through */
                
                /* Animation */
                opacity: 0;
                animation: fadeIn 0.2s forwards;
            }

            /* Small triangle pointer (Optional) */
            .tooltip-trigger:hover::before {
                content: "";
                position: absolute;
                left: 100%;
                top: 50%;
                transform: translateY(-50%);
                margin-left: 6px;
                border-width: 6px;
                border-style: solid;
                border-color: transparent #1f2937 transparent transparent; /* Arrow pointing left */
                opacity: 0;
                animation: fadeIn 0.2s forwards;
            }

            @keyframes fadeIn {
                to { opacity: 1; }
            }

            /* Hide scrollbar for Chrome, Safari and Opera */
            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }
            
            /* Hide scrollbar for IE, Edge and Firefox */
            .no-scrollbar {
                -ms-overflow-style: none;  /* IE and Edge */
                scrollbar-width: none;  /* Firefox */
            }
        </style>
</html>