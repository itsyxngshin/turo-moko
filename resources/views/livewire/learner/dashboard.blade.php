@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen px-4 sm:px-6 md:px-8 py-6 space-y-10">

    {{-- ================= RECENTLY ACCESSED ================= --}}
    @if($recentCourses->isNotEmpty())
        @php $course = $recentCourses->first(); @endphp

        @if($course->enrollees->contains(auth()->id()))
        <div class="relative rounded-2xl shadow-lg overflow-hidden h-60 sm:h-72 md:h-80">
            <img
                src="{{ $course->activeCoverPhoto
                    ? asset('storage/' . $course->activeCoverPhoto->path)
                    : asset('storage/implementor/course/thumbnail.jpg') }}"
                class="absolute inset-0 w-full h-full object-cover"
            >

            <div class="absolute inset-0 bg-black/40"></div>

            <div class="relative z-10 h-full flex flex-col justify-center text-white px-4 sm:px-8">
                <p class="text-sm">{{ $course->subject }}</p>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold">
                    {{ $course->course_title }}
                </h2>

                <a href="{{ route('learner.course.show', $course) }}"
                   class="mt-4 inline-flex items-center gap-2 bg-white text-black px-5 py-3 rounded-full w-fit hover:bg-gray-200">
                    <i data-lucide="play" class="w-5 h-5"></i>
                    Continue course
                </a>
            </div>
        </div>
        @endif
    @endif

    {{-- ================= COURSE SUGGESTIONS ================= --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-8">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg sm:text-xl font-semibold">Course Suggestions</h3>
            <a href="#" class="text-sm text-gray-500 hover:underline">View All</a>
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
                        <p class="text-xs text-gray-400">1st Sem SY 2024-2025</p>
                        <p class="text-sm text-gray-500">{{ $course->subject }}</p>
                        <h3 class="text-lg font-bold">{{ $course->course_title }}</h3>
                        <p class="text-sm text-gray-400 line-clamp-3">
                            {{ $course->background }}
                        </p>
                    </div>

                    {{-- BUTTON --}}
                    <div class="pt-4" x-data="{ open: false }">

                        @php
                            $isEnrolled = $course->enrollees()
                                ->where('users.id', auth()->id())
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

                            {{-- MODAL --}}
                            <div x-show="open" x-cloak
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
                                <div @click.away="open = false"
                                     class="bg-white rounded-xl p-6 w-11/12 max-w-md">
                                    <h3 class="text-lg font-semibold mb-4">
                                        Confirm Enrollment
                                    </h3>
                                    <p class="text-gray-600 mb-6">
                                        Enroll in <strong>{{ $course->course_title }}</strong>?
                                    </p>
                                    <div class="flex justify-end gap-3">
                                        <button @click="open=false"
                                                class="px-4 py-2 rounded-full border">
                                            Cancel
                                        </button>
                                        <form method="POST"
                                              action="{{ route('learner.course.enroll', $course) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="px-4 py-2 rounded-full bg-black text-white">
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
</div>
@endsection
