@extends('layouts.layout')

@section('title', 'My Assessments')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">My Assessments</h1>
    </div>

    @if($assessments->isEmpty())
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-12 text-center">
        <i data-lucide="file-text" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">No Assessments Available</h3>
        <p class="text-gray-500">You don't have any assessments at the moment.</p>
    </div>
    @else
    <div class="grid grid-cols-1 gap-4">
        @foreach($assessments as $assessment)
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-bold">{{ $assessment['title'] }}</h3>
                        @if($assessment['is_overdue'])
                        <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">Overdue</span>
                        @elseif($assessment['has_submitted'])
                        <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">Submitted</span>
                        @else
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">Available</span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-600 mb-3">
                        <strong>Course:</strong> {{ $assessment['course_name'] }}
                    </p>

                    @if($assessment['description'])
                    <p class="text-gray-600 mb-3">{{ Str::limit($assessment['description'], 150) }}</p>
                    @endif

                    <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-4">
                        <div class="flex items-center gap-1">
                            <i data-lucide="file-text" class="w-4 h-4"></i>
                            <span>{{ $assessment['question_count'] }} questions</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <i data-lucide="award" class="w-4 h-4"></i>
                            <span>{{ $assessment['total_points'] }} points</span>
                        </div>
                        @if($assessment['timer_hours'] || $assessment['timer_minutes'])
                        <div class="flex items-center gap-1">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            <span>
                                @if($assessment['timer_hours']){{ $assessment['timer_hours'] }}h @endif
                                @if($assessment['timer_minutes']){{ $assessment['timer_minutes'] }}m @endif
                                timer
                            </span>
                        </div>
                        @endif
                        <div class="flex items-center gap-1">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                            <span>Due: {{ \Carbon\Carbon::parse($assessment['end_date'])->format('M d, Y h:i A') }}</span>
                        </div>
                        @if($assessment['submission_limit'])
                        <div class="flex items-center gap-1">
                            <i data-lucide="repeat" class="w-4 h-4"></i>
                            <span>{{ $assessment['submission_count'] }}/{{ $assessment['submission_limit'] }} attempts</span>
                        </div>
                        @endif
                    </div>

                    @if($assessment['has_submitted'])
                    <div class="flex items-center gap-2 text-sm">
                        @if($assessment['status'] === 'Checked')
                        <span class="text-green-600 font-semibold">
                            Score: {{ $assessment['score'] }}/{{ $assessment['total_points'] }}
                            ({{ round(($assessment['score'] / $assessment['total_points']) * 100, 1) }}%)
                        </span>
                        @else
                        <span class="text-orange-600 font-semibold">
                            <i data-lucide="clock" class="w-4 h-4 inline"></i>
                            Pending grading
                        </span>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="flex flex-col gap-2 ml-4">
                    @if($assessment['has_submitted'])
                    <a 
                        href="{{ route('learner.assessment.result', $assessment['id']) }}" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors text-center text-sm font-medium whitespace-nowrap"
                    >
                        View Result
                    </a>
                    @if($assessment['can_submit'])
                    <a 
                        href="{{ route('learner.assessment.show', $assessment['id']) }}" 
                        class="px-6 py-2 bg-gray-600 text-white rounded-full hover:bg-gray-700 transition-colors text-center text-sm font-medium whitespace-nowrap"
                    >
                        Retake
                    </a>
                    @endif
                    @else
                    <a 
                        href="{{ route('learner.assessment.show', $assessment['id']) }}" 
                        class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition-colors text-center text-sm font-medium whitespace-nowrap"
                    >
                        Start Assessment
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endsection

