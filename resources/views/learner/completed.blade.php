@extends('layouts.learner-layout')

@section('title', 'Completed Courses')

@section('content')
<div class="space-y-8 pl-6">

    <!-- Page Header with Back Button -->
    <div class="flex items-center gap-3 mb-4 mt-8 ml-4">
        <!-- Back Button -->
        <button onclick="history.back()"
                class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 p-2 rounded-full shadow-sm hover:shadow-md transition-all duration-200">
            <i data-lucide="arrow-left" class="w-4 h-4 md:w-5 md:h-5"></i>
        </button>

        <!-- Page Title -->
        <h1 class="text-2xl font-bold">Completed Courses</h1>
    </div>

    <p class="text-gray-500 text-sm mt-1">
        You have completed <span class="font-semibold">{{ $completedCoursesCount }}</span> course(s).
    </p>

    <!-- Completed Courses Grid -->
    <section class="bg-white rounded-2xl shadow-md p-6 mt-4">
        @if($completedCourses->isEmpty())
            <p class="text-gray-500 text-center py-10">No completed courses yet.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($completedCourses as $course)
                    <div class="bg-white rounded-2xl shadow-md flex overflow-hidden border hover:shadow-lg transition">
                        <!-- Course Image -->
                        <div class="w-1/2">
                            <img src="{{ $course->activeCoverPhoto
                            ? asset('storage/' . $course->activeCoverPhoto->path)
                            : asset('storage/implementor/course/thumbnail.jpg') }}"
                                 alt="{{ $course->course_title }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Course Info -->
                        <div class="p-4 flex flex-col justify-between w-1/2 h-full">
                            <div>
                               <h3 class="text-lg md:text-xl font-medium text-black">{{ $course->course_title ?? '--' }}</h3>
                            <p class="mt-1 text-sm text-gray-600">Category: {{ $course->category->category_name ?? '--' }}</p>
                            <p class="mt-1 text-sm text-gray-600">Instructor: <span class="font-bold">{{ $course->implementer->profile->first_name ?? '--' }}</span></p>
                            <p class="text-xs mt-1 text-gray-400 mb-1">
                                {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') ?? '' }} - 
                                {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') ?? '' }}
                            </p>
                            </div>
                            <div class="flex justify-end mt-2">
                                <span class="flex items-center gap-1 text-green-600 font-medium text-sm">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Completed
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
