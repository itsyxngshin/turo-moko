@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="min-h-screen space-y-8 px-4 sm:px-6 md:px-8 overflow-y-auto">

    <!-- ⭐ RECENTLY ACCESSED — Updated to Featured Style -->
    @if($recentCourses->isNotEmpty())
        @php $course = $recentCourses->first(); @endphp

        @if($course->enrollees->contains(auth()->id()))
        <div class="relative rounded-2xl overflow-hidden shadow-lg h-60 sm:h-72 md:h-80">
            <img src="{{ $course->activeCoverPhoto 
                        ? asset('storage/' . $course->activeCoverPhoto->path) 
                        : asset('storage/implementor/course/thumbnail.jpg') }}"
                 alt="{{ $course->course_title }}"
                 class="absolute inset-0 w-full h-full object-cover">

            <div class="absolute inset-0 bg-black/40"></div>

            <div class="relative z-10 h-full flex flex-col justify-center px-4 sm:px-8 text-white">
                <p class="text-sm sm:text-base">{{ $course->subject }}</p>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold">
                    {{ $course->course_title }}
                </h2>

                <a href="{{ route('learner.course.show', $course) }}"
                   class="mt-4 bg-white text-black px-4 py-2 sm:px-5 sm:py-3 rounded-full w-fit 
                          hover:bg-gray-200 flex items-center gap-2 text-sm sm:text-base">
                    <i data-lucide="play" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    Continue course
                </a>
            </div>
        </div>
        @endif
    @endif


    <!-- ⭐ SUGGESTED COURSES — Updated to Featured Card Design -->
    <div class="bg-white rounded-2xl shadow-md p-4 sm:p-8 mt-8">
        <div class="flex justify-between items-center mb-4 sm:mb-6">
            <h3 class="text-lg sm:text-xl font-semibold">Course Suggestions</h3>
            <a href="#" 
               class="text-sm text-gray-500 hover:underline">
               View All
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8">
            @forelse($suggestedCourses as $course)

            <div class="bg-gray-50 rounded-2xl shadow flex flex-col sm:flex-row overflow-hidden h-auto sm:h-72">
                
                <!-- ⭐ IMAGE TOP (MOBILE), LEFT (DESKTOP) -->
                <div class="w-full sm:w-1/2 h-48 sm:h-full">
                    <img src="{{ $course->activeCoverPhoto 
                                ? asset('storage/' . $course->activeCoverPhoto->path) 
                                : asset('storage/implementor/course/thumbnail.jpg') }}"
                         alt="{{ $course->course_title }}"
                         class="w-full h-full object-cover">
                </div>

                <!-- ⭐ COURSE TEXT / BUTTON -->
                <div class="p-4 sm:p-6 flex flex-col justify-between w-full sm:w-1/2">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-400">1st Sem SY 2024-2025</p>
                        <p class="text-sm sm:text-base text-gray-500">{{ $course->subject }}</p>
                        <h3 class="text-lg sm:text-xl font-bold">{{ $course->course_title }}</h3>

                        <p class="text-xs sm:text-sm text-gray-400 mt-1 line-clamp-2">
                            {{ $course->background }}
                        </p>
                    </div>

                    <!-- ⭐ ENROLL / START BUTTON -->
                    <div class="flex justify-end mt-4" x-data="{ open: false }">

                        @php
                            $isEnrolled = $course->enrollees()
                                ->where('users.id', auth()->id())
                                ->exists();
                        @endphp

                        @if($isEnrolled)
                            <a href="{{ route('learner.course.show', $course) }}"
                               class="bg-black text-white px-4 sm:px-5 py-2 sm:py-3 rounded-full 
                                      hover:bg-gray-800 text-sm sm:text-base">
                               Start Course
                            </a>

                        @else
                            <button @click="open = true"
                                    class="bg-black text-white px-4 sm:px-5 py-2 sm:py-3 rounded-full 
                                           hover:bg-gray-800 text-sm sm:text-base">
                                Enroll Class
                            </button>

                            <!-- ⭐ ENROLL MODAL -->
                            <div x-show="open" x-cloak 
                                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                
                                <div @click.away="open = false" 
                                     class="bg-white rounded-xl p-6 w-96">
                                        
                                    <h3 class="text-lg font-semibold mb-4">Confirm Enrollment</h3>

                                    <p class="text-gray-600 mb-6">
                                        Are you sure you want to enroll in 
                                        <strong>{{ $course->course_title }}</strong>?
                                    </p>

                                    <div class="flex justify-end gap-3">
                                        <button @click="open = false" 
                                                class="px-4 py-1 rounded-full border hover:bg-gray-100">
                                            Cancel
                                        </button>

                                        <form method="POST" action="{{ route('learner.course.enroll', $course) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="px-4 py-1 rounded-full bg-black text-white hover:bg-gray-800">
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
            <p class="text-gray-500 col-span-1 sm:col-span-2">No suggested courses available.</p>
            @endforelse
        </div>
    </div>

</div>
@endsection
