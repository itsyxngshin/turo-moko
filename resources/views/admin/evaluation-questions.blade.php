@extends('layouts.layout')

@section('title', 'Manage Evaluation Questions')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<main x-data="evaluationQuestionsManager">
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm py-10 px-10 ml-4 mt-2 flex flex-col gap-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-2">
            <h2 class="text-xl font-bold">Evaluation Questions</h2>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button
                    @click="activeTab = 'course_evaluation'"
                    :class="activeTab === 'course_evaluation' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    Course Evaluation
                </button>
                <button
                    @click="activeTab = 'implementor_evaluation'"
                    :class="activeTab === 'implementor_evaluation' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                >
                    Implementor Evaluation
                </button>
            </nav>
        </div>

        <!-- Course Evaluation Questions -->
        <div x-show="activeTab === 'course_evaluation'" x-cloak>
            <div class="space-y-6" x-ref="programRatingsContainer">
                <template x-for="(item, index) in programRatings" :key="item.id">
                    <div :data-id="item.id" draggable="true" class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200">
                        <div class="flex gap-2">
                            <button type="button" @click="deleteQuestion('program_rating', item.id)">
                                <i data-lucide="trash" class="w-6 h-6 text-gray-600"></i>
                            </button>
                            <button type="button" @click="addItemAfter('program_rating', index)">
                                <i data-lucide="plus" class="w-6 h-6 text-gray-600"></i>
                            </button>
                            <div class="drag-handle cursor-move">
                                <i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input 
                                type="text" 
                                placeholder="Enter rating question..." 
                                class="text-xl font-semibold mb-4 w-full min-w-0 border-none outline-none" 
                                x-model="item.statement"
                            />
                            <div class="flex gap-1">
                                <template x-for="i in 5" :key="i">
                                    <i data-lucide="star" class="w-8 h-8 text-gray-300"></i>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-8 space-y-6" x-ref="programCommentsContainer">
                <template x-for="(item, index) in programComments" :key="item.id">
                    <div :data-id="item.id" draggable="true" class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200">
                        <div class="flex gap-2">
                            <button type="button" @click="deleteQuestion('program_comment', item.id)">
                                <i data-lucide="trash" class="w-6 h-6 text-gray-600"></i>
                            </button>
                            <button type="button" @click="addItemAfter('program_comment', index)">
                                <i data-lucide="plus" class="w-6 h-6 text-gray-600"></i>
                            </button>
                            <div class="drag-handle cursor-move">
                                <i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input 
                                type="text" 
                                placeholder="Enter feedback question..." 
                                class="text-xl font-semibold mb-4 w-full min-w-0 border-none outline-none" 
                                x-model="item.description"
                            />
                            <textarea 
                                placeholder="Your feedback..." 
                                class="w-full border rounded-lg p-3 min-h-[80px] bg-gray-50 text-gray-400" 
                                rows="3"
                                disabled
                            ></textarea>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Add First Item Button -->
            <div x-show="programRatings.length === 0 && programComments.length === 0" class="text-center py-12">
                <button 
                    type="button" 
                    @click="addItemModalOpen = true"
                    class="px-8 py-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-gray-400 hover:text-gray-800 transition-colors"
                >
                    <i data-lucide="plus" class="w-6 h-6 inline mr-2"></i>
                    Add your first question
                </button>
            </div>
        </div>

        <!-- Implementor Evaluation Questions -->
        <div x-show="activeTab === 'implementor_evaluation'" x-cloak>
            <div class="space-y-6" x-ref="implementerRatingsContainer">
                <template x-for="(item, index) in implementerRatings" :key="item.id">
                    <div :data-id="item.id" draggable="true" class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200">
                        <div class="flex gap-2">
                            <button type="button" @click="deleteQuestion('implementer_rating', item.id)">
                                <i data-lucide="trash" class="w-6 h-6 text-gray-600"></i>
                            </button>
                            <button type="button" @click="addItemAfter('implementer_rating', index)">
                                <i data-lucide="plus" class="w-6 h-6 text-gray-600"></i>
                            </button>
                            <div class="drag-handle cursor-move">
                                <i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input 
                                type="text" 
                                placeholder="Enter rating question..." 
                                class="text-xl font-semibold mb-4 w-full min-w-0 border-none outline-none" 
                                x-model="item.statement"
                            />
                            <div class="flex gap-1">
                                <template x-for="i in 5" :key="i">
                                    <i data-lucide="star" class="w-8 h-8 text-gray-300"></i>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="mt-8 space-y-6" x-ref="implementerCommentsContainer">
                <template x-for="(item, index) in implementerComments" :key="item.id">
                    <div :data-id="item.id" draggable="true" class="flex items-start gap-4 p-4 border border-gray-200 rounded-lg hover:border-gray-300 transition-all duration-200">
                        <div class="flex gap-2">
                            <button type="button" @click="deleteQuestion('implementer_comment', item.id)">
                                <i data-lucide="trash" class="w-6 h-6 text-gray-600"></i>
                            </button>
                            <button type="button" @click="addItemAfter('implementer_comment', index)">
                                <i data-lucide="plus" class="w-6 h-6 text-gray-600"></i>
                            </button>
                            <div class="drag-handle cursor-move">
                                <i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <input 
                                type="text" 
                                placeholder="Enter feedback question..." 
                                class="text-xl font-semibold mb-4 w-full min-w-0 border-none outline-none" 
                                x-model="item.description"
                            />
                            <textarea 
                                placeholder="Your feedback..." 
                                class="w-full border rounded-lg p-3 min-h-[80px] bg-gray-50 text-gray-400" 
                                rows="3"
                                disabled
                            ></textarea>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Add First Item Button -->
            <div x-show="implementerRatings.length === 0 && implementerComments.length === 0" class="text-center py-12">
                <button 
                    type="button" 
                    @click="addItemModalOpen = true"
                    class="px-8 py-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-gray-400 hover:text-gray-800 transition-colors"
                >
                    <i data-lucide="plus" class="w-6 h-6 inline mr-2"></i>
                    Add your first question
                </button>
            </div>
        </div>

    </div>

    <!-- Action Buttons (Assessment Builder Style) -->
    <div class="flex justify-center gap-4 mt-8 pt-6 border-t border-gray-200">
        <button 
            @click="publishQuestions()"
            class="px-8 py-3 bg-black hover:bg-gray-800 text-white rounded-lg font-medium transition-colors"
        >
            Publish
        </button>
    </div>

    <!-- Add Anything Modal -->
    <div x-show="addItemModalOpen" x-transition x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Add anything</h2>
                <button type="button" @click="addItemModalOpen = false">
                    <i data-lucide="x" class="w-6 h-6 text-gray-600"></i>
                </button>
            </div>

            <hr class="border-t border-gray-200 w-full mb-4">

            <div class="grid grid-cols-2 gap-3">
                <button @click="addNewQuestion('rating')" class="border border-gray-300 rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 p-4 h-28 transition-colors">
                    <i data-lucide="star" class="w-10 h-10 mb-2 text-gray-700"></i>
                    <span class="text-sm text-gray-700 font-medium text-center">Star Rating</span>
                </button>
                <button @click="addNewQuestion('comment')" class="border border-gray-300 rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 p-4 h-28 transition-colors">
                    <i data-lucide="message-square" class="w-10 h-10 mb-2 text-gray-700"></i>
                    <span class="text-sm text-gray-700 font-medium text-center">Feedback Field</span>
                </button>
            </div>
        </div>
    </div>


