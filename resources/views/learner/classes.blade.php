@extends('layouts.layout')

@section('title', 'Classes')

@section('content')
<div class="space-y-8">

    <!-- Top Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-2xl p-5 flex items-center gap-4">
            <img src="https://img.icons8.com/fluency/48/000000/books.png" class="w-10 h-10" alt="Active Courses">
            <div>
                <p class="font-semibold text-lg">Active courses</p>
                <p class="text-gray-500 text-sm">5</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl p-5 flex items-center gap-4">
            <img src="https://img.icons8.com/fluency/48/000000/checked.png" class="w-10 h-10" alt="Completed">
            <div>
                <p class="font-semibold text-lg">Completed course</p>
                <p class="text-gray-500 text-sm">15</p>
            </div>
        </div>

        <div class="bg-white shadow rounded-2xl p-5 flex items-center gap-4">
            <img src="https://img.icons8.com/fluency/48/000000/todo-list.png" class="w-10 h-10" alt="Pending">
            <div>
                <p class="font-semibold text-lg">Pending activities</p>
                <p class="text-gray-500 text-sm">5</p>
            </div>
        </div>
    </div>

    <!-- Banner -->
    <div class="relative h-60 rounded-2xl overflow-hidden shadow">
        <img src="https://i.imgur.com/6IUbEMF.png" alt="Course Banner" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 h-full flex flex-col justify-center px-8 text-white">
            <p class="text-sm">Subject here</p>
            <h2 class="text-2xl font-bold">Course Name</h2>
            <button class="mt-4 bg-white text-black px-4 py-2 rounded-full w-fit hover:bg-gray-200 flex items-center gap-2">
                <i data-lucide="play" class="w-4 h-4"></i> Continue course
            </button>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="flex justify-between items-center">
        <h3 class="font-semibold text-lg">Courses you’re taking</h3>
        <button class="text-sm text-gray-500">View all</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card -->
        <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border">
            <img src="https://images.unsplash.com/photo-1587614382346-4ec65b6d6b6e" alt="Course Image" class="w-40 h-auto object-cover">
            <div class="flex-1 p-5 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs text-gray-400">1st Sem SY 2024-2025</p>
                        <button aria-label="More options" class="text-gray-400 hover:text-gray-600">
                            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <h4 class="font-semibold text-lg mb-1">Course Name</h4>
                    <p class="text-sm text-gray-500 leading-snug">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.
                    </p>
                </div>
                <div class="mt-4 flex items-center gap-4">
                    <div class="flex items-center gap-2 w-full">
                        <span class="text-xs text-gray-500">50%</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-black" style="width: 50%;"></div>
                        </div>
                    </div>
                    <button class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800">
                        Continue
                    </button>
                </div>
            </div>
        </div>

        <!-- Duplicate course card (you can loop later) -->
        <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border">
            <img src="https://images.unsplash.com/photo-1606761568499-6d2451b23c64" alt="Course Image" class="w-40 h-auto object-cover">
            <div class="flex-1 p-5 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-2">
                        <p class="text-xs text-gray-400">1st Sem SY 2024-2025</p>
                        <button aria-label="More options" class="text-gray-400 hover:text-gray-600">
                            <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <h4 class="font-semibold text-lg mb-1">Course Name</h4>
                    <p class="text-sm text-gray-500 leading-snug">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.
                    </p>
                </div>
                <div class="mt-4 flex items-center gap-4">
                    <div class="flex items-center gap-2 w-full">
                        <span class="text-xs text-gray-500">50%</span>
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-black" style="width: 50%;"></div>
                        </div>
                    </div>
                    <button class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
