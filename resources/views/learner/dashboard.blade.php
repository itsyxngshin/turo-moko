@extends('layouts.layout2')

@section('title', 'Dashboard')
@section('content')
<div class="h-screen overflow-y-auto space-y-8 pl-6">

    <!-- Recently Accessed (Featured Style) -->
    @if($recentCourses->isNotEmpty())
    @php $course = $recentCourses->first(); @endphp
    <div class="relative rounded-2xl overflow-hidden shadow-lg h-60">
        <img src="{{ asset($course->background ?? 'images/banner.jpg') }}" 
             alt="{{ $course->name }}" 
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 h-full flex flex-col justify-center px-8 text-white">
            <p class="text-sm">{{ $course->subject }}</p>
            <h2 class="text-2xl font-bold">{{ $course->name }}</h2>
            <button class="mt-4 bg-white text-black px-4 py-2 rounded-full w-fit hover:bg-gray-200 flex items-center gap-2">
                <i data-lucide="play" class="w-4 h-4"></i> Continue course
            </button>
        </div>
    </div>
    @else
    <p class="text-gray-500">No recently accessed courses.</p>
    @endif

    <!-- Suggested Courses Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 container mx-auto mt-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Course Suggestions</h3>
            <a href="#" class="text-sm text-gray-500 hover:underline">View All</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($suggestedCourses as $course)
            <div class="bg-gray-50 rounded-2xl shadow flex overflow-hidden h-56">
                <div class="w-1/2 h-full">
                    <img src="{{ asset($course->background ?? 'images/banner.jpg') }}" 
                         alt="{{ $course->name }}" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex flex-col justify-between w-1/2 h-full">
                    <div>
                        <p class="text-xs text-gray-400">1st Sem SY 2024-2025</p>
                        <p class="text-sm text-gray-500">{{ $course->subject }}</p>
                        <h3 class="text-lg font-bold">{{ $course->name }}</h3>
                        <p class="text-xs text-gray-400 mt-1">{{ $course->background }}</p>
                    </div>
                    <div class="flex justify-end mt-2">
                        <button class="bg-black text-white px-4 py-1 rounded-full hover:bg-gray-800">
                            Start
                        </button>
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
