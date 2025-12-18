@extends('layouts.layout')

@section('title', 'Assessment Builder')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    [x-cloak] { display: none !important; }
</style>

<main x-data="assessmentBuilder">
    <!-- Back to Course Button -->
    @php
        $course = null;
        if ($courseId = request('course_id')) {
            $course = \App\Models\Course::find($courseId);
        } elseif ($quiz && $quiz->course_id) {
            $course = \App\Models\Course::find($quiz->course_id);
        }
    @endphp
    @if($course)
        <div class="ml-4 mt-2 mb-2">
            <a href="{{ route('implementor.course-information', $course->course_code) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Course
            </a>
        </div>
    @endif

    <!-- Assessment Builder -->
    <div x-show="!isPreviewMode">
    <form
            action="{{ $quiz ? route('implementor.assessment-builder.update', $quiz->id) : route('implementor.assessment-builder.store') }}"
            method="POST"
        class="bg-white rounded-3xl border border-gray-200 shadow-sm py-10 px-10 ml-4 mt-2 flex flex-col gap-8"
            @submit.prevent="submitForm($event)"
    >
            @csrf
            {{-- Debug: quiz exists = {{ $quiz ? 'yes' : 'no' }} --}}
            @if($quiz)
                <input type="hidden" name="_method" value="PUT" id="method-field">
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}" id="quiz-id-field">
                <input type="hidden" name="is_editing" value="1" id="is-editing-field">
            @endif

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">{{ $quiz ? 'Edit Assessment' : 'Assessment Details' }}</h2>
            
            @if($quiz)
            <button 
                type="button"
                @click="confirmDelete()"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center gap-2 transition-colors"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                    <line x1="10" y1="11" x2="10" y2="17"/>
                    <line x1="14" y1="11" x2="14" y2="17"/>
                </svg>
                Delete Assessment
            </button>
            @endif
        </div>

        <div class="space-y-6">
        <div class="flex flex-col">
                <label class="font-semibold text-sm mb-2">Course Name</label>
                @php
                    $currentCourse = null;
                    if ($quiz) {
                        $currentCourse = $quiz->course;
                    } else {
                        $courseId = request()->get('course_id');
                        $currentCourse = $courseId ? \App\Models\Course::find($courseId) : null;
                    }
                @endphp
                
                @if($currentCourse)
                    <!-- Display course name as read-only text -->
                    <div class="block border rounded-lg p-3 bg-gray-50 text-gray-700">
                        {{ $currentCourse->course_title }}
                    </div>
                    
                    <!-- Hidden input to preserve course_id (always present) -->
                    <input type="hidden" name="course_id" value="{{ $currentCourse->id }}" id="course-id-input">
                @else
                    <div class="block border rounded-lg p-3 bg-gray-50 text-gray-500">
                        No course selected
                    </div>
                @endif
        </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="flex flex-col">
                    <label class="font-semibold text-sm mb-2">Type</label>
                    <select x-model="assessment.type" name="type" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select type</option>
                    <option value="quiz">Quiz</option>
                    <option value="exam">Exam</option>
                </select>
            </div>

                <div class="flex flex-col">
                    <label class="font-semibold text-sm mb-2">Schedule Closing</label>
                    <input x-model="assessment.closing_schedule" type="datetime-local" name="closing_schedule" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="flex flex-col">
                <label class="font-semibold text-sm mb-2">Timer (optional)</label>
                <div class="flex gap-2">
                    <input x-model="assessment.timer_hours" type="number" name="timer_hours" placeholder="Hours" min="0" max="23" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex-1">
                    <input x-model="assessment.timer_minutes" type="number" name="timer_minutes" placeholder="Minutes" min="0" max="59" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 flex-1">
                </div>
            </div>

            <div class="flex flex-col">
                <label class="font-semibold text-sm mb-2">Number of submissions allowed</label>
                <input x-model="assessment.submission_limit" type="number" name="submission_limit" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>

        <div class="flex flex-col">
                <label class="font-semibold text-sm mb-2">Description</label>
                <textarea x-model="assessment.description" name="description" rows="3" class="border rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none" placeholder="Enter assessment description..."></textarea>
            </div>
        </div>

        <hr class="border-t-2 border-gray-300 w-full mb-4">

        <input
            type="text"
            name="title"
            value="Assessment Form Title"
            class="text-3xl font-bold text-center"
            x-model="formTitle"
        />

        <!-- Dynamic Assessment Items -->
        <div x-ref="itemsContainer" class="space-y-6">
            <template x-for="(item, index) in items" :key="item.id">
                <div class="flex items-start gap-8 p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200" :data-id="item.id">
                    <div class="flex flex-col gap-2 w-15 flex-shrink-0">
                        <div class="flex gap-2">
                            <button type="button" @click="removeItem(index)"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
                            <button type="button" @click="addItemAfter(index)"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
                            <button type="button" class="drag-handle cursor-move" draggable="true"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <label class="text-xs text-gray-500">Points</label>
                            <input 
                                type="number" 
                                x-model="item.points" 
                                class="w-16 border rounded px-2 py-1 text-sm text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                min="1" 
                                value="1"
                            />
                        </div>
                    </div>
                    <div class="flex-1">
                        <template x-if="item.type === 'multiple_choice'">
                            <div>
                                <input type="text" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText"  />
                                
                                <!-- Image Upload Section -->
                                <div class="mb-4">
                                    <template x-if="!item.imageData">
                                        <div>
                                            <input 
                                                type="file" 
                                                :id="'image-upload-' + item.id" 
                                                accept="image/jpeg,image/png,image/gif" 
                                                class="hidden"
                                                @change="handleImageUpload($event, item)"
                                            />
                                            <button 
                                                type="button" 
                                                @click="document.getElementById('image-upload-' + item.id).click()" 
                                                class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
                                            >
                                                <i data-lucide="image" class="w-4 h-4"></i>
                                                Add Image
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="item.imageData">
                                        <div class="relative inline-block">
                                            <img :src="item.imageData" :alt="item.imageName" class="max-w-md max-h-64 rounded-lg border border-gray-300" />
                                            <button 
                                                type="button" 
                                                @click="item.imageData = null; item.imageName = null; reinitializeIcons()" 
                                                class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700"
                                            >
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                            <p class="text-xs text-gray-500 mt-1" x-text="item.imageName"></p>
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="space-y-3">
                                    <template x-for="(option, optionIndex) in item.options" :key="optionIndex">
                                        <div class="flex items-center gap-2">
                                            <label class="flex-1 block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                                <input type="radio" :name="'mc-' + item.id" :value="optionIndex" class="hidden peer" x-model="item.correctAnswer" />
                                                <span class="peer-checked:font-semibold peer-checked:text-orange-600">
                                                    <b x-text="String.fromCharCode(65 + optionIndex) + '.'"></b> 
                                                    <input type="text" :placeholder="'Option ' + String.fromCharCode(65 + optionIndex)" class="border-none outline-none bg-transparent min-w-[150px] max-w-full" x-model="item.options[optionIndex]"  />
                                                </span>
                                            </label>
                                            <button type="button" @click="item.options.splice(optionIndex, 1)" class="text-red-600 hover:text-red-800 px-2" x-show="item.options.length > 1">×</button>
                                        </div>
                                    </template>
                                    <button type="button" @click="item.options.push('')" class="text-orange-600 hover:text-orange-800 text-sm">+ Add option</button>
                                </div>
                            </div>
                        </template>
                        <template x-if="item.type === 'true_false'">
                            <div>
                                <input type="text" placeholder="Enter statement..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText"  />
                                
                                <!-- Image Upload Section -->
                                <div class="mb-4">
                                    <template x-if="!item.imageData">
                                        <div>
                                            <input 
                                                type="file" 
                                                :id="'image-upload-' + item.id" 
                                                accept="image/jpeg,image/png,image/gif" 
                                                class="hidden"
                                                @change="handleImageUpload($event, item)"
                                            />
                                            <button 
                                                type="button" 
                                                @click="document.getElementById('image-upload-' + item.id).click()" 
                                                class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
                                            >
                                                <i data-lucide="image" class="w-4 h-4"></i>
                                                Add Image
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="item.imageData">
                                        <div class="relative inline-block">
                                            <img :src="item.imageData" :alt="item.imageName" class="max-w-md max-h-64 rounded-lg border border-gray-300" />
                                            <button 
                                                type="button" 
                                                @click="item.imageData = null; item.imageName = null; reinitializeIcons()" 
                                                class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700"
                                            >
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                            <p class="text-xs text-gray-500 mt-1" x-text="item.imageName"></p>
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="space-y-3">
                                    <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                        <input type="radio" :name="'tf-' + item.id" value="true" class="hidden peer" x-model="item.correctAnswer" />
                                        <span class="peer-checked:font-semibold peer-checked:text-orange-600">
                                            <b>TRUE:</b> <input type="text" placeholder="True statement" class="border-none outline-none bg-transparent min-w-[150px] max-w-full" x-model="item.trueText"  />
                                        </span>
                                    </label>
                                    <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                        <input type="radio" :name="'tf-' + item.id" value="false" class="hidden peer" x-model="item.correctAnswer" />
                                        <span class="peer-checked:font-semibold peer-checked:text-orange-600">
                                            <b>FALSE:</b> <input type="text" placeholder="False statement" class="border-none outline-none bg-transparent min-w-[150px] max-w-full" x-model="item.falseText"  />
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </template>
                        <template x-if="item.type === 'short_answer'">
                            <div>
                                <input type="text" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText"  />
                                
                                <!-- Image Upload Section -->
                                <div class="mb-4">
                                    <template x-if="!item.imageData">
                                        <div>
                                            <input 
                                                type="file" 
                                                :id="'image-upload-' + item.id" 
                                                accept="image/jpeg,image/png,image/gif" 
                                                class="hidden"
                                                @change="handleImageUpload($event, item)"
                                            />
                                            <button 
                                                type="button" 
                                                @click="document.getElementById('image-upload-' + item.id).click()" 
                                                class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
                                            >
                                                <i data-lucide="image" class="w-4 h-4"></i>
                                                Add Image
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="item.imageData">
                                        <div class="relative inline-block">
                                            <img :src="item.imageData" :alt="item.imageName" class="max-w-md max-h-64 rounded-lg border border-gray-300" />
                                            <button 
                                                type="button" 
                                                @click="item.imageData = null; item.imageName = null; reinitializeIcons()" 
                                                class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700"
                                            >
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                            <p class="text-xs text-gray-500 mt-1" x-text="item.imageName"></p>
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="space-y-2">
                                    <template x-for="(answer, answerIndex) in item.acceptedAnswers" :key="answerIndex">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="text" 
                                                :placeholder="'Accepted answer ' + (answerIndex + 1)" 
                                                class="flex-1 border rounded-lg p-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                x-model="item.acceptedAnswers[answerIndex]" 
                                            />
                                            <button type="button" @click="item.acceptedAnswers.splice(answerIndex, 1)" class="text-red-600 hover:text-red-800 px-2" x-show="item.acceptedAnswers.length > 1">×</button>
                                        </div>
                                    </template>
                                    <button type="button" @click="item.acceptedAnswers.push('')" class="text-orange-600 hover:text-orange-800 text-sm">+ Add another answer</button>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    All answer variations will be accepted (case-insensitive matching)
                                </p>
                            </div>
                        </template>
                        <template x-if="item.type === 'long_answer'">
                            <div>
                                <input type="text" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText"  />
                                
                                <!-- Image Upload Section -->
                                <div class="mb-4">
                                    <template x-if="!item.imageData">
                                        <div>
                                            <input 
                                                type="file" 
                                                :id="'image-upload-' + item.id" 
                                                accept="image/jpeg,image/png,image/gif" 
                                                class="hidden"
                                                @change="handleImageUpload($event, item)"
                                            />
                                            <button 
                                                type="button" 
                                                @click="document.getElementById('image-upload-' + item.id).click()" 
                                                class="flex items-center gap-2 px-3 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
                                            >
                                                <i data-lucide="image" class="w-4 h-4"></i>
                                                Add Image
                                            </button>
                                        </div>
                                    </template>
                                    <template x-if="item.imageData">
                                        <div class="relative inline-block">
                                            <img :src="item.imageData" :alt="item.imageName" class="max-w-md max-h-64 rounded-lg border border-gray-300" />
                                            <button 
                                                type="button" 
                                                @click="item.imageData = null; item.imageName = null; reinitializeIcons()" 
                                                class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700"
                                            >
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                            <p class="text-xs text-gray-500 mt-1" x-text="item.imageName"></p>
                                        </div>
                                    </template>
                                </div>
                                
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Model Answer</label>
                                    <textarea 
                                        placeholder="Enter the model answer (optional)" 
                                        class="w-full border rounded-lg p-3 min-h-[80px]" 
                                        rows="3" 
                                        x-model="item.modelAnswer"
                                    ></textarea>
                                </div>
                            </div>
                        </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Add First Item Button (shows when no items) -->
        <div x-show="items.length === 0" class="text-center py-12">
            <button 
                type="button" 
                @click="openAddItemModal()"
                class="px-8 py-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-gray-400 hover:text-gray-800 transition-colors"
            >
                <i data-lucide="plus" class="w-6 h-6 inline mr-2"></i>
                Add your first question
            </button>
        </div>

        <div class="flex gap-4 mt-6 justify-center">
            <button
                type="submit"
                name="action"
                value="save"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg"
            >
                Save
            </button>
            <button
                type="button"
                @click="switchToPreview()"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg"
            >
                Preview
            </button>
            <button
                type="submit"
                name="action"
                value="publish"
                class="px-4 py-2 bg-black text-white rounded-lg"
            >
                Publish
            </button>
        </div>
    </form>
    </div>

    <!-- Add Item Modal -->
    <div x-show="addItemModalOpen" x-transition x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">Add anything</h2>
                <button type="button" @click="closeAddItemModal()"><i data-lucide="x" class="w-6 h-6 text-gray-600"></i></button>
            </div>

            <hr class="border-t-2 border-gray-300 w-full mb-4">

            <div class="flex gap-2">
                <button @click="addNewItem('multiple_choice')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                    <i data-lucide="circle-dot" class="w-6 h-6 mb-2"></i>
                    <span class="text-xs text-center">Multiple Choice</span>
                </button>
                <button @click="addNewItem('true_false')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                    <i data-lucide="toggle-right" class="w-6 h-6 mb-2"></i>
                    <span class="text-xs text-center">True/False</span>
                </button>
                <button @click="addNewItem('short_answer')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                    <i data-lucide="minus" class="w-6 h-6 mb-2"></i>
                    <span class="text-xs text-center">Short Answer</span>
                </button>
                <button @click="addNewItem('long_answer')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                    <i data-lucide="text-align-justify" class="w-6 h-6 mb-2"></i>
                    <span class="text-xs text-center px-1">Long Answer</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Assessment Preview -->
    <div x-show="isPreviewMode" class="bg-white rounded-3xl border border-gray-200 shadow-sm py-10 px-10 ml-4 mt-2 flex flex-col gap-8">
        <div class="flex justify-between items-center mb-6">
            <div class="flex-1"></div>
            <h1 class="text-3xl font-bold text-center flex-1" x-text="formTitle"></h1>
            <div class="flex-1 flex justify-end">
                <button 
                    type="button" 
                    @click="isPreviewMode = false; reinitializeIcons()"
                    class="px-4 py-2 bg-gray-600 text-white rounded-lg"
                >
                    Back to Builder
                </button>
            </div>
        </div>

        <!-- Questions Preview -->
        <div class="space-y-8">
            <template x-for="(item, index) in items" :key="item.id">
                <div class="preview-item">
                    <div x-html="getPreviewContent(item)"></div>
                </div>
            </template>
            
            <!-- No questions message -->
            <div x-show="items.length === 0" class="text-center py-8 text-gray-500">
                <p>No questions added yet. Go back to builder to add questions.</p>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("assessmentBuilder", () => ({
            // State management - properly initialized
            addItemModalOpen: false,
            insertAfterIndex: null,
            isPreviewMode: false,
            formTitle: '{{ $quiz->quiz_title ?? "Assessment Form Title" }}',
            
            // Assessment details state
            assessment: {
                course_id: '{{ $quiz->course_id ?? request()->get("course_id") ?? "" }}',
                type: '{{ $quiz ? "quiz" : "" }}',
                description: '{{ $quiz->description ?? "" }}',
                timer_hours: '{{ $quiz->timer_hours ?? "" }}',
                timer_minutes: '{{ $quiz->timer_minutes ?? "" }}',
                submission_limit: '{{ $quiz->submission_limit ?? "" }}',
                closing_schedule: '{{ $quiz && $quiz->end_date ? \Carbon\Carbon::parse($quiz->end_date)->format("Y-m-d\TH:i") : "" }}'
            },
            
            // Courses array - will be populated from database
            courses: [
                { id: 'course1', name: 'Course 1' },
                { id: 'course2', name: 'Course 2' }
                // TODO: Replace with dynamic data from database
            ],
            
            // Start with empty items array - users add from scratch (or load existing)
            items: @json($quizItems ?? []),

            // Initialize component
            init() {
                this.$nextTick(() => {
                    this.reinitializeIcons();
                    this.initDragAndDrop();
                });
            },

            // ===== UTILITY FUNCTIONS =====
            
            /**
             * Safely reinitialize Lucide icons
             */
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

            /**
             * Safely query DOM elements with error handling
             */
            safeQuerySelector(element, selector) {
                try {
                    return element?.querySelector(selector);
                } catch (error) {
                    console.warn('DOM query failed:', error);
                    return null;
                }
            },

            /**
             * Safely query multiple DOM elements
             */
            safeQuerySelectorAll(element, selector) {
                try {
                    return element ? Array.from(element.querySelectorAll(selector)) : [];
                } catch (error) {
                    console.warn('DOM query all failed:', error);
                    return [];
                }
            },

            /**
             * Generate unique ID for items
             */
            generateUniqueId() {
                return Date.now() + Math.random();
            },

            // ===== MODAL MANAGEMENT =====
            
            openAddItemModal() {
                this.addItemModalOpen = true;
                this.insertAfterIndex = null;
                this.reinitializeIcons();
            },

            closeAddItemModal() {
                this.addItemModalOpen = false;
                this.insertAfterIndex = null;
            },

            // ===== ITEM MANAGEMENT =====
            
            handleImageUpload(event, item) {
                const file = event.target.files[0];
                if (!file) return;
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Please upload a valid image file (JPG, PNG, or GIF)');
                    return;
                }
                
                // Validate file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('Image size must be less than 5MB');
                    return;
                }
                
                // Convert to base64
                const reader = new FileReader();
                reader.onload = (e) => {
                    item.imageData = e.target.result;
                    item.imageName = file.name;
                    this.reinitializeIcons();
                };
                reader.readAsDataURL(file);
            },
            
            removeItem(index) {
                if (index >= 0 && index < this.items.length) {
                    this.items.splice(index, 1);
                    this.reinitializeIcons();
                }
            },

            addItemAfter(index) {
                if (index >= 0 && index < this.items.length) {
                    this.insertAfterIndex = index;
                    this.addItemModalOpen = true;
                    this.reinitializeIcons();
                }
            },

            addNewItem(type) {
                try {
                    const newItem = {
                        id: this.generateUniqueId(),
                        type: type,
                        points: 1,
                        questionText: '',
                        shortAnswerField: '',
                        longAnswerField: '',
                        modelAnswer: '',
                        acceptedAnswers: type === 'short_answer' ? [''] : [], // Initialize for short answer
                        trueText: 'True',
                        falseText: 'False',
                        correctAnswer: type === 'true_false' ? 'true' : 0,
                        options: type === 'multiple_choice' ? ['', ''] : [],
                        imageData: null,
                        imageName: null
                    };

                    if (this.insertAfterIndex !== null) {
                        this.items.splice(this.insertAfterIndex + 1, 0, newItem);
                    } else {
                        this.items.push(newItem);
                    }

                    this.closeAddItemModal();
                    this.reinitializeIcons();
                } catch (error) {
                    console.error('Failed to add new item:', error);
                }
            },


            // ===== FORM SUBMISSION =====
            
            submitForm(event) {
                console.log('=== FORM SUBMISSION STARTED ===');
                this.serializeItems();
                
                // Get which button was clicked
                const submitter = event.submitter;
                const action = submitter ? submitter.value : 'publish';
                console.log('Button clicked:', action);
                
                // Add a delay so we can see the console logs
                setTimeout(() => {
                    console.log('=== SUBMITTING FORM NOW ===');
                    const form = document.querySelector('form[action*="assessment-builder"]');
                    console.log('Form found:', form);
                    
                    if (form) {
                        // Use AJAX instead of form.submit() to prevent page reload
                        const formData = new FormData(form);
                        
                        // Make sure the action is included
                        formData.set('action', action);
                        
                        // Get CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                         document.querySelector('input[name="_token"]')?.value;
                        
                        // Use the form's action URL (handles both create and update)
                        const url = form.getAttribute('action');
                        
                        // CRITICAL FIX: Check if this is an edit (URL contains ID) and force add _method
                        const isEditMode = url.match(/assessment-builder\/\d+$/);
                        const methodInput = form.querySelector('input[name="_method"]');
                        
                        if (isEditMode || (methodInput && methodInput.value === 'PUT')) {
                            formData.set('_method', 'PUT');
                            console.log('✓ Forced _method=PUT into FormData (edit mode)');
                        }
                        
                        console.log('Form data being sent:');
                        for (let [key, value] of formData.entries()) {
                            console.log(key + ':', value);
                        }
                        
                        console.log('Final URL:', url);
                        console.log('Has _method?', formData.has('_method'));
                        console.log('_method value:', formData.get('_method'));
                        
                        fetch(url, {
                            method: 'POST', // Always POST, Laravel handles _method for PUT
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            console.log('Response headers:', response.headers);
                            
                            if (response.status === 200) {
                                console.log('✅ SUCCESS: Form submitted successfully!');
                                return response.json();
                            } else if (response.status === 422) {
                                console.log('❌ VALIDATION ERROR: Form validation failed');
                                return response.json();
                            } else if (response.status === 500) {
                                console.log('❌ SERVER ERROR: Something went wrong');
                                return response.json();
                            } else {
                                console.log('⚠️ UNEXPECTED STATUS:', response.status);
                                return response.text();
                            }
                        })
                        .then(data => {
                            console.log('Response data:', data);
                            if (typeof data === 'object') {
                                if (data.success) {
                                    console.log('✅ SUCCESS:', data.message);
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonColor: '#000000',
                                        background: '#ffffff',
                                        iconColor: '#22c55e'
                                    }).then(() => {
                                        // Redirect to course page if course_code is provided
                                        if (data.course_code) {
                                            window.location.href = `/implementor/course-information/${data.course_code}`;
                                        } else {
                                            window.location.reload();
                                        }
                                    });
                                } else if (data.errors) {
                                    console.log('❌ Validation errors:', data.errors);
                                    const errorMessages = Object.values(data.errors).flat().join('\n');
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Validation Failed',
                                        text: errorMessages,
                                        confirmButtonColor: '#000000',
                                        background: '#ffffff',
                                        iconColor: '#ef4444'
                                    });
                                } else if (data.message) {
                                    console.log('❌ ERROR:', data.message);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: data.message,
                                        confirmButtonColor: '#000000',
                                        background: '#ffffff',
                                        iconColor: '#ef4444'
                                    });
                                }
                            } else {
                                console.log('Response is not JSON:', data);
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting form:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Submission Error',
                                text: 'Failed to submit form: ' + error.message,
                                confirmButtonColor: '#000000',
                                background: '#ffffff',
                                iconColor: '#ef4444'
                            });
                        });
                    } else {
                        console.error('Form not found!');
                    }
                }, 2000); // 2 second delay
            },

            // ===== FORM SERIALIZATION =====
            
            serializeItems() {
                try {
                    console.log('Starting form serialization...');
                    this.captureInputValues();
                    
                    // Select the form element
                    const form = document.querySelector('form[action*="assessment-builder"]');
                    console.log('Form found for serialization:', form);
                    
                    if (!form) {
                        console.error('Form not found! Cannot serialize items.');
                        return;
                    }
                    
                    // Clear any existing hidden inputs (except _token, _method, quiz_id, is_editing, course_id)
                    const existingHidden = form.querySelectorAll('input[type="hidden"]:not([name="_token"]):not([name="_method"]):not([name="quiz_id"]):not([name="is_editing"]):not([name="course_id"])');
                    existingHidden.forEach(input => input.remove());
                    
                    // Create hidden input for questions
                    const questionsInput = document.createElement('input');
                    questionsInput.type = 'hidden';
                    questionsInput.name = 'questions';
                    
                    // Format questions data for backend
                    const formattedQuestions = this.items.map(item => {
                        const question = {
                            text: item.questionText || '',
                            type: item.type,
                            points: item.points || 1,
                            modelAnswer: item.modelAnswer || '',
                            imageData: item.imageData || null,
                            imageName: item.imageName || null,
                        };
                        
                        // Add type-specific data
                        if (item.type === 'multiple_choice') {
                            question.options = item.options || [];
                            question.correctAnswer = item.correctAnswer || 0;
                        } else if (item.type === 'true_false') {
                            question.trueText = item.trueText || '';
                            question.falseText = item.falseText || '';
                            question.correctAnswer = item.correctAnswer || 'true';
                        } else if (item.type === 'short_answer') {
                            // Send acceptedAnswers array for short answer questions
                            question.acceptedAnswers = item.acceptedAnswers || [''];
                        }
                        
                        return question;
                    });
                    
                    questionsInput.value = JSON.stringify(formattedQuestions);
                    form.appendChild(questionsInput);
                    
                    console.log('Formatted questions:', formattedQuestions);
                    console.log('Alpine data:', {
                        course_id: this.assessment.course_id,
                        type: this.assessment.type,
                        title: this.formTitle,
                        questions: formattedQuestions
                    });
                    
                    // Force update form fields with Alpine data
                    const courseSelect = form.querySelector('select[name="course_id"]');
                    if (courseSelect) courseSelect.value = this.assessment.course_id || '';
                    
                    const typeSelect = form.querySelector('select[name="type"]');
                    if (typeSelect) typeSelect.value = this.assessment.type || '';
                    
                    const titleInput = form.querySelector('input[name="title"]');
                    if (titleInput) titleInput.value = this.formTitle || 'Assessment Form Title';
                    
                    const descriptionTextarea = form.querySelector('textarea[name="description"]');
                    if (descriptionTextarea) descriptionTextarea.value = this.assessment.description || '';
                    
                    const timerHoursInput = form.querySelector('input[name="timer_hours"]');
                    if (timerHoursInput) timerHoursInput.value = this.assessment.timer_hours || '';
                    
                    const timerMinutesInput = form.querySelector('input[name="timer_minutes"]');
                    if (timerMinutesInput) timerMinutesInput.value = this.assessment.timer_minutes || '';
                    
                    const submissionLimitInput = form.querySelector('input[name="submission_limit"]');
                    if (submissionLimitInput) submissionLimitInput.value = this.assessment.submission_limit || '';
                    
                    const closingScheduleInput = form.querySelector('input[name="closing_schedule"]');
                    if (closingScheduleInput) closingScheduleInput.value = this.assessment.closing_schedule || '';
                    
                    console.log('Form fields updated with Alpine data');
                } catch (error) {
                    console.error('Failed to serialize items:', error);
                }
            },

            // ===== DELETE FUNCTIONALITY =====
            
            confirmDelete() {
                const status = '{{ $quiz->status ?? "" }}';
                const quizId = {{ $quiz->id ?? 'null' }};
                const courseCode = '{{ $quiz->course->course_code ?? "" }}';
                
                let title = 'Delete Assessment?';
                let message = 'Are you sure you want to delete this assessment?';
                let warning = '';
                
                if (status === 'Published') {
                    title = 'Delete Published Assessment?';
                    message = 'This assessment is PUBLISHED and learners may have already taken it.';
                    warning = 'Deleting it will remove all associated submissions and results.';
                } else if (status === 'Draft') {
                    title = 'Delete Draft Assessment?';
                    message = 'This action cannot be undone.';
                }
                
                Swal.fire({
                    icon: 'warning',
                    title: title,
                    html: warning ? `<p>${message}</p><p class="mt-2 font-semibold text-red-600">${warning}</p>` : message,
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send delete request
                        fetch(`/implementor/assessment-builder/${quizId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: 'Assessment deleted successfully',
                                    confirmButtonColor: '#000000',
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    window.location.href = `/implementor/course-information/${courseCode}`;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message,
                                    confirmButtonColor: '#000000'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Delete error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to delete assessment',
                                confirmButtonColor: '#000000'
                            });
                        });
                    }
                });
            },

            // ===== PREVIEW FUNCTIONALITY =====
            
            switchToPreview() {
                try {
                    this.captureInputValues();
                    this.isPreviewMode = true;
                    this.reinitializeIcons();
                } catch (error) {
                    console.error('Failed to switch to preview:', error);
                }
            },

            captureInputValues() {
                this.items.forEach(i => {
                    if (['multiple_choice','dropdown','checkboxes'].includes(i.type) && !Array.isArray(i.options)) i.options = ['',''];
                    i.questionText = i.questionText || '';
                    i.points = i.points || 1;
                });
            },


            getPreviewContent(item) {
                try {
                    const values = this.getPreviewValues(item);
                    return this.generatePreviewHTML(item.type, values, item.id);
                } catch (error) {
                    console.error('Failed to generate preview content:', error);
                    return '<div>Error generating preview</div>';
                }
            },

            getPreviewValues(item) {
                return {
                    questionText: item.questionText || '',
                    textValue: item.textValue || '',
                    shortAnswerField: item.shortAnswerField || '',
                    longAnswerField: item.longAnswerField || '',
                    trueText: item.trueText || 'True',
                    falseText: item.falseText || 'False',
                    options: item.options || [],
                    points: item.points || 1,
                    imageData: item.imageData || null,
                    imageName: item.imageName || null
                };
            },

            generatePreviewHTML(type, values, itemId) {
                const { questionText, textValue, shortAnswerField, longAnswerField, trueText, falseText, options, points, imageData, imageName } = values;
                
                // Helper function to generate image HTML if present
                const getImageHTML = () => {
                    if (imageData) {
                        return `<div class="mb-4"><img src="${imageData}" alt="${imageName || 'Question image'}" class="max-w-md max-h-64 rounded-lg border border-gray-200" /></div>`;
                    }
                    return '';
                };
                
                switch (type) {
                    case 'text':
                        return `<div class="text-content mb-6">${textValue || 'Enter text content...'}</div>`;
                    
                    case 'heading':
                        return `<h2 class="text-2xl font-bold mb-6">${questionText || 'Enter heading...'}</h2>`;
                    
                    case 'short_answer':
                        return `
                            <div class="mb-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <label class="block text-lg font-medium">${questionText || 'Enter question...'}</label>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">${points} point${points !== 1 ? 's' : ''}</span>
                                </div>
                                ${getImageHTML()}
                                <input type="text" placeholder="Your answer here..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            </div>
                        `;
                    
                    case 'long_answer':
                        return `
                            <div class="mb-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <label class="block text-lg font-medium">${questionText || 'Enter question...'}</label>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">${points} point${points !== 1 ? 's' : ''}</span>
                                </div>
                                ${getImageHTML()}
                                <textarea placeholder="Your answer here..." rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-vertical"></textarea>
                            </div>
                        `;
                    
                    case 'true_false':
                        return `
                            <div class="mb-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <label class="block text-lg font-medium">${questionText || 'Enter statement...'}</label>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">${points} point${points !== 1 ? 's' : ''}</span>
                                </div>
                                ${getImageHTML()}
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="tf-${itemId}" value="true" class="mr-2" />
                                        <span>${trueText}</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="tf-${itemId}" value="false" class="mr-2" />
                                        <span>${falseText}</span>
                                    </label>
                                </div>
                            </div>
                        `;
                    
                    case 'multiple_choice':
                        const mcOptions = options.map((opt, idx) => {
                            const letter = String.fromCharCode(65 + idx);
                            return `<label class="flex items-center"><input type="radio" name="mc-${itemId}" value="${idx}" class="mr-2" /><span>${letter}. ${opt}</span></label>`;
                        }).join('');
                        return `
                            <div class="mb-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <label class="block text-lg font-medium">${questionText || 'Enter question...'}</label>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">${points} point${points !== 1 ? 's' : ''}</span>
                                </div>
                                ${getImageHTML()}
                                <div class="space-y-2">${mcOptions}</div>
                            </div>
                        `;
                    
                    case 'dropdown':
                        const selectOptions = options.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                        return `
                            <div class="mb-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <label class="block text-lg font-medium">${questionText || 'Enter question...'}</label>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">${points} point${points !== 1 ? 's' : ''}</span>
                                </div>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Select an option...</option>
                                    ${selectOptions}
                                </select>
                            </div>
                        `;
                    
                    case 'checkboxes':
                        const cbOpts = options.map((opt, idx) => {
                            return `<label class="flex items-center"><input type="checkbox" name="cb-${itemId}[]" value="${idx}" class="mr-2" /><span>${opt}</span></label>`;
                        }).join('');
                        return `
                            <div class="mb-6">
                                <div class="flex items-center gap-2 mb-3">
                                    <label class="block text-lg font-medium">${questionText || 'Enter question...'}</label>
                                    <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">${points} point${points !== 1 ? 's' : ''}</span>
                                </div>
                                <div class="space-y-2">${cbOpts}</div>
                            </div>
                        `;
                    
                    default:
                        return `<div>Unknown question type</div>`;
                }
            },

            // ===== DRAG AND DROP =====

            initDragAndDrop() {
                try {
                    const container = this.safeQuerySelector(this.$el, '[x-ref="itemsContainer"]') || this.$el;
                    if (!container) return;

                    let draggedElement = null;
                    let draggedIndex = null;

                    // Add drag event listeners
                    container.addEventListener('dragstart', (e) => {
                        if (e.target.closest('.drag-handle')) {
                            draggedElement = e.target.closest('[data-id]');
                            draggedIndex = this.items.findIndex(item => item.id == draggedElement.dataset.id);
                            e.dataTransfer.effectAllowed = 'move';
                            e.dataTransfer.setData('text/html', draggedElement.outerHTML);
                            
                            // Lightweight drag animation for all devices
                            this.applyDragStyles(draggedElement);
                        }
                    });

                    container.addEventListener('dragend', (e) => {
                        if (draggedElement) {
                            this.removeDragStyles(draggedElement);
                            setTimeout(() => {
                                draggedElement = null;
                                draggedIndex = null;
                            }, 200);
                        }
                    });

                    container.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        e.dataTransfer.dropEffect = 'move';
                        
                        const dropTarget = e.target.closest('[data-id]');
                        if (dropTarget && dropTarget !== draggedElement) {
                            this.applyDropZoneStyles(dropTarget);
                        }
                    });

                    container.addEventListener('dragleave', (e) => {
                        const dropTarget = e.target.closest('[data-id]');
                        if (dropTarget && dropTarget !== draggedElement) {
                            this.removeDropZoneStyles(dropTarget);
                        }
                    });

                    container.addEventListener('drop', (e) => {
                        e.preventDefault();
                        
                        if (draggedElement && draggedIndex !== null) {
                            const dropTarget = e.target.closest('[data-id]');
                            if (dropTarget && dropTarget !== draggedElement) {
                                const targetIndex = this.items.findIndex(item => item.id == dropTarget.dataset.id);
                                
                                if (targetIndex !== -1 && draggedIndex !== targetIndex) {
                                    const item = this.items.splice(draggedIndex, 1)[0];
                                    this.items.splice(targetIndex, 0, item);
                                }
                            }
                            
                            if (dropTarget) {
                                this.removeDropZoneStyles(dropTarget);
                            }
                        }
                    });
                } catch (error) {
                    console.error('Failed to initialize drag and drop:', error);
                }
            },

            applyDragStyles(element) {
                if (element) {
                    element.style.opacity = '0.8';
                    element.style.transform = 'scale(1.02)';
                    element.style.transition = 'all 0.2s ease';
                    element.style.zIndex = '1000';
                }
            },

            removeDragStyles(element) {
                if (element) {
                    element.style.transform = 'scale(1)';
                    element.style.opacity = '1';
                    element.style.zIndex = '';
                    element.style.transition = 'all 0.2s ease';
                }
            },

            applyDropZoneStyles(element) {
                if (element) {
                    element.style.borderTop = '3px solid #007AFF';
                    element.style.backgroundColor = '#F2F2F7';
                    element.style.transform = 'scale(1.01)';
                    element.style.transition = 'all 0.15s ease';
                }
            },

            removeDropZoneStyles(element) {
                if (element) {
                    element.style.borderTop = '';
                    element.style.backgroundColor = '';
                    element.style.transform = '';
                    element.style.transition = '';
                }
            }
        }));
    });
    </script>
</main>
@endsection