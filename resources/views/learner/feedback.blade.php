@extends('layouts.layout')

@section('title', 'Course Feedback')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<main x-data="feedbackWizard">
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm py-10 px-10 ml-4 mt-2">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Course Feedback</h1>
            <div class="text-sm text-gray-600">
                <span x-text="`Step ${currentStep} of 5`"></span>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div 
                    class="bg-blue-600 h-2 rounded-full transition-all duration-300 ease-in-out"
                    :style="`width: ${(currentStep / 5) * 100}%`"
                ></div>
            </div>
        </div>

        <!-- Feedback Form -->
        <form 
            action="{{ route('feedback.submit') }}" 
            method="POST"
            @submit="validateAndSubmit()"
        >
            @csrf

            <!-- Step 1: Overall Course Rating -->
            <div x-show="currentStep === 1" x-transition class="text-center">
                <h2 class="text-2xl font-semibold mb-4">How would you rate the overall course?</h2>
                <p class="text-gray-600 mb-8">Please select a rating from 1 to 5 stars</p>
                
                <div class="flex justify-center gap-2 mb-8">
                    <template x-for="star in 5" :key="star">
                        <button 
                            type="button"
                            @click="ratings[1] = star"
                            class="transition-colors duration-200"
                        >
                            <i 
                                data-lucide="star" 
                                class="w-12 h-12"
                                :class="star <= ratings[1] ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                            ></i>
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

            <!-- Step 2: Instructor Teaching Rating -->
            <div x-show="currentStep === 2" x-transition class="text-center">
                <h2 class="text-2xl font-semibold mb-4">How effective was the instructor's teaching?</h2>
                <p class="text-gray-600 mb-8">Please select a rating from 1 to 5 stars</p>
                
                <div class="flex justify-center gap-2 mb-8">
                    <template x-for="star in 5" :key="star">
                        <button 
                            type="button"
                            @click="ratings[2] = star"
                            class="transition-colors duration-200"
                        >
                            <i 
                                data-lucide="star" 
                                class="w-12 h-12"
                                :class="star <= ratings[2] ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                            ></i>
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

            <!-- Step 3: Course Materials Rating -->
            <div x-show="currentStep === 3" x-transition class="text-center">
                <h2 class="text-2xl font-semibold mb-4">How useful were the course materials/resources?</h2>
                <p class="text-gray-600 mb-8">Please select a rating from 1 to 5 stars</p>
                
                <div class="flex justify-center gap-2 mb-8">
                    <template x-for="star in 5" :key="star">
                        <button 
                            type="button"
                            @click="ratings[3] = star"
                            class="transition-colors duration-200"
                        >
                            <i 
                                data-lucide="star" 
                                class="w-12 h-12"
                                :class="star <= ratings[3] ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                            ></i>
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

            <!-- Step 4: Recommendation Rating -->
            <div x-show="currentStep === 4" x-transition class="text-center">
                <h2 class="text-2xl font-semibold mb-4">How likely are you to recommend this course to others?</h2>
                <p class="text-gray-600 mb-8">Please select a rating from 1 to 5 stars</p>
                
                <div class="flex justify-center gap-2 mb-8">
                    <template x-for="star in 5" :key="star">
                        <button 
                            type="button"
                            @click="ratings[4] = star"
                            class="transition-colors duration-200"
                        >
                            <i 
                                data-lucide="star" 
                                class="w-12 h-12"
                                :class="star <= ratings[4] ? 'text-yellow-400 fill-current' : 'text-gray-300'"
                            ></i>
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

            <!-- Step 5: Written Feedback -->
            <div x-show="currentStep === 5" x-transition>
                <h2 class="text-2xl font-semibold mb-4 text-center">Additional Comments</h2>
                <p class="text-gray-600 mb-8 text-center">Please share any additional feedback or suggestions</p>
                
                <div class="max-w-2xl mx-auto">
                    <textarea 
                        x-model="comment"
                        name="comment"
                        rows="8"
                        class="w-full border rounded-lg p-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                        placeholder="Share your thoughts about the course, instructor, materials, or any suggestions for improvement..."
                    ></textarea>
                    
                    <div class="text-sm text-gray-500 mt-2">
                        <span x-text="comment.length"></span> characters
                    </div>
                </div>
            </div>

            <!-- Hidden inputs for ratings -->
            <input type="hidden" name="overall_rating" :value="ratings[1] || ''">
            <input type="hidden" name="instructor_rating" :value="ratings[2] || ''">
            <input type="hidden" name="materials_rating" :value="ratings[3] || ''">
            <input type="hidden" name="recommendation_rating" :value="ratings[4] || ''">

            <!-- Navigation Buttons -->
            <div class="flex justify-between items-center mt-12">
                <button 
                    type="button"
                    @click="previousStep()"
                    x-show="currentStep > 1"
                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 flex items-center gap-2"
                >
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                    Previous
                </button>
                
                <div x-show="currentStep === 1" class="flex-1"></div>
                
                <button 
                    type="button"
                    @click="nextStep()"
                    x-show="currentStep < 5"
                    :disabled="!canProceed()"
                    :class="canProceed() ? 'px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 flex items-center gap-2' : 'px-6 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed flex items-center gap-2'"
                >
                    Next
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </button>
                
                <button 
                    type="submit"
                    x-show="currentStep === 5"
                    :disabled="!canSubmit()"
                    :class="canSubmit() ? 'px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 flex items-center gap-2' : 'px-8 py-3 bg-gray-300 text-gray-500 rounded-lg cursor-not-allowed flex items-center gap-2'"
                >
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Submit Feedback
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("feedbackWizard", () => ({
            // State management
            currentStep: 1,
            ratings: {
                1: null, // Overall course rating
                2: null, // Instructor teaching rating
                3: null, // Course materials rating
                4: null  // Recommendation rating
            },
            comment: '',

            // Initialize component
            init() {
                this.$nextTick(() => {
                    this.reinitializeIcons();
                });
            },

            // Utility function to reinitialize Lucide icons
            reinitializeIcons() {
                try {
                    this.$nextTick(() => {
                        if (typeof lucide !== 'undefined' && lucide.createIcons) {
                            lucide.createIcons();
                        }
                    });
                } catch (error) {
                    console.warn('Failed to reinitialize icons:', error);
                }
            },

            // Navigation functions
            nextStep() {
                if (this.canProceed() && this.currentStep < 5) {
                    this.currentStep++;
                    this.reinitializeIcons();
                }
            },

            previousStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                    this.reinitializeIcons();
                }
            },

            // Validation functions
            canProceed() {
                if (this.currentStep >= 1 && this.currentStep <= 4) {
                    return this.ratings[this.currentStep] !== null;
                }
                return false;
            },

            canSubmit() {
                // Check if all ratings are provided and comment is not empty
                const allRatingsProvided = Object.values(this.ratings).every(rating => rating !== null);
                const commentProvided = this.comment.trim().length > 0;
                return allRatingsProvided && commentProvided;
            },

            // Form submission
            validateAndSubmit() {
                if (!this.canSubmit()) {
                    event.preventDefault();
                    
                    // Show validation message
                    if (!Object.values(this.ratings).every(rating => rating !== null)) {
                        alert('Please provide ratings for all questions before submitting.');
                    } else if (this.comment.trim().length === 0) {
                        alert('Please provide written feedback before submitting.');
                    }
                    return false;
                }
                
                // Form will submit normally if validation passes
                return true;
            }
        }));
    });
    </script>
</main>
@endsection
