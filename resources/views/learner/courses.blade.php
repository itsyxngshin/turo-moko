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
                            <img src="{{ optional($course->activeCoverPhoto)->path
                    ? asset('storage/' . $course->activeCoverPhoto->path)
                    : asset('storage/implementor/course/thumbnail.jpg') }}"
                                 alt="{{ $course->course_title }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Course Info -->
                        <div class="p-3 md:p-5 flex flex-col justify-between w-full md:w-1/2">
                            <div>
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-1 md:mb-2 gap-1 md:gap-0">
                                    <h3 class="text-lg md:text-xl font-medium text-black">{{ $course->course_title ?? '--' }}</h3>
                           
                                </div>
                                
                             <p class="mt-1 text-sm text-gray-600">Category: {{ $course->category->category_name ?? '--' }}</p>
                            <p class="mt-1 text-sm text-gray-600">Instructor: <span class="font-bold">{{ $course->implementer->profile->first_name ?? '--' }}</span></p>
                            <p class="text-xs mt-1 text-gray-400 mb-1">
                                {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') ?? '' }} - 
                                {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') ?? '' }}
                            </p>
                            </div>

                            <!-- Button -->
                            <div class="flex justify-end mt-3 md:mt-4">
                                <a href="{{ route('learner.course.show', $course->id) }}"
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
