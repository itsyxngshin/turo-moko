@extends('layouts.layout')

@section('title', 'Assignment Submissions')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<main class="p-6" x-data="assignmentSubs()">
    <!-- Header with assignment selector -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm py-6 px-8 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Assignment Submissions</h1>
                <p class="text-gray-500 text-sm mt-1">
                    @if($course)
                        Viewing assignments for: <span class="font-semibold text-gray-700">{{ $course->name ?? $course->course_title ?? 'Course' }}</span>
                    @else
                        Grade assignment submissions
                    @endif
                </p>
            </div>
            <div class="relative">
                <select 
                    x-model="selectedAssignmentId"
                    @change="loadAssignment()"
                    class="block w-64 border border-gray-300 rounded-xl px-4 py-3 pr-10 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white"
                >
                    <option value="">Select an assignment</option>
                    @foreach($assignmentsWithSubs as $assignment)
                        <option value="{{ $assignment['id'] }}">
                            {{ $assignment['title'] }} ({{ $assignment['submissions']->count() }} submissions)
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty state -->
    <div x-show="!currentAssignment && !selectedAssignmentId" class="bg-white rounded-3xl border border-gray-200 shadow-sm py-16 px-8 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Select an Assignment</h2>
            <p class="text-gray-500">Choose an assignment from the dropdown above to view submissions and grades.</p>
        </div>
    </div>

    <!-- Assignment submissions table -->
    <template x-if="currentAssignment">
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900" x-text="currentAssignment.title"></h2>
                <p class="text-sm text-gray-500" x-text="(currentAssignment.submissions?.length || 0) + ' submissions'"></p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submission</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade (/100)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="submission in currentAssignment.submissions" :key="submission.id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-medium text-sm" x-text="(submission.student_name || '').charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900" x-text="submission.student_name"></p>
                                            <p class="text-sm text-gray-500" x-text="submission.email"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500" x-text="submission.submitted_at || '—'"></td>
                                <td class="px-6 py-4 text-sm text-gray-700 space-y-2">
                                    <template x-if="submission.text_submission">
                                        <div class="max-w-xs">
                                            <p class="text-gray-800 whitespace-pre-wrap" x-text="submission.text_submission"></p>
                                        </div>
                                    </template>
                                    <template x-if="submission.attachment_url">
                                        <div>
                                            <a :href="submission.attachment_url" target="_blank" class="text-blue-600 hover:underline">
                                                <span x-text="submission.attachment_name || 'Download'"></span>
                                            </a>
                                        </div>
                                    </template>
                                    <template x-if="!submission.text_submission && !submission.attachment_url">
                                        <span class="text-gray-400">None</span>
                                    </template>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900 font-semibold" x-text="submission.grade !== null ? Number(submission.grade).toFixed(2) : '—'"></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span 
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="submission.grade !== null ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800'"
                                    >
                                        <span x-text="submission.grade !== null ? 'Graded' : 'Not Graded'"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button 
                                        type="button"
                                        @click.prevent="openModal(submission)"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                    >
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        <span>View</span>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div x-show="currentAssignment.submissions.length === 0" class="py-12 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="inbox" class="w-6 h-6 text-gray-400"></i>
                </div>
                <p class="text-gray-500">No submissions yet for this assignment.</p>
            </div>
        </div>
    </template>

    <!-- Grade Modal -->
    <div 
        x-show="modalOpen" 
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
        @click.self="closeModal()"
    >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-y-auto relative">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900" x-text="selected?.student_name || 'Submission'"></h2>
                    <p class="text-sm text-gray-500" x-text="selected?.email || ''"></p>
                </div>
                <button @click="closeModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <div class="text-sm text-gray-500">
                    Submitted: <span class="font-medium text-gray-800" x-text="selected?.submitted_at || '—'"></span>
                </div>

                <div class="border rounded-xl p-4 bg-gray-50 space-y-3">
                    <h3 class="font-semibold text-gray-800">Submission</h3>
                    <template x-if="selected?.text_submission">
                        <div class="text-gray-800 whitespace-pre-wrap" x-text="selected.text_submission"></div>
                    </template>
                    <template x-if="selected?.attachment_url">
                        <div>
                            <a :href="selected.attachment_url" target="_blank" class="text-blue-600 hover:underline">
                                <span x-text="selected.attachment_name || 'Download file'"></span>
                            </a>
                        </div>
                    </template>
                    <template x-if="!selected?.text_submission && !selected?.attachment_url">
                        <p class="text-gray-400">None</p>
                    </template>
                </div>

                <div class="border rounded-xl p-4 space-y-3">
                    <form method="POST" action="{{ route('implementor.assignment-results.grade') }}" class="space-y-3">
                        @csrf
                        <input type="hidden" name="submission_id" :value="selected?.id">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grade (/100)</label>
                            <input type="number" step="0.01" max="100" name="grade" class="w-32 border rounded px-3 py-2"
                                   x-bind:value="selected?.grade ?? ''">
                        </div>
                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="px-5 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    function assignmentSubs() {
        return {
            modalOpen: false,
            selected: null,
            selectedAssignmentId: '',
            currentAssignment: null,

            loadAssignment() {
                const selectedId = this.selectedAssignmentId;
                if (!selectedId) {
                    this.currentAssignment = null;
                    return;
                }

                // assignmentData is injected from blade below
                const found = this.assignments.find(a => String(a.id) === String(selectedId));
                this.currentAssignment = found || null;
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            },

            openModal(sub) {
                this.selected = sub;
                this.modalOpen = true;
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                });
            },
            closeModal() {
                this.modalOpen = false;
                this.selected = null;
            },

            // injected data
            assignments: @json($assignmentsWithSubs ?? [])
        }
    }
</script>
@endsection

