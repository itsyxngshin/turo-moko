@extends('layouts.layout2')

@section('title', 'Classes')

@section('content')
<div class="space-y-8 pl-6"> 

    <!-- Top Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

    <!-- Active Courses -->
    <div class="bg-white shadow rounded-2xl p-5 flex items-center gap-4 cursor-pointer hover:bg-gray-50"
        onclick="window.location='{{ route('learner.courses.index') }}'">
        <img src="https://img.icons8.com/fluency/48/000000/books.png" class="w-10 h-10" alt="Active Courses">
        <div>
            <p class="font-semibold text-lg">Active courses</p>
           <p class="text-gray-500 text-sm">{{ $activeCoursesCount }}</p>
        </div>
    </div>

    <!-- Pending Activities -->
   <div class="bg-white shadow rounded-2xl p-5 flex items-center gap-4 cursor-pointer hover:bg-gray-50"
        onclick="window.location='{{ route('learner.activities') }}'">
        <img src="https://img.icons8.com/fluency/48/000000/todo-list.png" class="w-10 h-10" alt="Pending">
        <div>
            <p class="font-semibold text-lg">Pending activities</p>
            <p class="text-gray-500 text-sm">{{ $pendingActivities }}</p>
        </div>
    </div>


    <!-- Evaluation Status -->
   <a href="{{ route('learner.evaluation-status') }}">
    <div class="bg-white shadow rounded-2xl p-5 flex items-center gap-4 hover:bg-gray-50 transition">
        <img src="https://img.icons8.com/fluency/48/000000/survey.png" 
             class="w-10 h-10" alt="Evaluation Status">
        <div>
            <p class="font-semibold text-lg">Evaluation status</p>
            <p class="text-gray-500 text-sm">{{ $pendingEvaluations }} pending</p>
        </div>
    </div>
</a>


    <!-- Completed Courses (moved to last) -->
    <div class="bg-white shadow rounded-2xl p-5 flex items-center gap-4 cursor-pointer hover:bg-gray-50"
        onclick="window.location='{{ route('learner.courses.completed') }}'">
        <img src="https://img.icons8.com/fluency/48/000000/checked.png" class="w-10 h-10" alt="Completed">
        <div>
            <p class="font-semibold text-lg">Completed courses</p>
            <p class="text-gray-500 text-sm">{{ $completedCoursesCount }}</p>
        </div>
    </div>
</div>


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


<!-- Courses Grid -->
<section class="bg-white rounded-2xl shadow-md p-6 mt-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-semibold text-lg">Courses you’re taking</h3>
        <button class="text-sm text-gray-500 hover:underline">View all</button>
    </div>

<!-- Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse ($activeCourses as $course)
        <div class="bg-white rounded-2xl shadow-md flex overflow-hidden border h-52">
            <!-- Course Image -->
            <div class="w-1/2">
                <img src="{{ $course->background ?? '/images/course1.jpg' }}" 
                     alt="{{ $course->name }}" class="w-full h-full object-cover">
            </div>

            <!-- Course Info -->
            <div class="p-4 flex flex-col justify-between w-1/2 h-full">
                <div>
                    <p class="text-xs text-gray-400">
                        {{ $course->semester ?? 'Ongoing' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Instructor: {{ $course->instructor ?? 'TBA' }}
                    </p>
                    <h3 class="text-lg font-bold">{{ $course->name }}</h3>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-3">
                        {{ $course->description ?? 'No description available.' }}
                    </p>
                </div>
                <div class="flex justify-end mt-2">
                    <a href="{{ route('learner.courses.show', $course->id) }}"
                       class="bg-black text-white px-4 py-1.5 rounded-full hover:bg-gray-800">
                        View Course
                    </a>
                </div>
            </div>
        </div>
   @empty
   <div class="col-span-2 text-center text-gray-500 py-10">
       <p class="text-lg font-semibold">No active courses yet 📚</p>
   </div>
@endforelse
</div>
</section>



</div>
@endsection
