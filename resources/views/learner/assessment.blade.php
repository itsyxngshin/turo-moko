@extends('layouts.layout')

@section('title', $quiz->quiz_title)

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="bg-white border rounded-3xl p-6" x-data="assessmentForm({{ $timerDuration ?? 'null' }})">
    <!-- Timer (if enabled) -->
    <div x-show="timerEnabled" x-cloak class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="clock" class="w-5 h-5 text-blue-600"></i>
                <span class="font-semibold text-blue-900">Time Remaining:</span>
            </div>
            <div class="text-2xl font-bold" :class="timeRemaining < 300 ? 'text-red-600' : 'text-blue-900'" x-text="formatTime(timeRemaining)"></div>
        </div>
        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
            <div class="bg-blue-600 h-2 rounded-full transition-all duration-1000" :style="`width: ${(timeRemaining / totalTime) * 100}%`"></div>
        </div>
    </div>

    <!-- Warning if overdue -->
    @if($isOverdue)
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
            <span class="font-semibold text-red-900">This assessment is overdue. Submissions may not be accepted.</span>
        </div>
    </div>
    @endif

    <!-- Title -->
    <h2 class="text-2xl font-bold mb-2">{{ $quiz->quiz_title }}</h2>
    
    <!-- Course and Points Info -->
    <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
        <span><strong>Course:</strong> {{ $quiz->course->course_title }}</span>
        <span><strong>Total Points:</strong> {{ $totalPoints }}</span>
        <span><strong>Questions:</strong> {{ $questions->count() }}</span>
        @if($quiz->submission_limit)
        <span><strong>Attempts:</strong> {{ $submissionCount }}/{{ $quiz->submission_limit }}</span>
        @endif
    </div>

    <!-- Description/Instructions -->
    @if($quiz->description)
    <p class="text-gray-600 mb-6">
        {{ $quiz->description }}
    </p>
    @endif

    <form @submit.prevent="submitAssessment($event)" class="space-y-6">
        @csrf

        @foreach($questions as $question)
        <!-- Question {{ $question['number'] }} -->
        <div class="border-t pt-6">
            <label class="block text-lg font-semibold mb-2">
                {{ $question['number'] }}. {{ $question['text'] }}
                <span class="text-sm text-gray-500 font-normal">({{ $question['points'] }} {{ $question['points'] == 1 ? 'point' : 'points' }})</span>
            </label>

            @if($question['type'] === 'multiple_choice')
                <!-- Multiple Choice -->
                <div class="space-y-3">
                    @foreach($question['choices'] as $choice)
                    <label class="flex items-center border rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition-colors">
                        <input 
                            type="radio" 
                            name="answer_{{ $question['id'] }}" 
                            value="{{ $choice['id'] }}"
                            class="mr-3 w-4 h-4 text-blue-600"
                            required
                        />
                        <span>{{ $choice['text'] }}</span>
                    </label>
                    @endforeach
                </div>

            @elseif($question['type'] === 'true_false')
                <!-- True/False -->
                <div class="space-y-3">
                    @foreach($question['choices'] as $choice)
                    <label class="flex items-center border rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition-colors">
                        <input 
                            type="radio" 
                            name="answer_{{ $question['id'] }}" 
                            value="{{ $choice['id'] }}"
                            class="mr-3 w-4 h-4 text-blue-600"
                            required
                        />
                        <span class="font-semibold">{{ strtoupper($choice['text']) }}:</span>
                        <span class="ml-2">{{ $choice['text'] }}</span>
                    </label>
                    @endforeach
                </div>

            @elseif($question['type'] === 'short_answer')
                <!-- Short Answer -->
                <input 
                    type="text" 
                    name="answer_{{ $question['id'] }}" 
                    placeholder="Answer"
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    required
                />

            @elseif($question['type'] === 'long_answer')
                <!-- Long Answer -->
                <textarea 
                    name="answer_{{ $question['id'] }}" 
                    placeholder="Your answer"
                    rows="5"
                    class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" 
                    required
                ></textarea>
            @endif
        </div>
        @endforeach

        <!-- Submit button -->
        <div class="flex justify-center pt-6">
            <button 
                type="submit" 
                class="px-8 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="submitting"
                x-text="submitting ? 'Submitting...' : 'Submit'"
            >
                Submit
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('assessmentForm', (timerDuration) => ({
        timerEnabled: timerDuration !== null,
        timeRemaining: timerDuration ? timerDuration * 60 : 0, // convert to seconds
        totalTime: timerDuration ? timerDuration * 60 : 0,
        timerInterval: null,
        submitting: false,

        init() {
            if (this.timerEnabled && this.timeRemaining > 0) {
                this.startTimer();
            }

            // Reinitialize Lucide icons
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        startTimer() {
            this.timerInterval = setInterval(() => {
                this.timeRemaining--;

                if (this.timeRemaining <= 0) {
                    clearInterval(this.timerInterval);
                    this.autoSubmit();
                }
            }, 1000);
        },

        formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;

            if (hours > 0) {
                return `${hours}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
            }
            return `${minutes}:${String(secs).padStart(2, '0')}`;
        },

        async autoSubmit() {
            alert('Time is up! Your assessment will be submitted automatically.');
            await this.submitAssessment();
        },

        async submitAssessment(event) {
            if (this.submitting) return;

            // Confirm submission
            if (!confirm('Are you sure you want to submit your assessment? You cannot change your answers after submission.')) {
                return;
            }

            this.submitting = true;

            // Stop timer if running
            if (this.timerInterval) {
                clearInterval(this.timerInterval);
            }

            try {
                // Get the form element - use event.target since this is called from form submit
                const form = event.target;
                const formData = new FormData(form);
                
                const response = await fetch('{{ route("learner.assessment.submit", $quiz->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert(data.message);
                    window.location.href = data.redirect_url;
                } else {
                    alert('Error: ' + data.message);
                    this.submitting = false;
                }
            } catch (error) {
                console.error('Submission error:', error);
                alert('An error occurred while submitting your assessment. Please try again.');
                this.submitting = false;
            }
        }
    }));
});
</script>
@endsection
