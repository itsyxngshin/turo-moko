<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'TuroMoko')</title>
<link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Trix Editor -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.umd.min.js"></script>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>

<!-- Alpine.js -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    tailwind.config = {
        theme: {
            extend: { fontFamily: { sans: ['Poppins', 'sans-serif'] } }
        }
    }
</script>

<style>
    [x-cloak] { display: none !important; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

@livewireStyles
</head>
<body class="bg-gray-50 font-sans overflow-x-hidden">

@php
    $user = Auth::user();
    $profile = $user->profile;
    $role = $user->role->role_name ?? null;
    $initials = strtoupper(substr($profile->first_name ?? 'U', 0, 1) . substr($profile->last_name ?? '', 0, 1));
@endphp

<div x-data="{ open: false }" class="flex h-screen overflow-hidden">


<div x-data="{ open: false }" class="md:hidden">

  <!-- Peek Handle -->
  <div 
    @click="open = !open"
    class="fixed top-1/2 left-0 transform -translate-y-1/2 bg-white border border-gray-200 rounded-r-full shadow cursor-pointer z-50 flex items-center justify-center w-10 h-16"
  >
    <i data-lucide="menu" class="w-6 h-6 text-gray-700"></i>
  </div>

  <!-- Sidebar -->
  <aside 
    x-show="open"
    x-cloak
    x-transition:enter="transition-transform duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition-transform duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    class="fixed top-0 left-0 h-full w-64 bg-white rounded-r-3xl border border-gray-200 shadow-xl p-6 z-50"
  >
    <div class="flex items-center justify-between mb-8">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/turo_moko_logo.png') }}" class="h-10 w-10 rounded-full">
        <span class="text-gray-800 font-semibold text-lg">Turo-Moko</span>
      </div>
      <button @click="open = false" class="p-1 rounded-full hover:bg-gray-100">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>

    <!-- Sidebar Links -->
    <nav class="flex flex-col gap-3 text-gray-700">

      @php $role = auth()->user()->role->role_name ?? 'learner'; @endphp

      {{-- LEARNER LINKS --}}
      @if($role === 'learner')
        <a href="{{ route('learner.hub') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors duration-200
                  {{ request()->routeIs('learner.hub') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
          <i data-lucide="home" class="w-6 h-6"></i>
          <span class="text-sm font-medium">Home</span>
        </a>

        <a href="{{ route('learner.classes') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors duration-200
                  {{ request()->routeIs('learner.classes') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
          <i data-lucide="book" class="w-6 h-6"></i>
          <span class="text-sm font-medium">Courses</span>
        </a>

        <a href="{{ route('auth.chat') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors duration-200
                  {{ request()->routeIs('auth.chat') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}">
          <i data-lucide="message-circle" class="w-6 h-6"></i>
          <span class="text-sm font-medium">Chat</span>
        </a>
      @endif

      {{-- Logout --}}
      <form method="POST" action="{{ route('auth.logout') }}">
        @csrf
        <button type="submit" 
                class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors duration-200 text-red-600 hover:bg-red-50">
          <i data-lucide="log-out" class="w-6 h-6"></i>
          <span class="text-sm font-medium">Logout</span>
        </button>
      </form>
    </nav>
  </aside>

  <!-- Backdrop -->
  <div 
    x-show="open" 
    x-cloak
    x-transition.opacity
    @click="open = false"
    class="fixed inset-0 bg-black/40 z-40"
  ></div>
</div>


    <!-- DESKTOP SIDEBAR -->
    <div class="hidden md:flex flex-shrink-0 h-full sticky top-0">
        <x-sidebar />
    </div>

    <!-- MAIN CONTENT -->
    <div :class="open ? 'translate-x-64' : 'translate-x-0'" 
         class="flex-1 flex flex-col h-full transition-transform duration-300">

        <!-- TOP NAV -->
        <nav class="flex justify-between items-center h-16 px-6 bg-transparent sticky top-0 z-30">
            <div class="flex items-center gap-4">

                {{-- Profile Avatar --}}
                <a href="{{ $role === 'learner' ? route('learner.profile') : route('implementor.profile') }}">
                    <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-white shadow">
                        @if ($profile && $profile->photo)
                            <img src="{{ asset('storage/' . $profile->photo->photos) }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full bg-orange-100 flex items-center justify-center text-orange-500 font-bold">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>
                </a>

                {{-- Greeting --}}
                <div class="flex flex-col leading-tight">
                    <h1 class="text-lg font-bold text-gray-800">Hello,
                        <span class="text-orange-500">{{ $profile->first_name ?? 'User' }}</span>!
                    </h1>
                    <span class="text-xs text-gray-400 uppercase tracking-wide">{{ $user->role->role_name ?? 'Dashboard' }}</span>
                </div>
            </div>

            {{-- Notifications --}}
            <div class="flex items-center gap-3">
                <livewire:partials.nav-notif />
            </div>
        </nav>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto p-4 scrollbar-hide">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>
</div>

<script>
    document.addEventListener("alpine:init", () => { lucide.createIcons(); });
</script>

@livewireScripts
</body>
</html>
