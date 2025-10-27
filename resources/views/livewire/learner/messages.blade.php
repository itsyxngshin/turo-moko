@extends('layouts.layout3')

@section('content')
<div class="flex h-[calc(100vh-140px)] bg-gray-50 overflow-hidden">
    <!-- Chat List Panel -->
    <div
        class="flex flex-col bg-white m-4 rounded-3xl shadow-sm border border-gray-200 transition-all duration-300
               w-20 md:w-1/3"
    >
        <!-- Header -->
        <div class="hidden md:flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-gray-800 font-semibold text-xl">Messages</h2>
            <button class="p-2 rounded-full hover:bg-gray-100">
                <i data-lucide="search" class="w-5 h-5 text-gray-600"></i>
            </button>
        </div>

        <!-- Search Bar (Hidden on small screens) -->
        <div class="hidden md:block px-6 py-3 border-b border-gray-100">
            <input
                type="text"
                placeholder="Search chats..."
                class="w-full border border-gray-200 rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-orange-300"
            />
        </div>

        <!-- Chat List -->
        <div class="flex-1 overflow-y-auto px-2 py-4 space-y-2 hide-scrollbar">
            @foreach (range(1, 10) as $i)
            <div
                class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-100 transition cursor-pointer group"
            >
                <!-- Avatar -->
                <div class="relative mx-auto md:mx-0">
                    <div
                        class="w-12 h-12 rounded-full bg-orange-400 flex items-center justify-center text-white font-semibold text-lg"
                    >
                        A
                    </div>
                    <span
                        class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"
                    ></span>
                </div>

                <!-- Chat Info (Hidden on small screens) -->
                <div class="hidden md:flex flex-1 min-w-0 flex-col">
                    <div class="flex justify-between items-center">
                        <p class="text-gray-800 font-semibold truncate">Advisor {{ $i }}</p>
                        <span class="text-xs text-gray-400">2m ago</span>
                    </div>
                    <p class="text-sm text-gray-500 truncate">
                        Sure! You’re currently 75% done with your module.
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Chat Panel (Livewire Component) -->
    <div class="flex-1 m-0">
        @livewire('learner.chat')
    </div>
</div>

<!-- Hide scrollbar CSS -->
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none; /* IE and Edge */
        scrollbar-width: none; /* Firefox */
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();
    });
</script>
@endsection
