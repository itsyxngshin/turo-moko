@extends('layouts.layout')

@section('title', 'Assessment Result - ' . $quiz->quiz_title)

@section('content')
<div class="space-y-6">
    <!-- Back button -->
    <a href="{{ route('learner.assessments') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span>Back to Assessments</span>
    </a>

    <!-- Result Summary Card -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">{{ $quiz->quiz_title }}</h1>
            <p class="text-gray-600">{{ $quiz->course->course_title }}</p>
        </div>

        <!-- Score Display -->
        <div class="flex justify-center mb-8">
            <div class="relative w-48 h-48">
                <svg class="transform -rotate-90 w-48 h-48">
                    <circle cx="96" cy="96" r="88" stroke="#e5e7eb" stroke-width="12" fill="none" />
                    <circle 
                        cx="96" 
                        cy="96" 
                        r="88" 
                        stroke="{{ $percentage >= 60 ? '#22c55e' : '#ef4444' }}" 
                        stroke-width="12" 
                        fill="none"
                        stroke-dasharray="{{ 2 * 3.14159 * 88 }}"
                        stroke-dashoffset="{{ 2 * 3.14159 * 88 * (1 - $percentage / 100) }}"
                        stroke-linecap="round"
                    />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <div class="text-4xl font-bold {{ $percentage >= 60 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $percentage }}%
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        {{ $quizResult->score }}/{{ $totalPoints }} points
                    </div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="text-center mb-6">
            @if($quizResult->status === 'Checked')
                @if($percentage >= 60)
                <div class="inline-flex items-center gap-2 px-6 py-3 bg-green-100 text-green-800 rounded-full">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span class="font-semibold">Passed</span>
                </div>
                @else
                <div class="inline-flex items-center gap-2 px-6 py-3 bg-red-100 text-red-800 rounded-full">
                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                    <span class="font-semibold">Failed</span>
                </div>
                @endif
            @else
            <div class="inline-flex items-center gap-2 px-6 py-3 bg-orange-100 text-orange-800 rounded-full">
                <i data-lucide="clock" class="w-5 h-5"></i>
                <span class="font-semibold">Pending Grading</span>
            </div>
            <p class="text-sm text-gray-600 mt-2">Some questions require manual grading by your instructor.</p>
            @endif
        </div>

        <!-- Submission Info -->
        <div class="flex justify-center gap-8 text-sm text-gray-600 border-t pt-6">
            <div class="text-center">
                <div class="font-semibold text-gray-900">Submitted</div>
                <div>{{ $quizResult->created_at->format('M d, Y h:i A') }}</div>
            </div>
            @if($quizResult->status === 'Checked' && $quizResult->checked_at)
            <div class="text-center">
                <div class="font-semibold text-gray-900">Graded</div>
                <div>{{ $quizResult->checked_at->format('M d, Y h:i A') }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Detailed Results -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-8">
        <h2 class="text-2xl font-bold mb-6">Question Review</h2>

        <div class="space-y-6">
            @foreach($questionsWithAnswers as $index => $question)
            <div class="border-b pb-6 last:border-b-0">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-lg font-semibold flex-1">
                        {{ $index + 1 }}. {{ $question['text'] }}
                    </h3>
                    <div class="ml-4">
                        @if($question['is_graded'])
                            @if($question['is_correct'] || $question['points_earned'] > 0)
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                <i data-lucide="check" class="w-4 h-4"></i>
                                {{ $question['points_earned'] }}/{{ $question['points'] }}
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                0/{{ $question['points'] }}
                            </span>
                            @endif
                        @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-semibold">
                            <i data-lucide="clock" class="w-4 h-4"></i>
                            Pending
                        </span>
                        @endif
                    </div>
                </div>

                <div class="ml-6 space-y-2">
                    <div class="flex items-start gap-2">
                        <span class="font-semibold text-gray-700 min-w-[120px]">Your Answer:</span>
                        <span class="text-gray-900">{{ $question['your_answer'] }}</span>
                    </div>

                    @if($question['is_graded'] && !$question['is_correct'] && $question['points_earned'] == 0)
                    <div class="flex items-start gap-2">
                        <span class="font-semibold text-gray-700 min-w-[120px]">Correct Answer:</span>
                        <span class="text-green-700 font-medium">{{ $question['correct_answer'] }}</span>
                    </div>
                    @endif

                    @if($question['model_answer'] && in_array($question['type'], ['short_answer', 'long_answer']))
                    <div class="flex items-start gap-2">
                        <span class="font-semibold text-gray-700 min-w-[120px]">Model Answer:</span>
                        <span class="text-gray-600 italic">{{ $question['model_answer'] }}</span>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-center gap-4">
        <a 
            href="{{ route('learner.assessments') }}" 
            class="px-8 py-3 bg-gray-600 text-white rounded-full hover:bg-gray-700 transition-colors font-medium"
        >
            Back to Assessments
        </a>
        @if($quiz->submission_limit && $quizResult->course_enrollee_id)
            @php
                $submissionCount = \App\Models\QuizResult::where('quiz_id', $quiz->id)
                    ->where('course_enrollee_id', $quizResult->course_enrollee_id)
                    ->count();
                $canRetake = $submissionCount < $quiz->submission_limit;
            @endphp
            @if($canRetake)
            <a 
                href="{{ route('learner.assessment.show', $quiz->id) }}" 
                class="px-8 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-colors font-medium"
            >
                Retake Assessment
            </a>
            @endif
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endsection