</main>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('evaluationQuestionsManager', () => ({
        
        // Data from backend
        programRatings: @json($programRatings),
        programComments: @json($programComments),
        implementerRatings: @json($implementerRatings),
        implementerComments: @json($implementerComments),
        
        init() {
            // Restore active tab from localStorage if available
            const savedTab = localStorage.getItem('evaluationActiveTab');
            if (savedTab) {
                this.activeTab = savedTab;
                localStorage.removeItem('evaluationActiveTab');
            }
            
            this.$nextTick(() => {
                this.initDragAndDrop();
                this.reinitializeIcons();
            });
        },
        
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
        
        activeTab: 'course_evaluation',
        addItemModalOpen: false,
        insertAfterIndex: null,
        insertAfterType: null,
        
        addItemAfter(type, index) {
            this.insertAfterIndex = index;
            this.insertAfterType = type;
            this.addItemModalOpen = true;
        },
        
        addNewQuestion(questionType) {
            this.addItemModalOpen = false;
            
            let type = '';
            let dataProperty = '';
            
            if (this.activeTab === 'course_evaluation') {
                if (questionType === 'rating') {
                    type = 'program_rating';
                    dataProperty = 'programRatings';
                } else {
                    type = 'program_comment';
                    dataProperty = 'programComments';
                }
            } else if (this.activeTab === 'implementor_evaluation') {
                if (questionType === 'rating') {
                    type = 'implementer_rating';
                    dataProperty = 'implementerRatings';
                } else {
                    type = 'implementer_comment';
                    dataProperty = 'implementerComments';
                }
            }
            
            const newItem = {
                id: Date.now(),
                type: type,
                text: '',
                // Keep old fields for backward compatibility with frontend bindings
                [questionType === 'rating' ? 'statement' : 'description']: '',
                status: 'active',
                order: this[dataProperty].length + 1
            };
            
            // Insert after the clicked item if insertAfterIndex is set
            if (this.insertAfterIndex !== null && this.insertAfterType === type) {
                this[dataProperty].splice(this.insertAfterIndex + 1, 0, newItem);
                // Update order for all items
                this[dataProperty].forEach((item, idx) => {
                    item.order = idx + 1;
                });
            } else {
                this[dataProperty].push(newItem);
            }
            
            // Reset insert tracking
            this.insertAfterIndex = null;
            this.insertAfterType = null;
            
            this.reinitializeIcons();
        },
        
        async publishQuestions() {
            const allQuestions = [
                ...this.programRatings.map(q => ({ ...q, type: 'program_rating', text: q.statement })),
                ...this.programComments.map(q => ({ ...q, type: 'program_comment', text: q.description })),
                ...this.implementerRatings.map(q => ({ ...q, type: 'implementer_rating', text: q.statement })),
                ...this.implementerComments.map(q => ({ ...q, type: 'implementer_comment', text: q.description }))
            ];

            try {
                const response = await fetch('/admin/evaluation-questions/publish', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ questions: allQuestions })
                });

                const data = await response.json();

                if (data.success) {
                    alert('Questions published successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to publish questions'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to publish questions');
            }
        },
        
        getDataProperty(type) {
            const map = {
                'program_rating': 'programRatings',
                'program_comment': 'programComments',
                'implementer_rating': 'implementerRatings',
                'implementer_comment': 'implementerComments'
            };
            return map[type];
        },
        
        async saveQuestion(type, item) {
            // Sync text field with old fields
            const text = item.statement || item.description;
            if (!text) return;
            
            const method = item.id > 1000000000 ? 'POST' : 'PUT';
            const url = method === 'POST' ? '/admin/evaluation-questions' : `/admin/evaluation-questions/${item.id}`;
            
            const payload = {
                type: type,
                text: text,
                order: parseInt(item.order)
            };
            
            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });
                
                const data = await response.json();
                
                if (data.success && method === 'POST' && data.data) {
                    item.id = data.data.id;
                } else if (!data.success) {
                    console.error('Failed to save question');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        },
        
        deleteQuestion(type, id) {
            if (!confirm('Are you sure you want to delete this question?')) {
                return;
            }
            
            const dataProperty = this.getDataProperty(type);
            const index = this[dataProperty].findIndex(item => item.id == id);
            
            if (index !== -1) {
                this[dataProperty].splice(index, 1);
                // Reorder remaining items
                this[dataProperty].forEach((item, idx) => {
                    item.order = idx + 1;
                });
            }
        },
        
        async updateItemOrder(type, id, newOrder) {
            try {
                const response = await fetch(`/admin/evaluation-questions/update-order`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        type: type,
                        id: id,
                        order: parseInt(newOrder)
                    })
                });
                
                const data = await response.json();
                if (!data.success) {
                    console.error('Failed to update order');
                }
            } catch (error) {
                console.error('Error updating order:', error);
            }
        },
        
        initDragAndDrop() {
            try {
                const containers = [
                    this.$refs.programRatingsContainer,
                    this.$refs.programCommentsContainer,
                    this.$refs.implementerRatingsContainer,
                    this.$refs.implementerCommentsContainer
                ];
                
                containers.forEach(container => {
                    if (!container) return;
                    
                    let draggedElement = null;
                    let draggedId = null;
                    let containerType = null;
                    
                    container.addEventListener('dragstart', (e) => {
                        draggedElement = e.target.closest('[data-id]');
                        if (draggedElement) {
                            draggedId = draggedElement.dataset.id;
                            
                            // Determine container type
                            if (container === this.$refs.programRatingsContainer) containerType = 'programRatings';
                            else if (container === this.$refs.programCommentsContainer) containerType = 'programComments';
                            else if (container === this.$refs.implementerRatingsContainer) containerType = 'implementerRatings';
                            else if (container === this.$refs.implementerCommentsContainer) containerType = 'implementerComments';
                            
                            e.dataTransfer.effectAllowed = 'move';
                            e.dataTransfer.setData('text/html', draggedElement.outerHTML);
                            this.applyDragStyles(draggedElement);
                        }
                    });
                    
                    container.addEventListener('dragend', (e) => {
                        if (draggedElement) {
                            this.removeDragStyles(draggedElement);
                            setTimeout(() => {
                                draggedElement = null;
                                draggedId = null;
                                containerType = null;
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
                        if (draggedElement && draggedId && containerType) {
                            const dropTarget = e.target.closest('[data-id]');
                            if (dropTarget && dropTarget !== draggedElement) {
                                const draggedIndex = this[containerType].findIndex(item => item.id == draggedId);
                                const targetIndex = this[containerType].findIndex(item => item.id == dropTarget.dataset.id);
                                
                                if (draggedIndex !== -1 && targetIndex !== -1 && draggedIndex !== targetIndex) {
                                    // Remove dragged item
                                    const [draggedItem] = this[containerType].splice(draggedIndex, 1);
                                    
                                    // Insert at new position
                                    this[containerType].splice(targetIndex, 0, draggedItem);
                                    
                                    // Update order for all items
                                    this[containerType].forEach((item, index) => {
                                        item.order = index + 1;
                                    });
                                    
                                    // Force Alpine to detect change
                                    this.$nextTick(() => {
                                        this.reinitializeIcons();
                                    });
                                }
                            }
                            if (dropTarget) {
                                this.removeDropZoneStyles(dropTarget);
                            }
                        }
                    });
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

@endsection
