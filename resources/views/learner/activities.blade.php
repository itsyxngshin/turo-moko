@extends('layouts.layout')

@section('title', 'Activities')

@section('content')
<div class="space-y-8 px-4 md:px-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold">Pending Activities</h1>
    </div>

    <!-- Activities Grid -->
    <section class="bg-white rounded-2xl shadow-md p-6">
        @if(empty($activities) || count($activities) === 0)
            <p class="text-gray-500 text-center py-10">No pending activities 🎉</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($activities as $activity)
                    <div class="bg-white rounded-2xl shadow-md flex flex-col md:flex-row overflow-hidden border hover:shadow-lg transition">
                        
                        <!-- Icon / Thumbnail -->
                        <div class="flex-shrink-0 flex items-center justify-center h-20 md:w-20 md:h-auto
                          @if($activity->type === 'assignment') bg-orange-100 
                          @elseif($activity->type === 'quiz') bg-blue-100 
                          @else bg-gray-100 @endif">
                            <i data-lucide="{{ $activity->type === 'assignment' ? 'clipboard-list' : 'file-text' }}" 
                               class="w-8 h-8 
                               @if($activity->type === 'assignment') text-orange-500 
                               @elseif($activity->type === 'quiz') text-blue-500 
                               @else text-gray-500 @endif">
                            </i>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 p-5 flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                                    <p class="text-xs text-gray-400">Course: {{ $activity->title ?? 'N/A' }}</p>
                                    <span class="text-xs 
                                        @if(isset($activity->due_date) && \Carbon\Carbon::parse($activity->due_date)->isPast()) bg-red-100 text-red-600 
                                        @else bg-yellow-100 text-yellow-600 @endif 
                                        px-2 py-0.5 rounded-full">
                                        Due: {{ isset($activity->due_date) ? \Carbon\Carbon::parse($activity->due_date)->format('M d') : 'N/A' }}
                                    </span>
                                </div>
                                <h3 class="text-lg font-bold">{{ $activity->title ?? 'Untitled Activity' }}</h3>
                                <p class="text-xs text-gray-400 mt-1">{{ $activity->description ?? 'No description available.' }}</p>
                            </div>

                            <!-- Button -->
                            <div class="flex justify-end mt-4">
                                <button class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800 w-full md:w-auto">
                                    {{ $activity->type === 'quiz' ? 'Start Quiz' : 'Submit' }}
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>
</div>
@endsection
