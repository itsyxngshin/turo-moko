@extends('layouts.layout')

@section('title', 'Create Assessment')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<main x-data="assessmentBuilder">
    <form
        action=""
        class="bg-white rounded-3xl border border-gray-200 shadow-sm py-10 px-10 ml-4 mt-2 flex flex-col gap-8"
    >

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
                @click="addItemModalOpen = true"
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
                type="submit"
                name="action"
                value="preview"
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

    <!-- Add Item Modal -->
    <div x-show="addItemModalOpen" x-transition x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">Add anything</h2>
                <button type="button" @click="addItemModalOpen = false"><i data-lucide="x" class="w-6 h-6 text-gray-600"></i></button>
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

    <script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("assessmentBuilder", () => ({
            addItemModalOpen: false,
            insertAfterIndex: null,
            
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

            // Basic methods for Step 1
            removeItem(index) {
                this.items.splice(index, 1);
                // Reinitialize icons after DOM update
                this.$nextTick(() => {
                    lucide.createIcons();
                });
            },

            addItemAfter(index) {
                this.insertAfterIndex = index;
                this.addItemModalOpen = true;
            },

            // Item templates for new items
            itemTemplates: {
                text: '<textarea name="" id="" class="w-full resize-none overflow-hidden leading-relaxed min-h-[60px]" oninput="this.style.height = \'\'; this.style.height = this.scrollHeight + \'px\'" placeholder="Enter text content..."></textarea>',
                heading: '<input class="text-xl font-semibold w-full min-w-0" type="text" placeholder="Enter heading..." oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />',
                short_answer: `<div>
                    <input type="text" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div class="mt-4">
                        <input type="text" placeholder="Short answer field" class="px-4 py-2 rounded-xl border bg-white shadow-sm min-w-[200px] max-w-full" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 20), this.parentElement.offsetWidth) + \'px\'" />
                    </div>
                </div>`,
                long_answer: `<div>
                    <input type="text" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <textarea placeholder="Long answer field" class="w-full border rounded-lg p-3 min-h-[80px]" rows="3" oninput="this.style.height = \'\'; this.style.height = this.scrollHeight + \'px\'"></textarea>
                </div>`,
                true_false: `<div>
                    <input type="text" placeholder="Enter statement..." class="text-xl font-semibold mb-4 w-full min-w-0" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div class="space-y-3">
                        <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="tf-new" value="true" class="hidden peer" />
                            <span class="peer-checked:font-semibold peer-checked:text-blue-600">
                                <b>TRUE:</b> <input type="text" placeholder="True statement" class="border-none outline-none bg-transparent min-w-[150px] max-w-full" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.closest(\'.block\').offsetWidth - 100) + \'px\'" />
                            </span>
                        </label>
                        <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="tf-new" value="false" class="hidden peer" />
                            <span class="peer-checked:font-semibold peer-checked:text-blue-600">
                                <b>FALSE:</b> <input type="text" placeholder="False statement" class="border-none outline-none bg-transparent min-w-[150px] max-w-full" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.closest(\'.block\').offsetWidth - 100) + \'px\'" />
                            </span>
                        </label>
                    </div>
                </div>`,
                multiple_choice: `<div>
                    <input type="text" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div class="space-y-3" x-data="{ options: ['', ''], questionId: 'mc-' + Math.random().toString(36).substr(2, 9) }">
                        <template x-for="(option, index) in options" :key="index">
                            <div class="flex items-center gap-2">
                                <label class="flex-1 block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                    <input type="radio" :name="questionId" :value="index" class="hidden peer" />
                                    <span class="peer-checked:font-semibold peer-checked:text-blue-600">
                                        <b x-text="String.fromCharCode(65 + index) + '.'"></b> 
                                        <input type="text" :placeholder="'Option ' + String.fromCharCode(65 + index)" class="border-none outline-none bg-transparent min-w-[150px] max-w-full" x-model="options[index]" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.closest(\'.block\').offsetWidth - 100) + \'px\'" />
                                    </span>
                                </label>
                                <button type="button" @click="options.splice(index, 1)" class="text-red-600 hover:text-red-800 px-2" x-show="options.length > 1">×</button>
                            </div>
                        </template>
                        <button type="button" @click="options.push('')" class="text-blue-600 hover:text-blue-800 text-sm">+ Add option</button>
                    </div>
                </div>`,
                dropdown: `<div>
                    <input type="text" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div x-data="{ options: ['Option 1', 'Option 2'] }">
                        <select class="px-4 py-2 rounded-xl border bg-white shadow-sm min-w-[200px] max-w-full mb-3" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 20), this.parentElement.offsetWidth) + \'px\'">
                            <option>Select option...</option>
                            <template x-for="(option, index) in options" :key="index">
                                <option x-text="option"></option>
                            </template>
                        </select>
                        <div class="space-y-2">
                            <template x-for="(option, index) in options" :key="index">
                                <div class="flex items-center gap-2">
                                    <input type="text" x-model="options[index]" class="border rounded px-2 py-1 flex-1 min-w-[150px] max-w-full" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.parentElement.offsetWidth - 50) + \'px\'" />
                                    <button type="button" @click="options.splice(index, 1)" class="text-red-600 hover:text-red-800">×</button>
                                </div>
                            </template>
                            <button type="button" @click="options.push('New Option')" class="text-blue-600 hover:text-blue-800 text-sm">+ Add option</button>
                        </div>
                    </div>
                </div>`,
                checkboxes: `<div>
                    <input type="text" placeholder="Enter question..." class="text-xl font-semibold mb-4 w-full min-w-0" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(200, this.scrollWidth + 10), this.parentElement.offsetWidth) + \'px\'" />
                    <div class="space-y-3" x-data="{ options: ['', ''] }">
                        <template x-for="(option, index) in options" :key="index">
                            <div class="flex items-center gap-2">
                                <label class="flex-1 flex items-center gap-2 mb-2 block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                                    <input type="checkbox" :name="'cb-' + Date.now() + '[]'" :value="index" />
                                    <input type="text" :placeholder="'Option ' + (index + 1)" class="border-none outline-none bg-transparent flex-1 min-w-[150px] max-w-full" x-model="options[index]" oninput="this.style.width = \'\'; this.style.width = Math.min(Math.max(150, this.scrollWidth + 10), this.closest(\'.block\').offsetWidth - 100) + \'px\'" />
                                </label>
                                <button type="button" @click="options.splice(index, 1)" class="text-red-600 hover:text-red-800 px-2" x-show="options.length > 1">×</button>
                            </div>
                        </template>
                        <button type="button" @click="options.push('')" class="text-blue-600 hover:text-blue-800 text-sm">+ Add option</button>
                    </div>
                </div>`
            },

            addNewItem(type) {
                const newItem = {
                    id: Date.now(),
                    type: type,
                    content: this.itemTemplates[type]
                };

                if (this.insertAfterIndex !== null) {
                    this.items.splice(this.insertAfterIndex + 1, 0, newItem);
                    this.insertAfterIndex = null;
                } else {
                    this.items.push(newItem);
                }

                this.addItemModalOpen = false;
                
                // Reinitialize icons after adding new item
                this.$nextTick(() => {
                    lucide.createIcons();
                });
            },

            // Initialize icons and drag & drop when component loads
            init() {
                this.$nextTick(() => {
                    lucide.createIcons();
                    this.initDragAndDrop();
                });
            },


            // Drag and drop functionality
            initDragAndDrop() {
                const container = this.$el.querySelector('[x-ref="itemsContainer"]') || this.$el;
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
                        draggedElement.style.opacity = '0.8';
                        draggedElement.style.transform = 'scale(1.02)';
                        draggedElement.style.transition = 'all 0.2s ease';
                        draggedElement.style.zIndex = '1000';
                    }
                });

                container.addEventListener('dragend', (e) => {
                    if (draggedElement) {
                        // Reset all visual effects
                        draggedElement.style.transform = 'scale(1)';
                        draggedElement.style.opacity = '1';
                        draggedElement.style.zIndex = '';
                        draggedElement.style.transition = 'all 0.2s ease';
                        
                        // Clean up after animation completes
                        setTimeout(() => {
                            if (draggedElement) {
                                draggedElement.style.transition = '';
                                draggedElement = null;
                                draggedIndex = null;
                            }
                        }, 200);
                    }
                });

                container.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    
                    // Simple drop zone feedback
                    const dropTarget = e.target.closest('[data-id]');
                    if (dropTarget && dropTarget !== draggedElement) {
                        dropTarget.style.borderTop = '3px solid #007AFF';
                        dropTarget.style.backgroundColor = '#F2F2F7';
                        dropTarget.style.transform = 'scale(1.01)';
                        dropTarget.style.transition = 'all 0.15s ease';
                    }
                });

                container.addEventListener('dragleave', (e) => {
                    const dropTarget = e.target.closest('[data-id]');
                    if (dropTarget && dropTarget !== draggedElement) {
                        dropTarget.style.borderTop = '';
                        dropTarget.style.backgroundColor = '';
                        dropTarget.style.transform = '';
                        dropTarget.style.transition = '';
                    }
                });

                container.addEventListener('drop', (e) => {
                    e.preventDefault();
                    
                    if (draggedElement && draggedIndex !== null) {
                        const dropTarget = e.target.closest('[data-id]');
                        if (dropTarget && dropTarget !== draggedElement) {
                            const targetIndex = this.items.findIndex(item => item.id == dropTarget.dataset.id);
                            
                            if (targetIndex !== -1 && draggedIndex !== targetIndex) {
                                // Remove item from old position and insert at new position
                                const item = this.items.splice(draggedIndex, 1)[0];
                                this.items.splice(targetIndex, 0, item);
                            }
                        }
                        
                        // Clean up visual feedback
                        if (dropTarget) {
                            dropTarget.style.borderTop = '';
                            dropTarget.style.backgroundColor = '';
                            dropTarget.style.transform = '';
                            dropTarget.style.transition = '';
                        }
                    }
                });
            }
        }));
    });
    </script>
</main>
@endsection