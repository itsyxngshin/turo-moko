@extends('layouts.layout')

@section('title', 'Create Assessment')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<main x-data="assessmentBuilder">
    <!-- Assessment Builder -->
    <div x-show="!isPreviewMode">
    <form
            action="{{ route('implementor.assessment.store') }}"
            method="POST"
        class="bg-white rounded-3xl border border-gray-200 shadow-sm py-10 px-10 ml-4 mt-2 flex flex-col gap-8"
            @submit="serializeItems()"
    >
            @csrf

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Assessment Details</h2>
        </div>

        <div class="space-y-6">
        <div class="flex flex-col">
                <label class="font-semibold text-sm mb-2">Course Name</label>
                <select x-model="assessment.course" name="course" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select course</option>
                    <template x-for="course in courses" :key="course.id">
                        <option :value="course.id" x-text="course.name"></option>
                    </template>
            </select>
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
                    <label class="font-semibold text-sm mb-2">Deadline</label>
                    <input x-model="assessment.deadline" type="date" name="deadline" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
            value="Assessment Form Title"
            class="text-3xl font-bold text-center"
            x-model="formTitle"
        />

        <!-- Dynamic Assessment Items -->
        <div x-ref="itemsContainer" class="space-y-6">
            <template x-for="(item, index) in items" :key="item.id">
                <div class="flex items-start gap-8 p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200" :data-id="item.id">
            <div class="flex gap-2 w-15 flex-shrink-0">
                        <button type="button" @click="removeItem(index)"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
                        <button type="button" @click="addItemAfter(index)"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
                        <button type="button" class="drag-handle cursor-move" draggable="true"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
            </div>
                    <div class="flex-1" x-html="item.content"></div>
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

            <div class="grid grid-cols-4 gap-4">
                <button @click="addNewItem('text')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                    <i data-lucide="type" class="w-6 h-6 mb-2"></i>
                    <span class="text-xs text-center px-1">Text</span>
                </button>
                <button @click="addNewItem('heading')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                    <i data-lucide="heading" class="w-6 h-6 mb-2"></i>
                    <span class="text-xs text-center">Heading</span>
                </button>
                <button @click="addNewItem('short_answer')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                    <i data-lucide="edit" class="w-6 h-6 mb-2"></i>
                    <span class="text-xs text-center">Short Answer</span>
                </button>
                <button @click="addNewItem('long_answer')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                        <i data-lucide="file-text" class="w-6 h-6 mb-2"></i>
                        <span class="text-xs text-center px-1">Long Answer</span>
                    </button>
                <button @click="addNewItem('true_false')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                        <i data-lucide="check-square" class="w-6 h-6 mb-2"></i>
                        <span class="text-xs text-center">True/False</span>
                    </button>
                <button @click="addNewItem('multiple_choice')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                        <i data-lucide="list" class="w-6 h-6 mb-2"></i>
                        <span class="text-xs text-center">Multiple Choice</span>
                    </button>
                <button @click="addNewItem('dropdown')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                        <i data-lucide="chevrons-down" class="w-6 h-6 mb-2"></i>
                        <span class="text-xs text-center">Dropdown</span>
                    </button>
                <button @click="addNewItem('checkboxes')" class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
                        <i data-lucide="square" class="w-6 h-6 mb-1"></i>
                        <span class="text-xs text-center">Checkboxes</span>
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
            formTitle: 'Assessment Form Title',
            
            // Assessment details state
            assessment: {
                course: '',
                type: '',
                deadline: '',
                description: ''
            },
            
            // Courses array - will be populated from database
            courses: [
                { id: 'course1', name: 'Course 1' },
                { id: 'course2', name: 'Course 2' }
                // TODO: Replace with dynamic data from database
            ],
            
            // Start with empty items array - users add from scratch
            items: [],

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
                        content: this.getItemTemplate(type),
                        // Initialize preview data
                        questionText: '',
                        textValue: '',
                        shortAnswerField: '',
                        longAnswerField: '',
                        trueText: 'True',
                        falseText: 'False',
                        options: []
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

            // ===== TEMPLATE GENERATION =====
            
            /**
             * Get template for specific item type
             */
            getItemTemplate(type) {
                const templates = {
                    text: this.getTextTemplate(),
                    heading: this.getHeadingTemplate(),
                    short_answer: this.getShortAnswerTemplate(),
                    long_answer: this.getLongAnswerTemplate(),
                    true_false: this.getTrueFalseTemplate(),
                    multiple_choice: this.getMultipleChoiceTemplate(),
                    dropdown: this.getDropdownTemplate(),
                    checkboxes: this.getCheckboxesTemplate()
                };

                return templates[type] || '<div>Unknown question type</div>';
            },

            getTextTemplate() {
                return '<textarea name="" id="" class="w-full resize-none overflow-hidden leading-relaxed min-h-[60px]" data-role="textValue" x-model="item.textValue" @input="this.style.height = \'\'; this.style.height = this.scrollHeight + \'px\'" placeholder="Enter text content..."></textarea>';
            },

            getHeadingTemplate() {
                return '<input class="text-xl font-semibold w-full min-w-0" type="text" data-role="questionText" placeholder="Enter heading..." x-model="item.questionText" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />';
            },

            getShortAnswerTemplate() {
                return `<div>
                    <input type="text" data-role="questionText" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div class="mt-4">
                        <input type="text" data-role="shortAnswerField" placeholder="Short answer field" class="px-4 py-2 rounded-xl border bg-white shadow-sm min-w-[200px] max-w-full" x-model="item.shortAnswerField" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 20), this.parentElement.offsetWidth) + \'px\'" />
                    </div>
                </div>`;
            },

            getLongAnswerTemplate() {
                return `<div>
                    <input type="text" data-role="questionText" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <textarea data-role="longAnswerField" placeholder="Long answer field" class="w-full border rounded-lg p-3 min-h-[80px]" rows="3" x-model="item.longAnswerField" @input="this.style.height = \'\'; this.style.height = this.scrollHeight + \'px\'"></textarea>
                </div>`;
            },

            getTrueFalseTemplate() {
                return `<div>
                    <input type="text" data-role="questionText" placeholder="Enter statement..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div class="space-y-3">
                        <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="tf-new" value="true" class="hidden peer" />
                            <span class="peer-checked:font-semibold peer-checked:text-blue-600">
                                <b>TRUE:</b> <input type="text" data-role="trueText" placeholder="True statement" class="border-none outline-none bg-transparent min-w-[150px] max-w-full" x-model="item.trueText" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.closest(\'.block\').offsetWidth - 100) + \'px\'" />
                            </span>
                        </label>
                        <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="tf-new" value="false" class="hidden peer" />
                            <span class="peer-checked:font-semibold peer-checked:text-blue-600">
                                <b>FALSE:</b> <input type="text" data-role="falseText" placeholder="False statement" class="border-none outline-none bg-transparent min-w-[150px] max-w-full" x-model="item.falseText" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.closest(\'.block\').offsetWidth - 100) + \'px\'" />
                            </span>
                        </label>
                    </div>
                </div>`;
            },

            getMultipleChoiceTemplate() {
                return `<div>
                    <input type="text" data-role="questionText" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div class="space-y-3" x-data="{ options: ['', ''], questionId: 'mc-' + Math.random().toString(36).substr(2, 9) }" x-init="item.options = options; $watch('options', value => item.options = value)">
                        <template x-for="(option, index) in options" :key="index">
                            <div class="flex items-center gap-2">
                                <label class="flex-1 block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                    <input type="radio" :name="questionId" :value="index" class="hidden peer" />
                                    <span class="peer-checked:font-semibold peer-checked:text-blue-600">
                                        <b x-text="String.fromCharCode(65 + index) + '.'"></b> 
                                        <input type="text" data-role="option" :placeholder="'Option ' + String.fromCharCode(65 + index)" class="border-none outline-none bg-transparent min-w-[150px] max-w-full" x-model="options[index]" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.closest(\'.block\').offsetWidth - 100) + \'px\'" />
                                    </span>
                                </label>
                                <button type="button" @click="options.splice(index, 1)" class="text-red-600 hover:text-red-800 px-2" x-show="options.length > 1">×</button>
                            </div>
                        </template>
                        <button type="button" @click="options.push('')" class="text-blue-600 hover:text-blue-800 text-sm">+ Add option</button>
                    </div>
                </div>`;
            },

            getDropdownTemplate() {
                return `<div>
                    <input type="text" data-role="questionText" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div x-data="{ options: ['Option 1', 'Option 2'] }" x-init="item.options = options; $watch('options', value => item.options = value)">
                        <select class="px-4 py-2 rounded-xl border bg-white shadow-sm min-w-[200px] max-w-full mb-3" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 20), this.parentElement.offsetWidth) + \'px\'">
                            <option>Select option...</option>
                            <template x-for="(option, index) in options" :key="index">
                                <option x-text="option"></option>
                            </template>
                        </select>
                        <div class="space-y-2">
                            <template x-for="(option, index) in options" :key="index">
                                <div class="flex items-center gap-2">
                                    <input type="text" data-role="option" x-model="options[index]" class="border rounded px-2 py-1 flex-1 min-w-[150px] max-w-full" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.parentElement.offsetWidth - 50) + \'px\'" />
                                    <button type="button" @click="options.splice(index, 1)" class="text-red-600 hover:text-red-800">×</button>
                                </div>
                            </template>
                            <button type="button" @click="options.push('New Option')" class="text-blue-600 hover:text-blue-800 text-sm">+ Add option</button>
                        </div>
                    </div>
                </div>`;
            },

            getCheckboxesTemplate() {
                return `<div>
                    <input type="text" data-role="questionText" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" x-model="item.questionText" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div class="space-y-3" x-data="{ options: ['', ''] }" x-init="item.options = options; $watch('options', value => item.options = value)">
                        <template x-for="(option, index) in options" :key="index">
                            <div class="flex items-center gap-2">
                                <label class="flex-1 flex items-center gap-2 mb-2 block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" :name="'cb-' + Date.now() + '[]'" :value="index" />
                                    <input type="text" data-role="option" :placeholder="'Option ' + (index + 1)" class="border-none outline-none bg-transparent flex-1 min-w-[150px] max-w-full" x-model="options[index]" @input="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.closest(\'.block\').offsetWidth - 100) + \'px\'" />
                                </label>
                                <button type="button" @click="options.splice(index, 1)" class="text-red-600 hover:text-red-800 px-2" x-show="options.length > 1">×</button>
                            </div>
                        </template>
                        <button type="button" @click="options.push('')" class="text-blue-600 hover:text-blue-800 text-sm">+ Add option</button>
                    </div>
                </div>`;
            },

            // ===== FORM SERIALIZATION =====
            
            serializeItems() {
                try {
                    this.captureInputValues();
                    
                    // Create hidden input for items
                    const itemsInput = document.createElement('input');
                    itemsInput.type = 'hidden';
                    itemsInput.name = 'items';
                    itemsInput.value = JSON.stringify(this.items);
                    this.$el.appendChild(itemsInput);
                    
                    // Add title from the form title input
                    const titleInput = document.createElement('input');
                    titleInput.type = 'hidden';
                    titleInput.name = 'title';
                    titleInput.value = this.formTitle || 'Assessment Form Title';
                    this.$el.appendChild(titleInput);
                } catch (error) {
                    console.error('Failed to serialize items:', error);
                }
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
                try {
                    this.items.forEach((item) => {
                        const itemElement = this.safeQuerySelector(this.$el, `[data-id="${item.id}"]`);
                        
                        if (itemElement) {
                            const contentDiv = this.safeQuerySelector(itemElement, '.flex-1');
                            if (contentDiv) {
                                // Capture question text
                                const questionInput = this.safeQuerySelector(contentDiv, '[data-role="questionText"]');
                                item.questionText = questionInput?.value || '';
                                
                                // Capture text value
                                const textValueInput = this.safeQuerySelector(contentDiv, '[data-role="textValue"]');
                                item.textValue = textValueInput?.value || '';
                                
                                // Capture type-specific values
                                this.captureTypeSpecificValues(item, contentDiv);
                                
                                // Debug: Log what we captured
                                console.log(`Item ${item.type} captured:`, {
                                    questionText: item.questionText,
                                    shortAnswerField: item.shortAnswerField,
                                    longAnswerField: item.longAnswerField
                                });
                            }
                        }
                    });
                } catch (error) {
                    console.error('Failed to capture input values:', error);
                }
            },

            captureTypeSpecificValues(item, contentDiv) {
                switch (item.type) {
                    case 'short_answer':
                        const shortAnswerInput = this.safeQuerySelector(contentDiv, '[data-role="shortAnswerField"]');
                        item.shortAnswerField = shortAnswerInput?.value || '';
                        break;
                        
                    case 'long_answer':
                        const longAnswerInput = this.safeQuerySelector(contentDiv, '[data-role="longAnswerField"]');
                        item.longAnswerField = longAnswerInput?.value || '';
                        break;
                        
                    case 'true_false':
                        const trueInput = this.safeQuerySelector(contentDiv, '[data-role="trueText"]');
                        const falseInput = this.safeQuerySelector(contentDiv, '[data-role="falseText"]');
                        item.trueText = trueInput?.value || 'True';
                        item.falseText = falseInput?.value || 'False';
                        break;
                        
                    case 'multiple_choice':
                    case 'dropdown':
                    case 'checkboxes':
                        const optionInputs = this.safeQuerySelectorAll(contentDiv, '[data-role="option"]');
                        item.options = optionInputs.map(opt => opt.value).filter(opt => opt);
                        break;
                }
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
                    options: item.options || []
                };
            },

            generatePreviewHTML(type, values, itemId) {
                const { questionText, textValue, shortAnswerField, longAnswerField, trueText, falseText, options } = values;
                
                switch (type) {
                    case 'text':
                        return `<div class="text-content mb-6">${textValue || 'Enter text content...'}</div>`;
                    
                    case 'heading':
                        return `<h2 class="text-2xl font-bold mb-6">${questionText || 'Enter heading...'}</h2>`;
                    
                    case 'short_answer':
                        return `
                            <div class="mb-6">
                                <label class="block text-lg font-medium mb-3">${questionText || 'Enter question...'}</label>
                                <input type="text" placeholder="Your answer here..." value="${shortAnswerField || ''}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            </div>
                        `;
                    
                    case 'long_answer':
                        return `
                            <div class="mb-6">
                                <label class="block text-lg font-medium mb-3">${questionText || 'Enter question...'}</label>
                                <textarea placeholder="Your answer here..." rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-vertical">${longAnswerField || ''}</textarea>
                            </div>
                        `;
                    
                    case 'true_false':
                        return `
                            <div class="mb-6">
                                <label class="block text-lg font-medium mb-3">${questionText || 'Enter statement...'}</label>
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
                                <label class="block text-lg font-medium mb-3">${questionText || 'Enter question...'}</label>
                                <div class="space-y-2">${mcOptions}</div>
                            </div>
                        `;
                    
                    case 'dropdown':
                        const selectOptions = options.map(opt => `<option value="${opt}">${opt}</option>`).join('');
                        return `
                            <div class="mb-6">
                                <label class="block text-lg font-medium mb-3">${questionText || 'Enter question...'}</label>
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
                                <label class="block text-lg font-medium mb-3">${questionText || 'Enter question...'}</label>
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