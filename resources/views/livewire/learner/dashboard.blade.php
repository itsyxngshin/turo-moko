@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="h-screen overflow-y-auto space-y-8 pl-6">

    <!-- Recently Accessed (Featured Style) -->
    @if($recentCourses->isNotEmpty())
        @php $course = $recentCourses->first(); @endphp
        @if($course->enrollees->contains(auth()->id()))
            <div class="relative rounded-2xl overflow-hidden shadow-lg h-60">
                <img src="{{ $course->activeCoverPhoto ? asset('storage/' . $course->activeCoverPhoto->path) : asset('images/banner.jpg') }}"
                     alt="{{ $course->course_title }}"
                     class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/40"></div>
                <div class="relative z-10 h-full flex flex-col justify-center px-8 text-white">
                    <p class="text-sm">{{ $course->subject }}</p>
                    <h2 class="text-2xl font-bold">{{ $course->course_title }}</h2>
                    <a href="{{ route('learner.course.show', $course) }}"
                       class="mt-4 bg-white text-black px-4 py-2 rounded-full w-fit hover:bg-gray-200 flex items-center gap-2">
                        <i data-lucide="play" class="w-4 h-4"></i> Continue course
                    </a>
                </div>
            </div>
        @endif
    @endif

    <!-- Suggested Courses Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 container mx-auto mt-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Course Suggestions</h3>
            <a href="{{ route('learner.show-all-courses') }}" class="text-sm text-gray-500 hover:underline">View All</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($suggestedCourses as $course)
                <div class="bg-gray-50 rounded-2xl shadow flex overflow-hidden h-56">
                    <!-- Course Image -->
                    <div class="w-1/2 h-full">
                        <img src="{{ $course->activeCoverPhoto ? asset('storage/' . $course->activeCoverPhoto->path) : asset('images/banner.jpg') }}"
                             alt="{{ $course->course_title }}"
                             class="w-full h-full object-cover">
                    </div>

                    <!-- Course Info -->
                    <div class="p-4 flex flex-col justify-between w-1/2 h-full">
                        <div>
                            <p class="text-xs text-gray-400">1st Sem SY 2024-2025</p>
                            <p class="text-sm text-gray-500">{{ $course->subject }}</p>
                            <h3 class="text-lg font-bold">{{ $course->course_title }}</h3>
                            <p class="text-xs text-gray-400 mt-1">{{ $course->background }}</p>
                        </div>

                        <div class="flex justify-end mt-2" x-data="{ open: false }">
                            @php $isEnrolled = $course->enrollees()->where('users.id', auth()->id())->exists(); @endphp

                            @if($isEnrolled)
                                <a href="{{ route('learner.course.show', $course) }}"
                                   class="bg-black text-white px-4 py-1 rounded-full hover:bg-gray-800">
                                   Start Course
                                </a>
                            @else
                                <button @click="open = true"
                                        class="bg-black text-white px-4 py-1 rounded-full hover:bg-gray-800">
                                    Enroll Class
                                </button>

                                <!-- Modal -->
                                <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                    <div @click.away="open = false" class="bg-white rounded-xl p-6 w-96">
                                        <h3 class="text-lg font-semibold mb-4">Confirm Enrollment</h3>
                                        <p class="text-gray-600 mb-6">
                                            Are you sure you want to enroll in <strong>{{ $course->course_title }}</strong>?
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
                <p class="text-gray-500 col-span-2">No suggested courses available.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
