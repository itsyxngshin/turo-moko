@extends('layouts.learner-layout')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen px-4 sm:px-6 md:px-8 py-6 space-y-10">

 {{-- HERO COURSE SECTION --}}
@if($heroCourse)
    <div class="relative rounded-2xl shadow-lg overflow-hidden h-60 sm:h-72 md:h-80 group" x-data="{
            showEnrollModal: false,
            selectedCourse: null,
            selectedCourseTitle(course) {
                return course?.course_title ?? '';
            },
            openEnroll(courseId, courseTitle) {
                this.selectedCourse = courseId;
                this.showEnrollModal = true;
                this._selectedTitle = courseTitle;
            }
        }">

        {{-- Background Image --}}
        <img 
            src="{{ $heroCourse->activeCoverPhoto ? asset('storage/' . $heroCourse->activeCoverPhoto->path) : asset('storage/implementor/course/thumbnail.jpg') }}" 
            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
        >
        
        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-black/40"></div>
        
        {{-- Content --}}
        <div class="relative z-10 h-full flex flex-col justify-center text-white px-4 sm:px-8">
            
            {{-- Label --}}
            <p class="text-sm uppercase tracking-wider opacity-80 mb-1">
                @if($heroMode === 'resume')
                    Continue Learning
                @else
                    Featured Course
                @endif
            </p>

            {{-- Subject/Category --}}
            <p class="text-sm font-medium opacity-90">{{ $heroCourse->category->category_name ?? 'General' }}</p>
            
            {{-- Title --}}
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold mt-1 max-w-2xl">
                {{ $heroCourse->course_title }}
            </h2>
            
            {{-- BUTTON AREA --}}
            <div class="mt-6">
                @php
                    // Check if user is actively enrolled or completed the course
                    $isEnrolledHero = $heroCourse->enrollees()
                        ->where('users.id', auth()->id())
                        ->wherePivotIn('status', ['Active', 'Completed'])
                        ->exists();
                @endphp

                @if($isEnrolledHero)
                    {{-- Active or Completed → View Course --}}
                    <a href="{{ route('learner.course.show', $heroCourse) }}" 
                       class="inline-flex items-center gap-2 bg-white text-black px-6 py-3 rounded-full hover:bg-gray-200 transition-colors font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        Continue Course
                    </a>

                @else
                    {{-- Not Enrolled or Dropped → Enroll Class --}}
                    <button @click="openEnroll({{ $heroCourse->id }}, '{{ addslashes($heroCourse->course_title) }}')"
                        class="inline-flex items-center gap-2 bg-white text-black px-6 py-3 rounded-full hover:bg-gray-200 transition-colors font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                        Enroll Class
                    </button>
                @endif
            </div>

        </div>

        {{-- GLOBAL ENROLL MODAL --}}
        <div x-show="showEnrollModal" x-cloak x-transition.opacity
             class="fixed inset-0 z-[10000] flex items-center justify-center bg-black/50">
            <div @click.away="showEnrollModal=false" x-transition.scale
                 class="bg-white rounded-xl p-6 w-11/12 max-w-md shadow-2xl">
                <h3 class="text-lg font-semibold mb-4">Confirm Enrollment</h3>
                <p class="text-gray-600 mb-6">
                    Enroll in 
                    <strong x-text="_selectedTitle"></strong>?
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="showEnrollModal=false" class="px-4 py-2 rounded-full border hover:bg-gray-50">Cancel</button>
                    <form action="{{ route('learner.course.enroll', $heroCourse) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 rounded-full bg-black text-white hover:bg-gray-800">Confirm</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endif

{{-- ================= COURSE SUGGESTIONS ================= --}}
<div class="bg-white rounded-2xl shadow p-4 sm:p-8">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg sm:text-xl font-semibold">Course Suggestions</h3>
        <a href="{{ route('learner.show-all-courses') }}" class="text-sm text-gray-500 hover:underline">View All</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        @forelse($suggestedCourses as $course)
        <div class="bg-gray-50 rounded-2xl shadow overflow-hidden flex flex-col">

            {{-- IMAGE TOP --}}
            <div class="w-full h-48">
                <img
                    src="{{ $course->activeCoverPhoto
                        ? asset('storage/' . $course->activeCoverPhoto->path)
                        : asset('storage/implementor/course/thumbnail.jpg') }}"
                    class="w-full h-full object-cover"
                >
            </div>

            {{-- DETAILS BOTTOM --}}
            <div class="p-5 flex flex-col flex-1 justify-between">
                <div class="space-y-1">
                    <h3 class="text-lg md:text-xl font-medium text-black">{{ $course->course_title ?? '--' }}</h3>
                    <p class="mt-1 text-sm text-gray-600">Category: {{ $course->category->category_name ?? '--' }}</p>
                    <p class="mt-1 text-sm text-gray-600">Instructor: <span class="font-bold">{{ $course->implementer->profile->first_name ?? '--' }}</span></p>
                    <p class="text-xs mt-1 text-gray-400 mb-1">
                        {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') ?? '' }} - 
                        {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') ?? '' }}
                    </p>
                </div>

                {{-- BUTTON --}}
                <div x-data="{ open: false }" class="pt-4 relative">
                    @php
                        $isEnrolled = $course->enrollees()
                            ->where('users.id', auth()->id())
                            ->wherePivotIn('status', ['Active', 'Completed'])
                            ->exists();
                    @endphp

                    @if($isEnrolled)
                        <a href="{{ route('learner.course.show', $course) }}"
                           class="block w-full text-center bg-black text-white py-3 rounded-full hover:bg-gray-800">
                            Start Course
                        </a>
                    @else
                        <button @click="open = true"
                            class="w-full bg-black text-white py-3 rounded-full hover:bg-gray-800">
                            Enroll Class
                        </button>

                        {{-- SUGGESTION ENROLL MODAL --}}
                        <div x-show="open" x-cloak x-transition.opacity
                             class="fixed inset-0 z-[10000] flex items-center justify-center bg-black/50">
                            <div @click.away="open = false" x-transition.scale
                                 class="bg-white rounded-xl p-6 w-11/12 max-w-md">
                                <h3 class="text-lg font-semibold mb-4">Confirm Enrollment</h3>
                                <p class="text-gray-600 mb-6">
                                    Enroll in <strong>{{ $course->course_title }}</strong>?
                                </p>
                                <div class="flex justify-end gap-3">
                                    <button @click="open=false" class="px-4 py-2 rounded-full border hover:bg-gray-50">
                                        Cancel
                                    </button>
                                    <form method="POST" action="{{ route('learner.course.enroll', $course) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 rounded-full bg-black text-white hover:bg-gray-800">
                                            Confirm
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
            <p class="col-span-full text-gray-500 text-center">
                No suggested courses available.
            </p>
        @endforelse
    </div>
</div>
@endsection
