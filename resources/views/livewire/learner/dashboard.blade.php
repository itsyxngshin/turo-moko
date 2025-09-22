<<<<<<< Updated upstream
@extends('layouts.layout2')  
=======
@extends('layouts.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
>>>>>>> Stashed changes

@section('content')
<div>
    <!-- Banner -->
    @if($courses->isNotEmpty())
        @php $bannerCourse = $courses->first(); @endphp
        <div class="relative h-60 rounded-2xl overflow-hidden shadow mb-8">
            <img src="{{ asset($bannerCourse->banner_image ?? 'images/banner.jpg') }}" 
                 alt="Course Banner" 
                 class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative z-10 h-full flex flex-col justify-center px-8 text-white">
                <p class="text-sm">{{ $bannerCourse->subject }}</p>
                <h2 class="text-2xl font-bold">{{ $bannerCourse->name }}</h2>
                <button class="mt-4 bg-white text-black px-4 py-2 rounded-full w-fit hover:bg-gray-200 flex items-center gap-2">
                    <i data-lucide="play" class="w-4 h-4"></i> Continue course
                </button>
            </div>
        </div>
    @endif

    <!-- Course Preview -->
    <section class="bg-white rounded-2xl shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-semibold text-lg">Course Suggestions</h3>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($courses as $course)
                <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border">
                    <img src="{{ asset($course->cover_image ?? 'images/default.jpg') }}" 
                         alt="Course Image" 
                         class="w-56 h-auto object-cover">
                    <div class="flex-1 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-xs text-gray-400">{{ $course->semester }} - SY {{ $course->school_year }}</p>
                                <button aria-label="More options" class="text-gray-400 hover:text-gray-600">
                                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <h4 class="font-semibold text-lg mb-1">{{ $course->name }}</h4>
                            <p class="text-sm text-gray-500 leading-snug">
                                {{ Str::limit($course->description, 120) }}
                            </p>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800">
                                Enroll Now
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
