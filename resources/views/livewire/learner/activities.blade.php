@section('title', 'Learner Activities')

@section('content')
<div class="bg-white rounded-2xl shadow-md p-6 mt-0">
  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <h3 class="font-semibold text-lg">Pending Activities</h3>
  </div>

  <!-- Activity Cards -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    @forelse($activities as $activity)
      <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border">
        <!-- Icon / Thumbnail -->
        <div class="w-20 flex items-center justify-center 
          @if($activity->type === 'assignment') bg-orange-100 @elseif($activity->type === 'quiz') bg-blue-100 @else bg-gray-100 @endif">
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
            <div class="flex justify-between items-start mb-2">
              <p class="text-xs text-gray-400">Course: {{ $activity->course_name }}</p>
              <span class="text-xs 
                @if($activity->due_date->isPast()) bg-red-100 text-red-600 
                @else bg-yellow-100 text-yellow-600 @endif 
                px-2 py-0.5 rounded-full">
                Due: {{ $activity->due_date->format('M d') }}
              </span>
            </div>
            <h4 class="font-semibold text-lg mb-1">{{ $activity->title }}</h4>
            <p class="text-sm text-gray-500 leading-snug">
              {{ $activity->description }}
            </p>
          </div>

          <!-- Button -->
          <div class="mt-4 flex justify-end">
            <button class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800">
              {{ $activity->type === 'quiz' ? 'Start Quiz' : 'Submit' }}
            </button>
          </div>
        </div>
      </div>
    @empty
      <p class="text-gray-500 text-sm">No pending activities 🎉</p>
    @endforelse

  </div>
</div>
@endsection