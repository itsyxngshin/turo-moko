<div>
@if($showModal)
<div wire:key="course-feedback-modal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 relative overflow-hidden animate-fade-in"
         x-data="{
    currentStep: 1,
    responses: {},
    fadingOut: false,
    isSubmitting: false,
    totalSteps: {{ count($programRatingQuestions) + count($programCommentQuestions) + count($implementerRatingQuestions) + count($implementerCommentQuestions) }},
    programRatingCount: {{ count($programRatingQuestions) }},
    programCommentCount: {{ count($programCommentQuestions) }},
    implementerRatingCount: {{ count($implementerRatingQuestions) }},
    implementerCommentCount: {{ count($implementerCommentQuestions) }},
    
    get progress() {
        return (this.currentStep / this.totalSteps) * 100;
    },
    
    get isCourseSection() {
        return this.currentStep <= (this.programRatingCount + this.programCommentCount);
    },
    
    handleFeedbackSubmitted() {
        if (this.fadingOut) return;
        this.isSubmitting = false;
        
        // Show success message
        this.currentStep = this.totalSteps + 1;

        // Wait 1.5 seconds to show success message, then reload
        setTimeout(() => {
            window.location.reload();
        }, 1500);
    },
    
    reset() {
        this.currentStep = 1;
        this.responses = {};
        this.isSubmitting = false;
    }
}"
:class="{'opacity-0 pointer-events-none scale-95 transition duration-500 ease-in-out': fadingOut}"
x-on:feedback-submitted.window="handleFeedbackSubmitted()">

        <!-- Close -->
        <button @click="$wire.closeModal(); reset()" 
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition text-xl leading-none z-10">
            &times;
        </button>

        <!-- Header -->
        <div class="text-center py-6 border-b border-gray-100">
            <h1 class="text-2xl font-semibold text-gray-800 mb-3"
                x-text="isCourseSection ? 'Course Evaluation' : 'Implementor Evaluation'"></h1>

            <!-- Animated Progress Bar -->
            <div class="w-3/4 mx-auto h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-700 rounded-full shadow-[0_0_6px_rgba(59,130,246,0.6)] transition-all duration-500 ease-in-out"
                     :style="`width: ${progress}%;`"></div>
            </div>
        </div>

        <!-- Steps -->
        <div class="p-8 text-center overflow-hidden min-h-[300px]">
            @php
                $allQuestions = array_merge(
                    $programRatingQuestions,
                    $programCommentQuestions,
                    $implementerRatingQuestions,
                    $implementerCommentQuestions
                );
                $stepIndex = 1;
            @endphp

            @foreach($allQuestions as $question)
                <div x-show="currentStep === {{ $stepIndex }}" 
                     x-transition.opacity.duration.300ms x-cloak 
                     class="space-y-5">

                    <h2 class="text-lg font-medium text-gray-800">{{ $question['text'] }}</h2>

                    @if(in_array($question['type'], ['program_rating', 'implementer_rating']))
                        {{-- Star Rating --}}
                        <p class="text-gray-600">Please select a rating from 1 to 5 stars</p>
                        <div class="flex justify-center gap-3 mt-4">
                            <template x-for="star in 5" :key="'q{{ $question['id'] }}-star-' + star">
                                <button type="button" 
                                        @click="responses[{{ $question['id'] }}] = { rating: star }" 
                                        class="transition-transform hover:scale-110">
                                    <svg viewBox="0 0 24 24" class="w-9 h-9"
                                         :class="star <= (responses[{{ $question['id'] }}]?.rating || 0) ? 'text-yellow-400 fill-current scale-110' : 'text-gray-300 fill-current'">
                                        <path d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                                    </svg>
                                </button>
                            </template>
                        </div>
                        <div class="mt-3 text-sm text-gray-500 font-medium">
                            <span x-show="responses[{{ $question['id'] }}]?.rating === 1">Poor</span>
                            <span x-show="responses[{{ $question['id'] }}]?.rating === 2">Fair</span>
                            <span x-show="responses[{{ $question['id'] }}]?.rating === 3">Good</span>
                            <span x-show="responses[{{ $question['id'] }}]?.rating === 4">Very Good</span>
                            <span x-show="responses[{{ $question['id'] }}]?.rating === 5">Excellent</span>
                        </div>
                    @else
                        {{-- Comment Field --}}
                        <p class="text-gray-600 mb-2">Share your feedback</p>
                        <textarea 
                            :value="responses[{{ $question['id'] }}]?.comment || ''"
                            @input="if (!responses[{{ $question['id'] }}]) responses[{{ $question['id'] }}] = {}; responses[{{ $question['id'] }}].comment = $event.target.value"
                            rows="4"
                            class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-none px-4 py-3"
                            placeholder="Your feedback..."></textarea>
                    @endif
                </div>
                @php $stepIndex++; @endphp
            @endforeach
            
            <!-- Success Step -->
            <div x-show="currentStep === totalSteps + 1" 
                 x-transition.opacity.duration.300ms 
                 class="space-y-5 py-12">
                <div class="text-center">
                    <svg class="w-20 h-20 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">Thank you for your feedback!</h3>
                    <p class="text-gray-600">Your evaluation has been submitted successfully.</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex justify-between items-center border-t border-gray-100 bg-gray-50 px-6 py-4"
             x-show="currentStep <= totalSteps">
            <button @click="if(currentStep > 1) currentStep--" 
                    :disabled="currentStep === 1"
                    :class="currentStep === 1 ? 'opacity-50 cursor-not-allowed' : ''"
                    class="flex items-center gap-1 px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                &larr; Back
            </button>

            <div class="text-sm text-gray-500">
                Step <span x-text="currentStep"></span> of <span x-text="totalSteps"></span>
            </div>

            <div>
                <button x-show="currentStep < totalSteps"
                        @click="if(currentStep < totalSteps) currentStep++"
                        class="flex items-center gap-1 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    Next &rarr;
                </button>

                <button x-show="currentStep === totalSteps"
                        :disabled="isSubmitting"
                        @click="
                            isSubmitting = true;
                            $wire.submitFeedback(responses)
                                .then(() => {
                                    handleFeedbackSubmitted();
                                })
                                .catch(() => {
                                    isSubmitting = false;
                                });
                        "
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition disabled:opacity-60 disabled:cursor-not-allowed">
                    <span x-show="!isSubmitting">Submit</span>
                    <span x-show="isSubmitting">Submitting...</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
</div>
