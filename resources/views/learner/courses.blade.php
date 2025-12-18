@extends('layouts.learner-layout')

@section('title', 'Courses') 

@section('content')
<div class="space-y-6 px-4 md:px-6">

    <!-- Header with Back Button -->
    <div class="flex items-center gap-3 mb-4">
        <!-- Back Button -->
        <button onclick="history.back()"
                class="flex items-center gap-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-2.5 py-1.5 md:px-3 md:py-1.5 rounded-full shadow-sm hover:shadow-md transition-all duration-200">
            <i data-lucide="arrow-left" class="w-4 h-4 md:w-5 md:h-5"></i>
        </button>

        <!-- Page Title -->
        <div>
            <h1 class="text-xl md:text-2xl font-bold">Active Courses</h1>
            <p class="text-gray-500 text-sm md:text-sm mt-0.5">
                You are currently enrolled in <span class="font-semibold">{{ $activeCourses->count() }}</span> course(s).
            </p>
        </div>
    </div>

    <!-- Active Courses Grid -->
    <section class="bg-white rounded-2xl shadow-md p-3 md:p-5">
        @if($activeCourses->isEmpty())
            <p class="text-gray-500 text-center py-8 text-sm md:text-base">No active courses yet 📚</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-5">
                @foreach ($activeCourses as $course)
                    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden flex flex-col md:flex-row border">

                        <!-- Course Image -->
                        <div class="w-full md:w-1/2 h-28 md:h-auto">
                            <img src="{{ optional($course->activeCoverPhoto)->path
                    ? asset('storage/' . $course->activeCoverPhoto->path)
                    : asset('storage/implementor/course/thumbnail.jpg') }}"
                                 alt="{{ $course->course_title }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Course Info -->
                        <div class="p-3 md:p-4 flex flex-col justify-between w-full md:w-1/2">
                            <div>
                                <h3 class="text-md md:text-lg font-medium text-black">{{ $course->course_title ?? '--' }}</h3>
                                <p class="mt-0.5 text-sm text-gray-600">Category: {{ $course->category->category_name ?? '--' }}</p>
                                <p class="mt-0.5 text-sm text-gray-600">Instructor: <span class="font-bold">{{ $course->implementer->profile->first_name ?? '--' }}</span></p>
                                <p class="text-xs mt-0.5 text-gray-400">
                                    {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') ?? '' }} - 
                                    {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') ?? '' }}
                                </p>
                            </div>

                            <!-- Button -->
                            <div class="flex justify-end mt-2 md:mt-3">
                                <a href="{{ route('learner.course.show', $course) }}"
                                   class="bg-black text-white px-3 py-1.5 md:px-4 md:py-1.5 rounded-full text-sm md:text-sm hover:bg-gray-800 transition">
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
