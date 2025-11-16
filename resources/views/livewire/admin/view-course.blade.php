@extends('layouts.layout')

@section('title', 'Course Information')
@section('page-title', 'Course Information')

@section('content')
<div class="p-0">
    <!-- Header -->
    <div class="relative h-56 bg-cover bg-center rounded-lg overflow-hidden" 
        style="background-image: url('https://images.unsplash.com/photo-1608506573186-631f3ff1f6e3');">

        @if ($course->activeCoverPhoto)
            <img 
                src="{{ asset('storage/' . $course->activeCoverPhoto->path) }}" 
                alt="Course Cover" 
                class="w-full h-full object-cover"
            >
        @endif

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col px-8 text-white rounded-lg">
            <h1 class="text-3xl font-bold mt-auto mb-1">{{ $course->name ?? '--' }}</h1>
            <p class="max-w-2xl mb-2">{{ $course->background ?? '--' }}</p>

            <p class="text-sm text-gray-300 mb-8">
                {{ $course->enrollees->count() }}/{{ $course->student_limit}} 
                {{ Str::plural('Student', $course->enrollees->count()) }} Enrolled
            </p>
        </div>
    </div>
</div>

<!-- Course Intro -->
<div class="px-9 py-6 rounded-lg my-6 border shadow-sm bg-white">
    <div class="relative text-center">
        <h2 class="text-[30px] font-bold mb-2">Course Introduction</h2>
    </div>

    <!-- Modules -->
    <div class="space-y-4 mt-6">
        @foreach ($modules as $module)
            <div class="bg-white w-full py-10 rounded-lg shadow-sm justify-between border p-4 flex">
                <div class="flex items-center px-5 gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                        <path fill="#ef5350" d="M13 9h5.5L13 3.5zM6 2h8l6 6v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2z"/>
                    </svg>
                    <span>Module: {{ $module->module_title }}</span>
                </div>
            </div>
        @endforeach

        @foreach ($assignments as $assignment)
            <div class="bg-white w-full flex rounded-lg justify-between shadow-sm border p-4 mb-6">
                <div class="flex items-center px-5 gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                        <path fill="#0094f0" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/>
                    </svg>
                    <span>{{ $assignment->title ?? '' }}: {{ $assignment->module->module_title ?? '' }}</span>
                </div>
                <div class="flex items-center px-5 gap-2">
                    <span class="text-sm text-gray-500">Due {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d') }}</span>
                </div>
            </div>
        @endforeach

        @foreach ($evaluations as $evaluation)
            <div class="bg-white w-full flex py-10 rounded-lg justify-between shadow-sm border p-4 mb-6">
                <div class="flex items-center px-5 gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22 16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4c0-1.11.89-2 2-2h12a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span>{{ $evaluation->title }}</span>
                </div>
                <div class="flex items-center px-5 gap-2">
                    <span class="text-sm text-gray-500">
                        Due {{ \Carbon\Carbon::parse($evaluation->due_date)->format('M d') }}
                    </span>
                </div>
            </div>
        @endforeach

        @foreach($announcements as $announcement)
            <div class="bg-white w-full flex items-start p-4 rounded-lg shadow hover:bg-gray-50 cursor-pointer mb-3">
                <div class="flex-1 text-left">
                    <h3 class="font-semibold">{{ $announcement->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $announcement->created_at->format('F j, Y') }}</p>
                    <p class="mt-2 text-gray-700">{{ Str::limit($announcement->content, 150) }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
