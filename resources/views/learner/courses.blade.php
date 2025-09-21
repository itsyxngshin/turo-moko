@extends('layouts.layout')

@section('title', 'Courses')

@section('content')
<section class="bg-white rounded-2xl shadow-md p-6 mt-0">
  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <h3 class="font-semibold text-lg">Your Active Courses</h3>
    <button class="text-sm text-gray-500 hover:underline">View all</button>
  </div>

  <!-- Course Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    @forelse($activeCourses as $course)
      <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border">
        <!-- Thumbnail -->
        <div class="w-28 h-full flex items-center justify-center bg-gray-100">
          <img src="{{ $course->background ?? '/images/course1.jpg' }}" 
               alt="{{ $course->name }}" 
               class="object-cover w-full h-full">
        </div>

        <!-- Content -->
        <div class="flex-1 p-5 flex flex-col justify-between">
          <div>
            <div class="flex justify-between items-start mb-2">
              <p class="text-xs text-gray-400">Instructor: {{ $course->instructor ?? 'TBA' }}</p>
              <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">
                {{ $course->semester ?? 'Ongoing' }}
              </span>
            </div>
            <h4 class="font-semibold text-lg mb-1">{{ $course->name }}</h4>
            <p class="text-sm text-gray-500 leading-snug">
              {{ $course->description ?? 'No description available.' }}
            </p>
          </div>

          <!-- Button -->
        <div class="mt-4 flex justify-end">
            <a href="{{ route('learner.courses.show', $course->id) }}"
            class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800">
                View Course
            </a>
        </div>
        </div>
      </div>
    @empty
      <p class="text-gray-500 text-sm">No active courses yet 📚</p>
    @endforelse

  </div>
</section>
@endsection
