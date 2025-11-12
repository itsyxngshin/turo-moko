<div>
@if($showModal)
<div wire:key="course-feedback-modal"
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 relative overflow-hidden animate-fade-in"
         x-data="{
    currentStep: 1,
    courseRatings: {1:0,2:0,3:0,4:0},
    implementorRatings: {6:0,7:0,8:0,9:0},
    courseComment:'',
    implementorComment:'',
    totalSteps: 10,
    fadingOut: false,
    get progress() {
        return (this.currentStep / this.totalSteps) * 100;
    },
    init() {
        lucide.createIcons();

        this.$watch('$wire.feedbackSubmitted', value => {
            if (value) {
                // Wait 2 seconds to show thank-you message
                setTimeout(() => {
                    this.fadingOut = true;
                    // After fade-out animation, close and reset
                    setTimeout(() => {
                        this.reset();
                        this.$wire.closeModal();
                        this.fadingOut = false;
                    }, 500); // fade-out duration (0.5s)
                }, 2000); // keep thank-you visible for 2s
            }
        });
    },
    reset() {
        this.currentStep = 1;
        this.courseRatings = {1:0,2:0,3:0,4:0};
        this.implementorRatings = {6:0,7:0,8:0,9:0};
        this.courseComment = '';
        this.implementorComment = '';
    }
}"
:class="{'opacity-0 pointer-events-none scale-95 transition duration-500 ease-in-out': fadingOut}"

         x-init="
            lucide.createIcons();
        ">

        <!-- Close -->
        <button @click="$wire.closeModal(); reset()" 
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition">
            <i data-lucide='x' class='w-5 h-5'></i>
        </button>

        <!-- Header -->
        <div class="text-center py-6 border-b border-gray-100">
            <h1 class="text-2xl font-semibold text-gray-800 mb-3"
                x-text="currentStep <= 5 ? 'Course Feedback' : 'Implementor Feedback'"></h1>

            <!-- Animated Progress Bar -->
            <div class="w-3/4 mx-auto h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-blue-500 to-blue-700 rounded-full shadow-[0_0_6px_rgba(59,130,246,0.6)] transition-all duration-500 ease-in-out"
                     :style="`width: ${progress}%;`"></div>
            </div>
        </div>

        <!-- Steps -->
        <div class="p-8 text-center overflow-hidden">
            @foreach([1,2,3,4,5,6,7,8,9,10] as $step)
                <div x-show="currentStep === {{ $step }}" 
                     x-transition.opacity.duration.300ms x-cloak 
                     class="space-y-5">

                    {{-- Step Titles --}}
                    @if($step === 1)
                        <h2 class="text-lg font-medium text-gray-800">How would you rate the overall course quality?</h2>
                    @elseif($step === 2)
                        <h2 class="text-lg font-medium text-gray-800">How useful were the course materials and resources?</h2>
                    @elseif($step === 3)
                        <h2 class="text-lg font-medium text-gray-800">How well-organized was the course structure?</h2>
                    @elseif($step === 4)
                        <h2 class="text-lg font-medium text-gray-800">How engaging was the course content?</h2>
                    @elseif($step === 5)
                        <h2 class="text-lg font-medium text-gray-800">Additional comments about the course</h2>
                    @elseif($step === 6)
                        <h2 class="text-lg font-medium text-gray-800">How would you rate the implementor's teaching effectiveness?</h2>
                    @elseif($step === 7)
                        <h2 class="text-lg font-medium text-gray-800">How responsive was the implementor to questions and concerns?</h2>
                    @elseif($step === 8)
                        <h2 class="text-lg font-medium text-gray-800">How clear were the implementor's explanations and instructions?</h2>
                    @elseif($step === 9)
                        <h2 class="text-lg font-medium text-gray-800">How likely are you to take another course from this implementor?</h2>
                    @elseif($step === 10)
                        <h2 class="text-lg font-medium text-gray-800">Additional feedback for the implementor</h2>
                    @endif

                    {{-- Rating Steps --}}
                    @if($step <= 4)
                        <p class="text-gray-600">Please select a rating from 1 to 5 stars</p>
                        <div class="flex justify-center gap-3 mt-4">
                            <template x-for="star in 5" :key="'course-step{{ $step }}-' + star">
                                <button type="button" 
                                        @click="courseRatings[{{ $step }}] = star" 
                                        class="transition-transform hover:scale-110">
                                    <i data-lucide='star' class='w-9 h-9'
                                       :class="star <= courseRatings[{{ $step }}] ? 'text-yellow-400 fill-current scale-110' : 'text-gray-300'"></i>
                                </button>
                            </template>
                        </div>
                        <div class="mt-3 text-sm text-gray-500 font-medium">
                            <span x-show="courseRatings[{{ $step }}] === 1">Poor</span>
                            <span x-show="courseRatings[{{ $step }}] === 2">Fair</span>
                            <span x-show="courseRatings[{{ $step }}] === 3">Good</span>
                            <span x-show="courseRatings[{{ $step }}] === 4">Very Good</span>
                            <span x-show="courseRatings[{{ $step }}] === 5">Excellent</span>
                        </div>

                    @elseif($step === 5)
                        <p class="text-gray-600 mb-2">Share any additional feedback about the course</p>
                        <textarea x-model="courseComment" rows="4"
                                  class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-none"
                                  placeholder="Your thoughts about the course..."></textarea>

                    @elseif($step >= 6 && $step <= 9)
                        <p class="text-gray-600">Please select a rating from 1 to 5 stars</p>
                        <div class="flex justify-center gap-3 mt-4">
                            <template x-for="star in 5" :key="'implementor-step{{ $step }}-' + star">
                                <button type="button" 
                                        @click="implementorRatings[{{ $step }}] = star" 
                                        class="transition-transform hover:scale-110">
                                    <i data-lucide='star' class='w-9 h-9'
                                       :class="star <= implementorRatings[{{ $step }}] ? 'text-yellow-400 fill-current scale-110' : 'text-gray-300'"></i>
                                </button>
                            </template>
                        </div>
                        <div class="mt-3 text-sm text-gray-500 font-medium">
                            <span x-show="implementorRatings[{{ $step }}] === 1">Poor</span>
                            <span x-show="implementorRatings[{{ $step }}] === 2">Fair</span>
                            <span x-show="implementorRatings[{{ $step }}] === 3">Good</span>
                            <span x-show="implementorRatings[{{ $step }}] === 4">Very Good</span>
                            <span x-show="implementorRatings[{{ $step }}] === 5">Excellent</span>
                        </div>

                    @elseif($step === 10)
                        <p class="text-gray-600 mb-2">Share any additional feedback for the implementor</p>
                        <textarea x-model="implementorComment" rows="4"
                                  class="w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 resize-none"
                                  placeholder="Your thoughts about the implementor..."></textarea>

                        <div class="mt-4 text-green-600 font-medium"
     x-show="$wire.feedbackSubmitted"
     x-transition.opacity.duration.500ms>
     Thank you for your feedback!
</div>

                    @endif
                </div>
            @endforeach
        </div>

        <!-- Footer -->
        <div class="flex justify-between items-center border-t border-gray-100 bg-gray-50 px-6 py-4">
            <button @click="if(currentStep > 1) currentStep--" 
                    class="flex items-center gap-1 px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                <i data-lucide='arrow-left' class='w-4 h-4'></i> Back
            </button>

            <div>
                <button x-show="currentStep < 10"
                        @click="if(currentStep < 10) currentStep++"
                        class="flex items-center gap-1 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    Next <i data-lucide='arrow-right' class='w-4 h-4'></i>
                </button>

                <button x-show="currentStep === 10"
                        @click="$wire.submitFeedback(courseRatings, implementorRatings, courseComment, implementorComment)"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    Submit
                </button>
            </div>
        </div>
    </div>
</div>
@endif
</div>
