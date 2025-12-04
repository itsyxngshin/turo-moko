@extends('layouts.layout')

@section('title', $quiz->quiz_title)

@section('content')
<style>
    [x-cloak] { display: none !important; }

    /* Hide scrollbar for clean look (from new design) */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div
    class="bg-white min-h-screen text-gray-800 relative"
    x-data="assessmentForm({{ $timerDuration ?? 'null' }}, {{ $questions->count() }})"
>
    {{-- Fixed Header --}}
    <div class="fixed top-0 left-0 right-0 bg-white shadow-sm border-b border-orange-200 z-40">
        <div class="max-w-5xl mx-auto flex items-center justify-between px-4 sm:px-6 py-3">
            <div class="flex items-center gap-4">
                <a href="{{ route('learner.course.show', $quiz->course) }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 text-sm sm:text-base">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                    <span class="hidden sm:inline">Back to Course</span>
                    <span class="sm:hidden">Back</span>
                </a>
                <div>
                    <h1 class="text-lg sm:text-xl font-semibold text-orange-700">Module Assessment</h1>
                    <p class="text-xs text-gray-500 truncate">
                        {{ $quiz->quiz_title }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm">
                <span class="font-medium text-orange-700 bg-orange-50 px-3 py-1.5 rounded-full border border-orange-200">
                    {{ $questions->count() }} {{ \Illuminate\Support\Str::plural('Question', $questions->count()) }}
                </span>
                <span class="hidden sm:inline text-gray-500">
                    Total Points: {{ $totalPoints }}
                </span>
            </div>
        </div>
    </div>

    {{-- Spacer for fixed header --}}
    <div class="h-24"></div>

    {{-- Scrollable Question Navigator --}}
    <div class="w-full overflow-x-auto no-scrollbar border-b border-orange-100 bg-white shadow-sm sticky top-16 z-30">
        <div class="max-w-5xl mx-auto flex space-x-2 py-3 px-4 min-w-max" x-ref="questionBoxesContainer">
            @foreach ($questions as $question)
                <button
                    type="button"
                    class="question-box w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-md border text-xs sm:text-sm font-semibold cursor-pointer transition duration-150"
                    :class="currentQuestion === {{ $loop->index }}
                        ? 'bg-orange-600 text-white border-orange-600'
                        : 'bg-white text-gray-700 border-gray-300 hover:bg-orange-100'"
                    @click="setQuestion({{ $loop->index }})"
                >
                    {{ $loop->iteration }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-4 sm:pt-6 pb-10 space-y-4">
        {{-- Timer (if enabled) --}}
        <div
            x-show="timerEnabled"
            x-cloak
            class="p-4 bg-blue-50 border border-blue-200 rounded-lg"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i data-lucide="clock" class="w-5 h-5 text-blue-600"></i>
                    <span class="font-semibold text-blue-900">Time Remaining:</span>
                </div>
                <div
                    class="text-2xl font-bold"
                    :class="timeRemaining < 300 ? 'text-red-600' : 'text-blue-900'"
                    x-text="formatTime(timeRemaining)"
                ></div>
            </div>
            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                <div
                    class="bg-blue-600 h-2 rounded-full transition-all duration-1000"
                    :style="`width: ${(timeRemaining / totalTime) * 100}%`"
                ></div>
            </div>
        </div>

        {{-- Warning if overdue --}}
        @if($isOverdue)
            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                    <span class="font-semibold text-red-900">
                        This assessment is overdue. Submissions may not be accepted.
                    </span>
                </div>
            </div>
        @endif

        {{-- Course and Points Info --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs sm:text-sm text-gray-600">
            <div class="space-y-1">
                <div><strong>Course:</strong> {{ $quiz->course->course_title }}</div>
                @if($quiz->description)
                    <p class="text-gray-600">
                        {{ $quiz->description }}
                    </p>
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span><strong>Total Points:</strong> {{ $totalPoints }}</span>
                <span><strong>Questions:</strong> {{ $questions->count() }}</span>
                @if($quiz->submission_limit)
                    <span><strong>Attempts:</strong> {{ $submissionCount }}/{{ $quiz->submission_limit }}</span>
                @endif
            </div>
        </div>

        <form
            x-ref="assessmentForm"
            @submit.prevent="submitAssessment($event)"
            class="space-y-6"
        >
            @csrf

            @foreach($questions as $question)
                {{-- Question Card --}}
                <div
                    class="question-card border border-orange-100 bg-orange-50/40 rounded-xl p-5 sm:p-6"
                    x-show="currentQuestion === {{ $loop->index }}"
                    x-cloak
                >
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <h3 class="font-semibold text-gray-800 text-base sm:text-lg">
                            {{ $question['number'] }}. {{ $question['text'] }}
                        </h3>
                        <span class="text-xs sm:text-sm text-gray-500 whitespace-nowrap">
                            ({{ $question['points'] }} {{ $question['points'] == 1 ? 'point' : 'points' }})
                        </span>
                    </div>

                    {{-- Question Types --}}
                    @if($question['type'] === 'multiple_choice')
                        {{-- Multiple Choice --}}
                        <div class="space-y-2 text-gray-700">
                            @foreach($question['choices'] as $choice)
                                <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-orange-100 cursor-pointer hover:bg-orange-50 transition">
                                    <input
                                        type="radio"
                                        name="answer_{{ $question['id'] }}"
                                        value="{{ $choice['id'] }}"
                                        class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-400"
                                    />
                                    <span class="text-sm sm:text-base">{{ $choice['text'] }}</span>
                                </label>
                            @endforeach
                        </div>

                    @elseif($question['type'] === 'true_false')
                        {{-- True/False --}}
                        <div class="space-y-2 text-gray-700">
                            @foreach($question['choices'] as $choice)
                                <label class="flex items-center gap-3 p-3 bg-white rounded-lg border border-orange-100 cursor-pointer hover:bg-orange-50 transition">
                                    <input
                                        type="radio"
                                        name="answer_{{ $question['id'] }}"
                                        value="{{ $choice['id'] }}"
                                        class="w-4 h-4 text-orange-600 border-gray-300 focus:ring-orange-400"
                                    />
                                    <span class="font-semibold uppercase text-xs sm:text-sm">
                                        {{ $choice['text'] }}:
                                    </span>
                                    <span class="ml-1 text-sm sm:text-base">{{ $choice['text'] }}</span>
                                </label>
                            @endforeach
                        </div>

                    @elseif($question['type'] === 'short_answer' || $question['type'] === 'identification')
                        {{-- Short Answer / Identification --}}
                        <input
                            type="text"
                            name="answer_{{ $question['id'] }}"
                            placeholder="Type your answer here"
                            class="w-full border border-orange-100 rounded-lg p-3 bg-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 text-sm sm:text-base"
                        />

                    @elseif($question['type'] === 'long_answer')
                        {{-- Long Answer --}}
                        <textarea
                            name="answer_{{ $question['id'] }}"
                            placeholder="Type your detailed answer here"
                            rows="5"
                            class="w-full border border-orange-100 rounded-lg p-3 bg-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500 resize-none text-sm sm:text-base"
                        ></textarea>
                    @endif
                </div>
            @endforeach

            {{-- Navigation Buttons --}}
            <div class="flex justify-between items-center pt-2">
                <button
                    type="button"
                    id="prevBtn"
                    class="px-4 sm:px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg shadow-sm text-sm sm:text-base transition disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="isFirstQuestion"
                    @click="prevQuestion()"
                >
                    ← Previous
                </button>
                <button
                    type="button"
                    id="nextBtn"
                    class="px-4 sm:px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg shadow-md text-sm sm:text-base transition disabled:opacity-50 disabled:cursor-not-allowed"
                    @click="handleNextClick($event)"
                    x-text="isLastQuestion ? 'Submit →' : 'Next →'"
                    :disabled="submitting"
                >
                    Next →
                </button>
            </div>
        </form>

        {{-- Footer --}}
        <div class="text-center mt-4 text-gray-500 text-xs sm:text-sm">
            © {{ date('Y') }} TURO-MOKO E-Learning Platform — All Rights Reserved.
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('assessmentForm', (timerDuration, totalQuestions = null) => ({
        // Timer
        timerEnabled: timerDuration !== null,
        timeRemaining: timerDuration ? timerDuration * 60 : 0, // seconds
        totalTime: timerDuration ? timerDuration * 60 : 0,
        timerInterval: null,

        // Submission
        submitting: false,

        // Navigation
        currentQuestion: 0,
        questionsCount: totalQuestions || 0,

        get isFirstQuestion() {
            return this.currentQuestion === 0;
        },

        get isLastQuestion() {
            if (this.questionsCount === 0) return true;
            return this.currentQuestion === this.questionsCount - 1;
        },

        init() {
            if (this.timerEnabled && this.timeRemaining > 0) {
                this.startTimer();
            }

            this.scrollToCurrentQuestion();

            // Reinitialize Lucide icons
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        // Timer logic
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
            const form = this.$refs.assessmentForm;
            const event = form ? { target: form } : null;
            await this.submitAssessment(event);
        },

        // Navigation logic
        setQuestion(index) {
            if (index < 0 || index >= this.questionsCount) return;
            this.currentQuestion = index;
            this.scrollToCurrentQuestion();
        },

        nextQuestion() {
            if (this.isLastQuestion) return;
            this.currentQuestion++;
            this.scrollToCurrentQuestion();
        },

        prevQuestion() {
            if (this.isFirstQuestion) return;
            this.currentQuestion--;
            this.scrollToCurrentQuestion();
        },

        handleNextClick(event) {
            if (!this.isLastQuestion) {
                this.nextQuestion();
            } else {
                // On last question, submit the form
                const form = this.$refs.assessmentForm;
                if (form) {
                    const submitEvent = new Event('submit', { bubbles: true, cancelable: true });
                    form.dispatchEvent(submitEvent);
                }
            }
        },

        scrollToCurrentQuestion() {
            this.$nextTick(() => {
                if (!this.$refs.questionBoxesContainer) return;
                const boxes = this.$refs.questionBoxesContainer.querySelectorAll('.question-box');
                const currentBox = boxes[this.currentQuestion];
                if (currentBox && currentBox.scrollIntoView) {
                    currentBox.scrollIntoView({
                        behavior: 'smooth',
                        inline: 'center',
                        block: 'nearest'
                    });
                }
            });
        },

        // Submission logic
        async submitAssessment(event) {
            if (this.submitting) return;

            const form = event && event.target
                ? event.target
                : this.$refs.assessmentForm;

            if (!form) return;

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
