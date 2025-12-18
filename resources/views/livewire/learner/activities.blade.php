@extends('layouts.layout')

@section('title', 'Learner Activities')

@section('content')
<div class="space-y-4">

  <!-- Back Button -->
  <div>
    <button onclick="history.back()"
            class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-3 py-1.5 md:px-4 md:py-2 rounded-full shadow-sm hover:shadow-md transition-all duration-200">
        <i data-lucide="arrow-left" class="w-4 h-4 md:w-5 md:h-5"></i>
    </button>
  </div>

  <!-- Main Card -->
  <div class="bg-white rounded-2xl shadow-md p-6 mt-0">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h3 class="font-semibold text-lg">Pending Activities</h3>
    </div>

    <!-- Assignment Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      
      @forelse($assignments as $assignment)
        <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border">
          <!-- Icon / Thumbnail -->
          <div class="w-20 flex items-center justify-center 
            @if($assignment->type === 'assignment') bg-orange-100 
            @elseif($assignment->type === 'quiz') bg-blue-100 
            @else bg-gray-100 @endif">
            <i data-lucide="{{ $assignment->type === 'assignment' ? 'clipboard-list' : 'file-text' }}" 
               class="w-8 h-8 
               @if($assignment->type === 'assignment') text-orange-500 
               @elseif($assignment->type === 'quiz') text-blue-500 
               @else text-gray-500 @endif">
            </i>
          </div>

          <!-- Content -->
          <div class="flex-1 p-5 flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start mb-2">
                <p class="text-xs text-gray-400">Course: {{ $assignment->course_title }}</p>
                @if($assignment->due_date)
                  <span class="text-xs 
                    @if($assignment->due_date->isPast()) bg-red-100 text-red-600 
                    @else bg-yellow-100 text-yellow-600 @endif 
                    px-2 py-0.5 rounded-full">
                    Due: {{ $assignment->due_date->format('M d') }}
                  </span>
                @else
                  <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                    No due date
                  </span>
                @endif
              </div>
              <h4 class="font-semibold text-lg mb-1">{{ $assignment->title }}</h4>
              <p class="text-sm text-gray-500 leading-snug">
                {{ $assignment->description }}
              </p>
            </div>

            <!-- Button -->
            <div class="mt-4 flex justify-end">
              <button class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800">
                {{ $assignment->type === 'quiz' ? 'Start Quiz' : 'Submit' }}
              </button>
            </div>
          </div>
        </div>
      @empty
        <p class="text-gray-500 text-sm">No pending assignments 🎉</p>
      @endforelse

    </div>
  </div>
</div>
@endsection
