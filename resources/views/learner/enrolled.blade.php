@extends('layouts.layout')

@section('title', 'Learner Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Enrolled Courses -->
    <section class="bg-white rounded-2xl shadow p-6">
        <h3 class="font-semibold text-lg mb-6">Enrolled courses</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Card -->
            <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border">
                <img src="https://images.unsplash.com/photo-1587614382346-4ec65b6d6b6e" 
                     alt="Course Image" class="w-40 h-auto object-cover">
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
                            Start
                        </button>
                    </div>
                </div>
            </div>

            <!-- Duplicate card (you’ll loop later) -->
            <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border">
                <img src="https://images.unsplash.com/photo-1606761568499-6d2451b23c64" 
                     alt="Course Image" class="w-40 h-auto object-cover">
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
                            Start
                        </button>
                    </div>
                </div>
            </div>

            <!-- Repeat more cards as needed... -->
        </div>
    </section>
</div>
@endsection
