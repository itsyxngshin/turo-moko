@extends('layouts.layout')

@section('title', 'Courses') 

@section('content')
<div class="space-y-8 pl-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold">Your Active Courses</h1>
        <p class="text-gray-500 text-sm mt-1">
            You are currently enrolled in <span class="font-semibold">{{ $activeCourses->count() }}</span> course(s).
        </p>
    </div>

    <!-- Active Courses Grid -->
    <section class="bg-white rounded-2xl shadow-md p-6">
        @if($activeCourses->isEmpty())
            <p class="text-gray-500 text-center py-10">No active courses yet 📚</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($activeCourses as $course)
                    <div class="bg-white rounded-2xl shadow-md flex overflow-hidden border hover:shadow-lg transition">
                        
                        <!-- Course Image -->
                        <div class="w-1/2">
                            <img src="{{ $course->background ?? '/images/course1.jpg' }}" 
                                 alt="{{ $course->course_title }}" 
                                 class="w-full h-full object-cover">
                        </div>

                        <!-- Course Info -->
                        <div class="p-5 flex flex-col justify-between w-1/2 h-full">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <p class="text-xs text-gray-400">Instructor: {{ $course->instructor ?? 'TBA' }}</p>
                                    <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">
                                        {{ $course->semester ?? 'Ongoing' }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold">{{ $course->course_title }}</h3>
                                <p class="text-xs text-gray-400 mt-1">{{ $course->description ?? 'No description available.' }}</p>
                            </div>
                            
                            <!-- Button -->
                            <div class="flex justify-end mt-4">
                                <a href="{{ route('learner.courses.show', $course->id) }}"
                                   class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800">
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
