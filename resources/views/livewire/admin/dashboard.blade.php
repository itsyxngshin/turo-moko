@extends('layouts.layout') 

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-[1720px] mx-auto px-6 pb-16">

    <!-- Stats Row -->
    <section class="mt-8 grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Enrollees -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-gray-300/30 grid place-items-center">
                <img src="/img/vector-22.svg" alt="icon" class="w-10 h-10" />
            </div>
            <div>
                <h3 class="text-2xl font-semibold">Enrollees</h3>
                <p class="text-xl text-gray-600 tracking-wide">{{ $enrolleesCount ?? 0 }}</p>
            </div>
        </div>

        <!-- Implementors -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-gray-300/20 grid place-items-center">
                <img src="/img/fluent-emoji-flat-briefcase.svg" alt="briefcase" class="w-10 h-10" />
            </div>
            <div>
                <h3 class="text-2xl font-semibold">Implementors</h3>
                <p class="text-xl text-gray-600 tracking-wide">{{ $implementorsCount ?? 0 }}</p>
            </div>
        </div>

        <!-- Courses -->
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center gap-6">
            <div class="w-20 h-20 rounded-full bg-gray-300/20 grid place-items-center">
                <img src="/img/noto-books.svg" alt="books" class="w-10 h-10" />
            </div>
            <div>
                <h3 class="text-2xl font-semibold">Courses</h3>
                <p class="text-xl text-gray-600 tracking-wide">{{ $coursesCount ?? 0 }}</p>
            </div>
        </div>

        <!-- Overall Donut Card -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h3 class="text-2xl font-medium">Overall</h3>

            <div class="mt-4 relative w-40 h-40 mx-auto">
                <!-- Outer ring -->
                <svg viewBox="0 0 40 40" class="w-40 h-40 rotate-[-90deg]">
                    <circle cx="20" cy="20" r="18" fill="none" stroke="#E5F6FE" stroke-width="4" />
                    <circle cx="20" cy="20" r="18" fill="none" stroke="#8BDCFC" stroke-width="4"
                        stroke-dasharray="113"
                        stroke-dashoffset="{{ 113 - (($activeStudents ?? 0)/100*113) }}"
                        stroke-linecap="round" />
                </svg>

                <!-- Inner ring -->
                <div class="absolute inset-0 grid place-items-center">
                    <svg viewBox="0 0 36 36" class="w-28 h-28 rotate-[-90deg]">
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#E5EEF2" stroke-width="4" />
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#A0BDCB" stroke-width="4"
                            stroke-dasharray="100.5"
                            stroke-dashoffset="{{ 100.5 - (($activeMentors ?? 0)/100*100.5) }}"
                            stroke-linecap="round" />
                    </svg>
                </div>
            </div>

            <div class="mt-4 flex items-start justify-between gap-4 px-2">
                <div>
                    <div class="text-3xl font-medium leading-none">{{ $activeStudents ?? 0 }}%</div>
                    <div class="text-sm text-black/80">Active Students</div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-medium leading-none">{{ $activeMentors ?? 0 }}%</div>
                    <div class="text-sm text-black/80">Active Mentors</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses List -->
    <section class="mt-10 bg-white rounded-2xl shadow-md border border-black/20">
        <div class="flex items-center justify-between px-6 py-4">
            <h2 class="text-2xl md:text-[28px] font-semibold">All courses</h2>
            <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center h-10 px-6 rounded-full border border-gray-300 text-base bg-white hover:bg-gray-50">View all</a>
        </div>

        <div class="px-6 pb-8 grid grid-cols-1 xl:grid-cols-2 gap-6">
            @foreach($latestCourses ?? [] as $course)
            <article class="relative rounded-2xl border border-gray-300 bg-white shadow-sm overflow-hidden">
                <button class="absolute top-3 right-3 w-8 h-8 grid place-items-center rounded-full hover:bg-gray-100" aria-label="menu">
                    <img src="/img/vector-7.svg" alt="more" class="w-5 h-5" />
                </button>
                <div class="grid grid-cols-[360px,1fr] gap-6 p-4">
                    <img src="{{ $course->cover_image ?? '/img/default-course.png' }}" alt="Course cover" class="w-[356px] h-[200px] object-cover rounded-xl" />
                    <div class="pr-4">
                        <h3 class="text-lg md:text-xl font-medium text-black">{{ $course->title ?? 'Course Name' }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ $course->semester ?? '' }}</p>
                        <p class="mt-3 text-sm text-black/80">{{ $course->description ?? '' }}</p>
                        <div class="mt-4">
                            <a href="{{ route('courses.show', $course->id ?? 0) }}" class="inline-flex h-7 px-5 rounded-full bg-black text-white text-xs font-medium">Start</a>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>
</div>
@endsection
