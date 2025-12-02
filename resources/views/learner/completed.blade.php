@extends('layouts.layout')

@section('title', 'Completed Courses')

@section('content')
<div class="space-y-8 pl-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold">Completed Courses</h1>
        <p class="text-gray-500 text-sm mt-1">
            You have completed <span class="font-semibold">{{ $completedCoursesCount }}</span> course(s).
        </p>
    </div>

    <!-- Completed Courses Grid -->
    <section class="bg-white rounded-2xl shadow-md p-6">
        @if($completedCourses->isEmpty())
            <p class="text-gray-500 text-center py-10">No completed courses yet.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($completedCourses as $course)
                    <div class="bg-white rounded-2xl shadow-md flex overflow-hidden border hover:shadow-lg transition">
                        <!-- Course Image -->
                        <div class="w-1/2">
                            <img src="{{ $course->image ?? '/images/course1.jpg' }}" 
                                 alt="{{ $course->name }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Course Info -->
                        <div class="p-4 flex flex-col justify-between w-1/2 h-full">
                            <div>
                                <p class="text-xs text-gray-400">{{ $course->semester ?? 'Semester not set' }}</p>
                                <p class="text-sm text-gray-500">{{ $course->subject ?? 'Subject' }}</p>
                                <h3 class="text-lg font-bold">{{ $course->name }}</h3>
                                <p class="text-xs text-gray-400 mt-1">{{ $course->description ?? 'No description available.' }}</p>
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
