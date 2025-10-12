<div>
    @if($showModal)
    <div wire:key="course-feedback-modal" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 relative overflow-hidden animate-fade-in"
             x-data="{ currentStep: 1, ratings: {1:0,2:0,3:0,4:0}, comment:'' }"
             x-init="lucide.createIcons()">
            
            <!-- Close Button -->
            <button wire:click="closeModal"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>

            <!-- Header -->
            <div class="text-center mt-6">
                <h1 class="text-2xl font-bold text-gray-800">Course Feedback</h1>
                <p class="text-gray-500 text-sm mt-1">Step <span x-text="currentStep"></span> of 5</p>
            </div>

            <div class="p-8 text-center">
                <!-- Step 1 -->
                <div x-show="currentStep === 1" x-transition>
                    <h2 class="text-xl font-semibold mb-2">How would you rate the overall course?</h2>
                    <p class="text-gray-600 mb-6">Please select a rating from 1 to 5 stars</p>

                    <div class="flex justify-center gap-2 mb-6">
                        <template x-for="star in 5" :key="'step1-' + star">
                            <button type="button" @click="ratings[1] = star" class="transition">
                                <i data-lucide="star" class="w-10 h-10"
                                   :class="star <= ratings[1] ? 'text-yellow-400 fill-current' : 'text-gray-300'"></i>
                            </button>
                        </template>
                    </div>

                    <div class="text-sm text-gray-500">
                        <span x-show="ratings[1] === 1">Poor</span>
                        <span x-show="ratings[1] === 2">Fair</span>
                        <span x-show="ratings[1] === 3">Good</span>
                        <span x-show="ratings[1] === 4">Very Good</span>
                        <span x-show="ratings[1] === 5">Excellent</span>
                    </div>
                </div>

                <!-- Step 2 -->
                <div x-show="currentStep === 2" x-transition>
                    <h2 class="text-xl font-semibold mb-2">How effective was the instructor's teaching?</h2>
                    <p class="text-gray-600 mb-6">Please select a rating from 1 to 5 stars</p>

                    <div class="flex justify-center gap-2 mb-6">
                        <template x-for="star in 5" :key="'step2-' + star">
                            <button type="button" @click="ratings[2] = star" class="transition">
                                <i data-lucide="star" class="w-10 h-10"
                                   :class="star <= ratings[2] ? 'text-yellow-400 fill-current' : 'text-gray-300'"></i>
                            </button>
                        </template>
                    </div>

                    <div class="text-sm text-gray-500">
                        <span x-show="ratings[2] === 1">Poor</span>
                        <span x-show="ratings[2] === 2">Fair</span>
                        <span x-show="ratings[2] === 3">Good</span>
                        <span x-show="ratings[2] === 4">Very Good</span>
                        <span x-show="ratings[2] === 5">Excellent</span>
                    </div>
                </div>

                <!-- Step 3 -->
                <div x-show="currentStep === 3" x-transition>
                    <h2 class="text-xl font-semibold mb-2">How useful were the course materials/resources?</h2>
                    <p class="text-gray-600 mb-6">Please select a rating from 1 to 5 stars</p>

                    <div class="flex justify-center gap-2 mb-6">
                        <template x-for="star in 5" :key="'step3-' + star">
                            <button type="button" @click="ratings[3] = star" class="transition">
                                <i data-lucide="star" class="w-10 h-10"
                                   :class="star <= ratings[3] ? 'text-yellow-400 fill-current' : 'text-gray-300'"></i>
                            </button>
                        </template>
                    </div>

                    <div class="text-sm text-gray-500">
                        <span x-show="ratings[3] === 1">Poor</span>
                        <span x-show="ratings[3] === 2">Fair</span>
                        <span x-show="ratings[3] === 3">Good</span>
                        <span x-show="ratings[3] === 4">Very Good</span>
                        <span x-show="ratings[3] === 5">Excellent</span>
                    </div>
                </div>

                <!-- Step 4 -->
                <div x-show="currentStep === 4" x-transition>
                    <h2 class="text-xl font-semibold mb-2">How likely are you to recommend this course to others?</h2>
                    <p class="text-gray-600 mb-6">Please select a rating from 1 to 5 stars</p>

                    <div class="flex justify-center gap-2 mb-6">
                        <template x-for="star in 5" :key="'step4-' + star">
                            <button type="button" @click="ratings[4] = star" class="transition">
                                <i data-lucide="star" class="w-10 h-10"
                                   :class="star <= ratings[4] ? 'text-yellow-400 fill-current' : 'text-gray-300'"></i>
                            </button>
                        </template>
                    </div>

                    <div class="text-sm text-gray-500">
                        <span x-show="ratings[4] === 1">Very Unlikely</span>
                        <span x-show="ratings[4] === 2">Unlikely</span>
                        <span x-show="ratings[4] === 3">Neutral</span>
                        <span x-show="ratings[4] === 4">Likely</span>
                        <span x-show="ratings[4] === 5">Very Likely</span>
                    </div>
                </div>

                <!-- Step 5 -->
                <div x-show="currentStep === 5" x-transition>
                    <h2 class="text-xl font-semibold mb-2">Additional Comments</h2>
                    <p class="text-gray-600 mb-4">Please share any additional feedback or suggestions</p>

                    <textarea x-model="comment" rows="4" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center border-t p-6 bg-gray-50">
                <button @click="if(currentStep > 1) currentStep--" 
                        class="flex items-center gap-1 px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Back
                </button>

                <button x-show="currentStep < 5" 
                        @click="if(currentStep < 5) currentStep++" 
                        class="flex items-center gap-1 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition">
                    Next <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>

                <button x-show="currentStep === 5"
                        @click="$wire.submitFeedback(ratings, comment)"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    Submit
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
