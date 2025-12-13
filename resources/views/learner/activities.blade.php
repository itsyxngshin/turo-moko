@extends('layouts.learner-layout')

@section('title', 'Activities')

@section('content')
<div class="space-y-4 px-4 md:px-6">

    <!-- Page Header with Back Button -->
    <div class="flex items-center gap-3 mb-6">
        <!-- Back Button (Icon Only) -->
        <button onclick="history.back()"
                class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium p-2 md:p-2.5 rounded-full shadow-sm hover:shadow-md transition-all duration-200">
            <i data-lucide="arrow-left" class="w-4 h-4 md:w-5 md:h-5"></i>
        </button>

        <h1 class="text-2xl font-bold">Pending Assignments</h1>
    </div>

    <!-- Activities Grid -->
    <section class="bg-white rounded-2xl shadow-md p-6">
        @if(empty($activities) || count($activities) === 0)
            <p class="text-gray-500 text-center py-10">No pending assignments 🎉</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($activities as $activity)
                    @php $activity = (object) $activity; @endphp
                    <div class="bg-white rounded-2xl shadow-md flex flex-col md:flex-row overflow-hidden border hover:shadow-lg transition">
                        
                        <!-- Icon / Thumbnail -->
                        <div class="flex-shrink-0 flex items-center justify-center h-20 md:w-20 md:h-auto bg-orange-100">
                            <i data-lucide="clipboard-list" class="w-8 h-8 text-orange-500"></i>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                                    <p class="text-xs text-gray-400">Course: {{ $activity->course_name ?? 'N/A' }}</p>
                                    <span class="text-xs 
                                        @if($activity->due_date && \Carbon\Carbon::parse($activity->due_date)->isPast()) bg-red-100 text-red-600 
                                        @else bg-yellow-100 text-yellow-600 @endif 
                                        px-2 py-0.5 rounded-full">
                                        Due: {{ $activity->due_date ? \Carbon\Carbon::parse($activity->due_date)->format('M d, Y') : 'No due date' }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold">{{ $activity->title ?? 'Untitled Activity' }}</h3>
                                <p class="text-xs text-gray-400 mt-1">{{ $activity->description ?? 'No description available.' }}</p>
                            </div>

                            <!-- Button -->
                            <div class="flex justify-end mt-4">
                                <a href="{{ route('learner.activity.show', ['course' => $activity->course_code, 'assignment' => $activity->id]) }}"
                                   class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800 w-full md:w-auto text-center">
                                    Submit Assignment
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
