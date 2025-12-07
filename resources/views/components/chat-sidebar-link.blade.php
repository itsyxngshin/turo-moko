@props(['route', 'label'])

@php
    $isActive = request()->routeIs($route);
    
    // 1. Active State: White Background, Orange Icon, Shadow
    // 2. Inactive State: Gray Icon, Hover turns it Orange/White
    $classes = $isActive 
        ? 'bg-white text-orange-500 shadow-md' 
        : 'text-white hover:bg-orange hover:text-white-500 hover:shadow-sm';
@endphp

<a href="{{ route($route) }}" 
   class="p-3 rounded-xl transition duration-200 group relative flex items-center justify-center {{ $classes }}"
   aria-label="{{ $label }}"
   data-tooltip="{{ $label }}"> {{-- The magic happens here --}}
    
    {{-- This slot is where you put your raw SVG --}}
    {{ $slot }}

</a>