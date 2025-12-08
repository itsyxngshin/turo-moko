@extends('layouts.layout')

@section('title', 'Courses') 

@section('content')
<div class="space-y-8 px-4 md:px-6 mt-8">

    <!-- Header with Back Button -->
    <div class="flex items-center gap-4 mb-4">
        <!-- Back Button -->
<button onclick="history.back()"
        class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-3 py-1.5 md:px-4 md:py-2 rounded-full shadow-sm hover:shadow-md transition-all duration-200">
    <i data-lucide="arrow-left" class="w-4 h-4 md:w-5 md:h-5"></i>
</button>

        <!-- Page Title -->
        <div>
            <h1 class="text-2xl md:text-3xl font-bold">Active Courses</h1>
            <p class="text-gray-500 text-sm md:text-base mt-1">
                You are currently enrolled in <span class="font-semibold">{{ $activeCourses->count() }}</span> course(s).
            </p>
        </div>
    </div>

    <!-- Active Courses Grid -->
    <section class="bg-white rounded-2xl shadow-md p-4 md:p-6">
        @if($activeCourses->isEmpty())
            <p class="text-gray-500 text-center py-10 text-sm md:text-base">No active courses yet 📚</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                @foreach ($activeCourses as $course)
                    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden flex flex-col md:flex-row border">

                        <!-- Course Image -->
                        <div class="w-full md:w-1/2 h-36 md:h-auto">
                            <img src="{{ $course->background ?? '/images/course1.jpg' }}" 
                                 alt="{{ $course->course_title }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Course Info -->
                        <div class="p-3 md:p-5 flex flex-col justify-between w-full md:w-1/2">
                            <div>
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-1 md:mb-2 gap-1 md:gap-0">
                                    <p class="text-xs md:text-sm text-gray-400">Instructor: {{ $course->instructor ?? 'TBA' }}</p>
                                    <span class="text-xs md:text-sm bg-green-100 text-green-600 px-2 py-0.5 rounded-full">
                                        {{ $course->semester ?? 'Ongoing' }}
                                    </span>
                                </div>
                                <h3 class="text-base md:text-lg font-bold">{{ $course->course_title }}</h3>
                                <p class="text-xs md:text-sm text-gray-400 mt-1 line-clamp-2 md:line-clamp-3">
                                    {{ $course->description ?? 'No description available.' }}
                                </p>
                            </div>

                            <!-- Button -->
                            <div class="flex justify-end mt-3 md:mt-4">
                                <a href="{{ route('learner.courses.show', $course->id) }}"
                                   class="bg-black text-white px-3 py-1.5 md:px-5 md:py-2 rounded-full text-sm md:text-base hover:bg-gray-800 transition">
                                    View Course
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection

<script>
    document.addEventListener("DOMContentLoaded", () => {
        if (window.lucide) {
            lucide.createIcons();
        }
    });
</script>
