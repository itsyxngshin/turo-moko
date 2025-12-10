@extends('layouts.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-[1720px] mx-auto px-6">

    <!-- Stats Row -->
    <section class="mt-3 grid grid-cols-1 lg:grid-cols-4 gap-6"> <!-- reduced top margin slightly -->

        <!-- Enrollees Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-[335px]">
            <!-- Large faint background icon -->
            <svg class="absolute inset-0 m-auto w-32 h-32 text-indigo-100 opacity-20" fill="currentColor" viewBox="0 0 32 32">
                <path d="M7.5 18A3.5 3.5 0 0 0 4 21.5v.5c0 2.393 1.523 4.417 3.685 5.793C9.859 29.177 12.802 30 16 30s6.14-.823 8.315-2.206C26.477 26.418 28 24.394 28 22v-.5a3.5 3.5 0 0 0-3.5-3.5z"/>
                <path d="M16 16a7 7 0 1 0 0-14a7 7 0 0 0 0 14"/>
            </svg>
            <div class="w-16 h-16 rounded-full bg-indigo-50 flex items-center justify-center absolute top-4 left-4 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-indigo-500" fill="currentColor" viewBox="0 0 32 32">
                    <path d="M7.5 18A3.5 3.5 0 0 0 4 21.5v.5c0 2.393 1.523 4.417 3.685 5.793C9.859 29.177 12.802 30 16 30s6.14-.823 8.315-2.206C26.477 26.418 28 24.394 28 22v-.5a3.5 3.5 0 0 0-3.5-3.5z"/>
                    <path d="M16 16a7 7 0 1 0 0-14a7 7 0 0 0 0 14"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500">Enrollees</h3>
                <div class="mt-1 flex items-center gap-2 justify-end">
                    <p class="text-3xl font-semibold text-slate-900">{{ $enrolleesCount ?? '--' }}</p>
                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Current</span>
                </div>
            </div>
        </div>

        <!-- Submissions Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-[335px]">
            <svg class="absolute inset-0 m-auto w-32 h-32 text-amber-200 opacity-20" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2 7h12v5a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/>
                <path d="M9.5 2A1.5 1.5 0 0 1 11 3.5V5h1c.506 0 .967.19 1.32.5H2.68C3.034 5.19 3.495 5 4 5h1V3.5A1.5 1.5 0 0 1 6.5 2z"/>
            </svg>
            <div class="w-16 h-16 rounded-full bg-amber-50 flex items-center justify-center absolute top-4 left-4 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-amber-500" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 7h12v5a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/>
                    <path d="M9.5 2A1.5 1.5 0 0 1 11 3.5V5h1c.506 0 .967.19 1.32.5H2.68C3.034 5.19 3.495 5 4 5h1V3.5A1.5 1.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500">Submissions</h3>
                <div class="mt-1 flex items-center gap-2 justify-end">
                    <p class="text-3xl font-semibold text-slate-900">{{ $submissionsCount ?? '--' }}</p>
                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">Active</span>
                </div>
            </div>
        </div>

        <!-- Evaluations Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-[335px]">
            <svg class="absolute inset-0 m-auto w-32 h-32 text-sky-200 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
            </svg>
            <div class="w-16 h-16 rounded-full bg-sky-50 flex items-center justify-center absolute top-4 left-4 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                    <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500">Evaluations</h3>
                <div class="mt-1 flex items-center gap-2 justify-end">
                    <p class="text-3xl font-semibold text-slate-900">{{ $evaluationsCount ?? '--' }}</p>
                </div>
            </div>
        </div>

        <!-- Courses Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-[335px]">
            <svg class="absolute inset-0 m-auto w-32 h-32 text-green-200 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
            </svg>
            <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center absolute top-4 left-4 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                    <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500">Courses</h3>
                <p class="mt-1 text-3xl font-semibold text-slate-900">{{ $coursesCount ?? '--' }}</p>
            </div>
        </div>

    </section>

    <!-- Instructor Courses Section -->
    <section class="mt-10 bg-white rounded-2xl shadow-md border border-black/20">
    <div class="flex items-center justify-between px-6 py-4">
        <h2 class="text-2xl md:text-[28px] font-semibold mt-4 ml-4">
            @isset($instructor) <span class="font-bold">{{ $instructor->profile->first_name }}</span>'s Courses @else No Instructor Found @endisset
        </h2>
        <a href="{{ route('implementor.all-courses', $courses) }}" class="text-sm text-gray-500 hover:underline">View All</a>
    </div>

    <div class="px-6 pb-8 grid grid-cols-1 xl:grid-cols-2 gap-6">
        @forelse($courses->take(2) as $course) {{-- Only 2 cards --}}
            <article class="relative rounded-2xl border border-gray-300 bg-white shadow-sm overflow-hidden">
    <div class="grid md:grid-cols-[300px,1fr] grid-cols-1 gap-4 p-4 relative"> <!-- make this relative -->
        <img src="{{ $course->activeCoverPhoto ? asset('storage/'.$course->activeCoverPhoto->path) : '/img/default-cover.png' }}"
             alt="Course cover"
             class="w-full md:w-[300px] h-[180px] object-cover rounded-xl flex-shrink-0" />

        <div class="pr-4 flex flex-col justify-between h-full">
            <div>
                <h3 class="text-lg md:text-xl font-medium text-black">{{ $course->course_title ?? '--' }}</h3>
                <p class="mt-1 text-sm text-gray-600">Category: {{ $course->category->category_name ?? '--' }}</p>
                <p class="mt-1 text-sm text-gray-600">Instructor: <span class="font-bold">{{ $instructor->profile->first_name ?? '--' }}</span></p>
                <p class="text-xs mt-1 text-gray-400 mb-1">
                    {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') ?? '' }} - 
                    {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') ?? '' }}
                </p>
            </div>

            <!-- Bottom-right button -->
            <div class="absolute bottom-4 right-4">
                <a href="{{ route('implementor.course-information', $course->course_code) }}"
                   class="inline-flex items-center justify-center h-10 px-6 rounded-full bg-black text-white text-sm font-medium text-center shadow-lg hover:scale-105 transform transition">
                    View Details
                </a>
            </div>
        </div>
    </div>
</article>
        @empty
            <p class="text-gray-500">You have no courses created yet.</p>
        @endforelse
    </div>
</section>


</div>
@endsection
