@extends('layouts.layout')

@section('title', 'Assessment Results')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    [x-cloak] { display: none !important; }
</style>

<main x-data="assessmentResults()" x-init="init()" @keydown.escape.window="handleEscape()">
    <!-- Back to Course Button -->
    @if($course)
        <div class="mb-4">
            <a href="{{ route('implementor.course-information', $course->course_code) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Course
            </a>
        </div>
    @endif

    <!-- Header Section -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm py-6 px-8 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Assessment Results</h1>
                <p class="text-gray-500 text-sm mt-1">
                    @if($course)
                        Viewing assessments for: <span class="font-semibold text-gray-700">{{ $course->name ?? $course->course_title ?? 'Course' }}</span>
                    @else
                        View and grade student submissions
                    @endif
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Quiz Selector -->
                <div class="relative">
                    <select 
                        x-model="selectedQuizId" 
                        @change="loadQuizResults()"
                        class="block w-64 border border-gray-300 rounded-xl px-4 py-3 pr-10 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white"
                    >
                        <option value="">Select an assessment</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz['id'] }}">
                                {{ $quiz['title'] }} ({{ $quiz['total_submissions'] }} responses)
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i data-lucide="chevron-down" class="w-5 h-5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Export Button -->
                <button 
                    x-show="selectedQuizId && !loading"
                    @click="exportCSV()"
                    class="flex items-center gap-2 px-4 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl transition-colors"
                >
                    <i data-lucide="download" class="w-5 h-5"></i>
                    <span>Export CSV</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div x-show="!selectedQuizId && !loading" class="bg-white rounded-3xl border border-gray-200 shadow-sm py-16 px-8 text-center">
        <div class="max-w-md mx-auto">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="clipboard-list" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Select an Assessment</h2>
            <p class="text-gray-500">Choose an assessment from the dropdown above to view student submissions and grades.</p>
        </div>
    </div>

    <!-- Loading State -->
    <div x-show="loading" x-cloak class="bg-white rounded-3xl border border-gray-200 shadow-sm py-16 px-8 text-center">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-500">Loading results...</p>
        </div>
    </div>

    <!-- Results Content -->
    <div x-show="selectedQuizId && !loading && quizData" x-cloak>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
            <!-- Completion Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1">Completion</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-2xl font-bold text-gray-900" x-text="formatRatio(quizData.stats.total_submissions, quizData.stats.enrolled_count)"></p>
                            <span class="text-sm text-gray-500" x-text="quizData.stats.enrolled_count ? formatPercent(quizData.stats.completion_rate) : '—'"></span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1" x-text="quizData.stats.enrolled_count ? 'Enrolled learners' : 'Enrollment data unavailable'"></p>
                    </div>
                </div>
            </div>

            <!-- Average Score Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="bar-chart-3" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1">Average Score</p>
                        <p class="text-3xl font-bold text-gray-900" x-text="formatPercent(quizData.stats.average_percentage)"></p>
                        <p class="text-xs text-gray-500 mt-1" x-text="'Avg raw: ' + formatDecimal(quizData.stats.average_score) + '/' + quizData.stats.total_points"></p>
                    </div>
                </div>
            </div>

            <!-- Pass Rate Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="trending-up" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1" x-text="'Pass Rate (≥ ' + quizData.stats.pass_threshold + '%)'"></p>
                        <p class="text-3xl font-bold text-gray-900" x-text="quizData.stats.graded_count ? formatPercent(quizData.stats.pass_rate) : '—'"></p>
                        <p class="text-xs text-gray-500 mt-1" x-text="quizData.stats.graded_count ? (quizData.stats.pass_count + ' passed · ' + quizData.stats.fail_count + ' failed') : 'No graded submissions yet'"></p>
                    </div>
                </div>
            </div>

            <!-- Needs Grading Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                         :class="quizData.stats.pending_count > 0 ? 'bg-amber-100' : 'bg-gray-100'">
                        <i data-lucide="edit-3" class="w-6 h-6" 
                           :class="quizData.stats.pending_count > 0 ? 'text-amber-600' : 'text-gray-400'"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1">Needs Grading</p>
                        <p class="text-3xl font-bold" 
                           :class="quizData.stats.pending_count > 0 ? 'text-amber-600' : 'text-gray-900'"
                           x-text="quizData.stats.pending_count">0</p>
                        <p class="text-xs text-gray-500 mt-1">Essay/short answers awaiting review</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Charts -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4 gap-4 flex-wrap">
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-lg font-semibold text-gray-900">Question Performance</h2>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-blue-50 text-blue-600 font-medium"
                                  x-text="questionFilterMode === 'problem' ? problemQuestionCount() + ' focus' : totalQuestionCount() + ' total'"></span>
                        </div>
                        <p class="text-sm text-gray-500" 
                           x-text="questionFilterMode === 'problem' ? 'Showing questions with < 70% correct' : 'Showing all questions'"></p>
                        <p class="text-xs text-gray-400 mt-1" x-show="isProblemChartLimited()" x-cloak>
                            Showing top 5 most challenging questions.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs text-gray-400" x-text="hasQuestionStats() ? 'Based on graded responses' : ''"></span>
                        <button 
                            @click="toggleQuestionFilterMode()"
                            class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-full text-xs font-medium text-gray-600 hover:text-blue-600 hover:border-blue-300 transition-colors"
                        >
                            <i data-lucide="filter" class="w-4 h-4"></i>
                            <span x-text="questionFilterMode === 'problem' ? 'Show all questions' : 'Focus on problems'"></span>
                        </button>
                        <button 
                            @click="openQuestionModal()"
                            :disabled="!hasQuestionStats()"
                            class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-full text-xs font-medium"
                            :class="hasQuestionStats() ? 'text-gray-600 hover:text-blue-600 hover:border-blue-300 transition-colors' : 'text-gray-400 cursor-not-allowed bg-gray-50'"
                        >
                            <i data-lucide="list" class="w-4 h-4"></i>
                            <span>View all</span>
                        </button>
                    </div>
                </div>
                <div class="relative">
                    <canvas x-ref="questionChart" class="w-full h-72"></canvas>
                    <div x-show="!hasQuestionStats()" x-cloak class="absolute inset-0 flex items-center justify-center text-sm text-gray-500 bg-white/80 rounded-2xl">
                        Not enough graded responses yet.
                    </div>
                    <div x-show="questionFilterMode === 'problem' && !hasProblemQuestions() && hasQuestionStats()" x-cloak class="absolute inset-0 flex flex-col items-center justify-center text-center text-sm text-green-700 bg-white/90 rounded-2xl px-6">
                        <i data-lucide="smile" class="w-6 h-6 mb-2 text-green-500"></i>
                        <p class="font-semibold">Great job!</p>
                        <p>All questions are above 70% correct. Switch to \"Show all\" to see the full breakdown.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Score Distribution</h2>
                        <p class="text-sm text-gray-500">Breakdown of graded submissions</p>
                    </div>
                    <span class="text-xs text-gray-400" x-text="quizData.stats.graded_count + ' graded'"></span>
                </div>
                <div class="relative">
                    <canvas x-ref="scoreChart" class="w-full h-72"></canvas>
                    <div x-show="!hasScoreDistribution()" x-cloak class="absolute inset-0 flex items-center justify-center text-sm text-gray-500 bg-white/80 rounded-2xl">
                        No graded submissions yet.
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <template x-for="item in scoreLegend" :key="item.key">
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full" :style="`background:${item.color}`"></span>
                                <span class="text-gray-600" x-text="item.label"></span>
                            </div>
                            <span class="font-semibold text-gray-900" x-text="(quizData.score_distribution?.[item.key] || 0)"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Student Submissions Table -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Student Submissions</h2>
                
                <!-- Sort Toggle -->
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600">Sort by:</span>
                    <div class="inline-flex rounded-lg border border-gray-300 p-1 bg-gray-50">
                        <button 
                            @click="toggleSort('name')"
                            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors"
                            :class="sortBy === 'name' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        >
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                <span>Name</span>
                            </div>
                        </button>
                        <button 
                            @click="toggleSort('date')"
                            class="px-3 py-1.5 text-sm font-medium rounded-md transition-colors"
                            :class="sortBy === 'date' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                        >
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                <span>Date</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <template x-for="(submission, index) in quizData.submissions" :key="submission.course_enrollee_id">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-medium text-sm" x-text="submission.student_name.charAt(0).toUpperCase()"></span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900" x-text="submission.student_name"></p>
                                            <p class="text-sm text-gray-500" x-text="submission.email"></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm" :class="submission.has_submission ? 'text-gray-500' : 'text-gray-400'" x-text="submission.submitted_at || '—'"></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold" :class="submission.has_submission ? 'text-gray-900' : 'text-red-600'" x-text="submission.score + '/' + submission.total_points"></span>
                                        <span class="text-sm" :class="submission.has_submission ? 'text-gray-500' : 'text-red-500'" x-text="'(' + submission.percentage + '%)'"></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span 
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-green-100 text-green-800': submission.status === 'Checked',
                                            'bg-amber-100 text-amber-800': submission.status === 'Pending' || submission.has_ungraded,
                                            'bg-red-100 text-red-800': submission.status === 'Not Submitted'
                                        }"
                                    >
                                        <span x-text="submission.status === 'Checked' ? 'Graded' : (submission.status === 'Pending' || submission.has_ungraded ? 'Needs Review' : 'No Submission')"></span>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button 
                                        x-show="submission.has_submission"
                                        @click="openModal(submission, index)"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                    >
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        <span>View</span>
                                    </button>
                                    <span x-show="!submission.has_submission" class="text-sm text-gray-400">—</span>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Empty submissions state -->
            <div x-show="quizData && quizData.submissions.length === 0" class="py-12 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="inbox" class="w-6 h-6 text-gray-400"></i>
                </div>
                <p class="text-gray-500">No submissions yet for this assessment.</p>
            </div>
        </div>
    </div>

    <!-- Question Details Modal -->
    <div 
        x-show="questionModalOpen" 
        x-cloak
        class="fixed inset-0 z-40 overflow-y-auto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div class="fixed inset-0 bg-black bg-opacity-40" @click="closeQuestionModal()"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div 
                x-show="questionModalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[85vh] overflow-hidden"
                @click.stop
            >
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">All Question Stats</h2>
                        <p class="text-sm text-gray-500" x-text="totalQuestionCount() + ' questions · ' + (quizData?.stats?.graded_count || 0) + ' graded submissions'"></p>
                    </div>
                    <button @click="closeQuestionModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
                <div class="overflow-y-auto max-h-[calc(85vh-80px)]">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Type</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Attempts</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-32">% Correct</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" x-show="hasQuestionStats()" x-cloak>
                            <template x-for="stat in getQuestionStatsRaw().sort((a,b) => a.question_number - b.question_number)" :key="'modal-question-' + stat.question_id">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-500 font-semibold" x-text="'Q' + stat.question_number"></td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-medium text-gray-900" x-text="stat.question_text"></p>
                                        <p class="text-xs text-gray-500 mt-1" x-text="'Worth ' + stat.points + ' pts'"></p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 capitalize" x-text="stat.type.replace('_', ' ')"></td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-600" x-text="stat.graded_attempts"></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-semibold"
                                              :class="stat.percent_correct >= 80 ? 'bg-green-100 text-green-700' : stat.percent_correct >= 50 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700'">
                                            <span x-text="formatPercent(stat.percent_correct)"></span>
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                        <tbody x-show="!hasQuestionStats()" x-cloak>
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                    No graded responses yet. Grade at least one submission to see analytics.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Submission Modal -->
    <div 
        x-show="modalOpen" 
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @keydown.arrow-left.window="hasPreviousSubmission() && navigateToPrevious()"
        @keydown.arrow-right.window="hasNextSubmission() && navigateToNext()"
        @keydown.escape.window="closeModal()"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Left Navigation Zone -->
        <div 
            x-show="hasPreviousSubmission()"
            @click="navigateToPrevious()"
            class="absolute left-0 top-0 bottom-0 w-24 flex items-center justify-start pl-4 cursor-pointer group z-20 hover:bg-black hover:bg-opacity-10 transition-all"
            title="Previous submission"
        >
            <div class="w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <i data-lucide="chevron-left" class="w-6 h-6 text-gray-700"></i>
            </div>
        </div>
        
        <!-- Right Navigation Zone -->
        <div 
            x-show="hasNextSubmission()"
            @click="navigateToNext()"
            class="absolute right-0 top-0 bottom-0 w-24 flex items-center justify-end pr-4 cursor-pointer group z-20 hover:bg-black hover:bg-opacity-10 transition-all"
            title="Next submission"
        >
            <div class="w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <i data-lucide="chevron-right" class="w-6 h-6 text-gray-700"></i>
            </div>
        </div>
        
        <!-- Modal Content -->
        <div class="flex min-h-screen items-center justify-center p-4">
            <div 
                x-show="modalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-3xl max-h-[85vh] overflow-hidden flex flex-col"
                @click.stop
            >
                <!-- Modal Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <h2 class="text-xl font-bold text-gray-900" x-text="selectedSubmission?.student_name + '\\'s Submission'"></h2>
                            <span x-show="currentSubmissionIndex >= 0" class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 font-medium">
                                <span x-text="currentSubmissionIndex + 1"></span> of <span x-text="quizData?.submissions.filter(s => s.has_submission).length"></span>
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Score: <span class="font-semibold" x-text="selectedSubmission?.score + '/' + selectedSubmission?.total_points"></span>
                            (<span x-text="selectedSubmission?.percentage + '%'"></span>)
                            <span class="mx-2">•</span>
                            No. of attempts: <span class="font-semibold" x-text="selectedSubmission?.total_attempts || 1"></span>
                        </p>
                    </div>
                    <button @click="closeModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                        <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                    </button>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto max-h-[calc(85vh-80px)]">
                    <div class="space-y-4">
                        <template x-for="(answer, answerIndex) in selectedSubmission?.answers" :key="answer.id">
                            <div class="bg-gray-50 rounded-xl border border-gray-200 p-4">
                                <!-- Question Header -->
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-medium text-gray-500" x-text="'Q' + (answerIndex + 1)"></span>
                                        <span 
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium"
                                            :class="{
                                                'bg-blue-100 text-blue-700': answer.question_type === 'multiple_choice',
                                                'bg-purple-100 text-purple-700': answer.question_type === 'true_false',
                                                'bg-teal-100 text-teal-700': answer.question_type === 'short_answer',
                                                'bg-indigo-100 text-indigo-700': answer.question_type === 'long_answer'
                                            }"
                                            x-text="answer.question_type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())"
                                        ></span>
                                    </div>
                                    <span class="text-sm text-gray-500" x-text="answer.question_points + ' pts'"></span>
                                </div>
                                
                                <!-- Question Text -->
                                <p class="font-medium text-gray-900 mb-3" x-text="answer.question_text"></p>
                                
                                <!-- Answer Content -->
                                <div class="space-y-2">
                                    <!-- For MC/TF - Show answer with correctness -->
                                    <template x-if="['multiple_choice', 'true_false'].includes(answer.question_type)">
                                        <div>
                                            <div class="flex items-center gap-2 p-3 rounded-lg bg-white"
                                                 :class="answer.is_correct ? 'border-2 border-green-300' : 'border-2 border-red-300'">
                                                <i :data-lucide="answer.is_correct ? 'check-circle' : 'x-circle'" 
                                                   class="w-5 h-5"
                                                   :class="answer.is_correct ? 'text-green-600' : 'text-red-600'"></i>
                                                <span class="font-medium" x-text="answer.answer_text || answer.choice_text"></span>
                                                <span class="ml-auto text-sm font-semibold"
                                                      :class="answer.is_correct ? 'text-green-600' : 'text-red-600'"
                                                      x-text="answer.points_earned + '/' + answer.question_points"></span>
                                            </div>
                                            <template x-if="!answer.is_correct && answer.correct_answer">
                                                <p class="text-sm text-gray-500 mt-2">
                                                    <span class="font-medium">Correct answer:</span> 
                                                    <span x-text="answer.correct_answer"></span>
                                                </p>
                                            </template>
                                        </div>
                                    </template>
                                    
                                    <!-- For Short Answer - Show answer with correctness -->
                                    <template x-if="['short_answer'].includes(answer.question_type)">
                                        <div>
                                            <!-- Student's Answer -->
                                            <div class="bg-white rounded-lg p-3 mb-3 border border-gray-200">
                                                <p class="text-sm font-medium text-gray-500 mb-1">Student's Answer:</p>
                                                <p class="text-gray-900" x-text="answer.answer_text || 'No answer provided'"></p>
                                            </div>
                                            
                                            <!-- Model Answer (if exists) -->
                                            <template x-if="answer.correct_answer || answer.model_answer">
                                                <div class="bg-blue-50 rounded-lg p-3 mb-3 border border-blue-200">
                                                    <p class="text-sm font-medium text-blue-700 mb-1">Correct Answer:</p>
                                                    <p class="text-blue-900" x-text="answer.correct_answer || answer.model_answer"></p>
                                                </div>
                                            </template>
                                            
                                            <!-- Grading Status -->
                                            <div class="flex items-center gap-2 p-3 rounded-lg bg-white"
                                                 :class="answer.is_correct ? 'border-2 border-green-300' : answer.points_earned < 0 ? 'border-2 border-amber-300' : 'border-2 border-red-300'">
                                                <i :data-lucide="answer.is_correct ? 'check-circle' : answer.points_earned < 0 ? 'alert-circle' : 'x-circle'" 
                                                   class="w-5 h-5"
                                                   :class="answer.is_correct ? 'text-green-600' : answer.points_earned < 0 ? 'text-amber-600' : 'text-red-600'"></i>
                                                <span class="font-medium" 
                                                      :class="answer.is_correct ? 'text-green-600' : answer.points_earned < 0 ? 'text-amber-600' : 'text-red-600'"
                                                      x-text="answer.is_correct ? 'Correct' : answer.points_earned < 0 ? 'Needs Manual Grading' : 'Incorrect'"></span>
                                                <span class="ml-auto text-sm font-semibold"
                                                      :class="answer.is_correct ? 'text-green-600' : answer.points_earned < 0 ? 'text-amber-600' : 'text-red-600'"
                                                      x-text="answer.points_earned >= 0 ? answer.points_earned + '/' + answer.question_points : 'Ungraded'"></span>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <!-- For Long Answer - Show answer with grading interface -->
                                    <template x-if="['long_answer'].includes(answer.question_type)">
                                        <div>
                                            <!-- Student's Answer -->
                                            <div class="bg-white rounded-lg p-3 mb-3 border border-gray-200">
                                                <p class="text-sm font-medium text-gray-500 mb-1">Student's Answer:</p>
                                                <p class="text-gray-900 whitespace-pre-wrap" x-text="answer.answer_text || 'No answer provided'"></p>
                                            </div>
                                            
                                            <!-- Model Answer (if exists) -->
                                            <template x-if="answer.model_answer">
                                                <div class="bg-blue-50 rounded-lg p-3 mb-3 border border-blue-200">
                                                    <p class="text-sm font-medium text-blue-700 mb-1">Model Answer:</p>
                                                    <p class="text-blue-900 whitespace-pre-wrap" x-text="answer.model_answer"></p>
                                                </div>
                                            </template>
                                            
                                            <!-- Grading Interface -->
                                            <div class="flex items-center gap-3 mt-3 p-3 bg-white rounded-lg border border-gray-200">
                                                <template x-if="answer.needs_grading">
                                                    <div class="flex items-center gap-2 w-full">
                                                        <label class="text-sm font-medium text-gray-700">Points:</label>
                                                        <input 
                                                            type="number" 
                                                            :id="'modal-points-' + answer.id"
                                                            :max="answer.question_points"
                                                            min="0"
                                                            step="0.5"
                                                            class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="0"
                                                        >
                                                        <span class="text-sm text-gray-500" x-text="'/ ' + answer.question_points"></span>
                                                        <button 
                                                            @click="saveGradeModal(answer)"
                                                            class="ml-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors"
                                                        >
                                                            Save Grade
                                                        </button>
                                                    </div>
                                                </template>
                                                <template x-if="!answer.needs_grading">
                                                    <div class="flex items-center gap-2 w-full">
                                                        <span class="text-sm text-gray-500">Grade:</span>
                                                        <span class="font-semibold text-green-600" x-text="answer.points_earned + '/' + answer.question_points"></span>
                                                        <button 
                                                            @click="enableRegrade(answer)"
                                                            class="ml-auto text-sm text-blue-600 hover:text-blue-800 underline"
                                                        >
                                                            Re-grade
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Modal Footer with Navigation -->
                <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between">
                    <!-- Navigation Buttons -->
                    <div class="flex gap-2">
                        <button 
                            x-show="hasPreviousSubmission()"
                            @click="navigateToPrevious()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                            title="Previous submission (Left Arrow)"
                        >
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                            <span>Previous</span>
                        </button>
                        
                        <button 
                            x-show="hasNextSubmission()"
                            @click="navigateToNext()"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                            title="Next submission (Right Arrow)"
                        >
                            <span>Next</span>
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                    
                    <!-- Close Button -->
                    <button 
                        @click="closeModal()"
                        class="px-6 py-2 text-sm font-medium text-white bg-gray-800 rounded-lg hover:bg-gray-900 transition-colors"
                    >
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div 
        x-show="toast.show" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-6 right-6 z-50"
    >
        <div 
            class="flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg"
            :class="toast.type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'"
        >
            <i :data-lucide="toast.type === 'success' ? 'check-circle' : 'alert-circle'" class="w-5 h-5"></i>
            <span x-text="toast.message"></span>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('assessmentResults', () => ({
        selectedQuizId: '{{ request()->get("quiz_id") ?? "" }}',
        loading: false,
        quizData: null,
        modalOpen: false,
        questionModalOpen: false,
        selectedSubmission: null,
        currentSubmissionIndex: -1,
        sortBy: 'name', // 'name' or 'date'
        _preserveModal: false,
        _scrollLockCount: 0,
        questionChart: null,
        scoreChart: null,
        scoreLegend: [
            { key: 'excellent', label: 'Excellent (90-100%)', color: '#22c55e' },
            { key: 'good', label: 'Good (70-89%)', color: '#3b82f6' },
            { key: 'needs_improvement', label: 'Needs Work (60-69%)', color: '#fbbf24' },
            { key: 'fail', label: 'Fail (<60%)', color: '#f87171' },
        ],
        questionFilterMode: 'problem',
        toast: {
            show: false,
            message: '',
            type: 'success'
        },

        init() {
            // Reinitialize icons after Alpine renders
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
            
            // Auto-load quiz if quiz_id is in URL
            if (this.selectedQuizId) {
                this.loadQuizResults();
            }
        },

        async loadQuizResults() {
            if (!this.selectedQuizId) {
                this.quizData = null;
                this.destroyCharts();
                return;
            }

            this.loading = true;
            // Only close modal if not preserving it (e.g., after grading)
            if (!this._preserveModal) {
                this.closeModal();
            }
            this._preserveModal = false;

            try {
                const response = await fetch(`/implementor/assessment-results/${this.selectedQuizId}`);
                if (!response.ok) throw new Error('Failed to load results');
                
                this.quizData = await response.json();
                
                // Sort submissions after loading
                this.sortSubmissions();
                
                this.$nextTick(() => {
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                    this.renderCharts();
                });
            } catch (error) {
                console.error('Error loading results:', error);
                this.showToast('Failed to load assessment results', 'error');
                this.destroyCharts();
            } finally {
                this.loading = false;
            }
        },
        
        toggleSort(sortBy) {
            this.sortBy = sortBy;
            this.sortSubmissions();
        },
        
        sortSubmissions() {
            if (!this.quizData || !this.quizData.submissions) return;
            
            const submissions = this.quizData.submissions;
            
            // Separate into three groups
            const ungraded = submissions.filter(s => s.has_submission && (s.status === 'Pending' || s.has_ungraded));
            const graded = submissions.filter(s => s.has_submission && s.status === 'Checked' && !s.has_ungraded);
            const noSubmission = submissions.filter(s => !s.has_submission);
            
            // Sort each group based on selected sort option
            const sortFn = this.sortBy === 'name' 
                ? (a, b) => a.student_name.localeCompare(b.student_name)
                : (a, b) => {
                    // For date sorting
                    if (!a.submitted_at_timestamp && !b.submitted_at_timestamp) return 0;
                    if (!a.submitted_at_timestamp) return 1;
                    if (!b.submitted_at_timestamp) return -1;
                    return b.submitted_at_timestamp - a.submitted_at_timestamp;
                };
            
            ungraded.sort(sortFn);
            graded.sort(sortFn);
            // Always sort no submissions by name
            noSubmission.sort((a, b) => a.student_name.localeCompare(b.student_name));
            
            // Combine: ungraded first, then graded, then no submission
            this.quizData.submissions = [...ungraded, ...graded, ...noSubmission];
        },

        openModal(submission, submissionIndex = -1) {
            // Find the index if not provided
            if (submissionIndex === -1 && submission) {
                submissionIndex = this.quizData.submissions.findIndex(s => s.id === submission.id);
            }
            
            console.log('Opening modal with submission:', submission);
            console.log('Submission has answers:', submission?.answers);
            console.log('Answers count:', submission?.answers?.length);
            
            this.selectedSubmission = submission;
            this.currentSubmissionIndex = submissionIndex;
            this.modalOpen = true;
            this.lockBodyScroll();
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        closeModal() {
            this.modalOpen = false;
            this.selectedSubmission = null;
            this.currentSubmissionIndex = -1;
            this.unlockBodyScroll();
        },
        
        navigateToPrevious() {
            if (this.currentSubmissionIndex > 0) {
                let prevIndex = this.currentSubmissionIndex - 1;
                
                // Find previous submission that has content
                while (prevIndex >= 0 && !this.quizData.submissions[prevIndex].has_submission) {
                    prevIndex--;
                }
                
                if (prevIndex >= 0 && this.quizData.submissions[prevIndex].has_submission) {
                    this.openModal(this.quizData.submissions[prevIndex], prevIndex);
                }
            }
        },
        
        navigateToNext() {
            if (this.currentSubmissionIndex < this.quizData.submissions.length - 1) {
                let nextIndex = this.currentSubmissionIndex + 1;
                
                // Find next submission that has content
                while (nextIndex < this.quizData.submissions.length && !this.quizData.submissions[nextIndex].has_submission) {
                    nextIndex++;
                }
                
                if (nextIndex < this.quizData.submissions.length && this.quizData.submissions[nextIndex].has_submission) {
                    this.openModal(this.quizData.submissions[nextIndex], nextIndex);
                }
            }
        },
        
        hasPreviousSubmission() {
            if (this.currentSubmissionIndex <= 0) return false;
            
            for (let i = this.currentSubmissionIndex - 1; i >= 0; i--) {
                if (this.quizData.submissions[i].has_submission) return true;
            }
            return false;
        },
        
        hasNextSubmission() {
            if (this.currentSubmissionIndex < 0 || this.currentSubmissionIndex >= this.quizData.submissions.length - 1) return false;
            
            for (let i = this.currentSubmissionIndex + 1; i < this.quizData.submissions.length; i++) {
                if (this.quizData.submissions[i].has_submission) return true;
            }
            return false;
        },

        openQuestionModal() {
            if (!this.hasQuestionStats()) return;
            this.questionModalOpen = true;
            this.lockBodyScroll();
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        closeQuestionModal() {
            this.questionModalOpen = false;
            this.unlockBodyScroll();
        },

        async saveGradeModal(answer) {
            const pointsInput = document.getElementById('modal-points-' + answer.id);
            await this.processGrade(answer, pointsInput);
        },

        async saveGrade(answer) {
            const pointsInput = document.getElementById('points-' + answer.id);
            await this.processGrade(answer, pointsInput);
        },

        async processGrade(answer, pointsInput) {
            const points = parseFloat(pointsInput?.value);

            if (isNaN(points) || points < 0) {
                this.showToast('Please enter a valid score', 'error');
                return;
            }

            if (points > answer.question_points) {
                this.showToast(`Score cannot exceed ${answer.question_points} points`, 'error');
                return;
            }

            try {
                const response = await fetch('/implementor/assessment-results/grade', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        answer_id: answer.id,
                        points: points
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Update the answer in the UI
                    answer.points_earned = points;
                    answer.needs_grading = false;
                    
                    // Store current submission id before reload
                    const currentSubmissionId = this.selectedSubmission?.id;
                    const wasModalOpen = this.modalOpen;
                    
                    // Preserve modal state during reload
                    this._preserveModal = wasModalOpen;
                    
                    // Reload to get updated stats
                    await this.loadQuizResults();
                    
                    // Reopen modal with updated data if it was open
                    if (currentSubmissionId && wasModalOpen) {
                        const updatedSubmission = this.quizData.submissions.find(s => s.id === currentSubmissionId);
                        if (updatedSubmission) {
                            this.selectedSubmission = updatedSubmission;
                            this.modalOpen = true;
                            this.$nextTick(() => {
                                if (typeof lucide !== 'undefined') {
                                    lucide.createIcons();
                                }
                            });
                        }
                    }
                    
                    this.showToast('Grade saved successfully!', 'success');
                } else {
                    this.showToast(data.message || 'Failed to save grade', 'error');
                }
            } catch (error) {
                console.error('Error saving grade:', error);
                this.showToast('Failed to save grade', 'error');
            }
        },

        enableRegrade(answer) {
            answer.needs_grading = true;
            this.$nextTick(() => {
                // Try modal input first, then regular input
                const input = document.getElementById('modal-points-' + answer.id) || 
                              document.getElementById('points-' + answer.id);
                if (input) {
                    input.value = answer.points_earned;
                    input.focus();
                }
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        },

        exportCSV() {
            if (!this.selectedQuizId) return;
            window.location.href = `/implementor/assessment-results/${this.selectedQuizId}/export`;
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            
            this.$nextTick(() => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });

            setTimeout(() => {
                this.toast.show = false;
            }, 3000);
        },

        destroyCharts() {
            if (this.questionChart) {
                this.questionChart.destroy();
                this.questionChart = null;
            }

            if (this.scoreChart) {
                this.scoreChart.destroy();
                this.scoreChart = null;
            }
        },

        renderCharts() {
            if (typeof Chart === 'undefined') {
                return;
            }

            this.$nextTick(() => {
                this.destroyCharts();

                const questionCanvas = this.$refs.questionChart;
                const questionData = this.getFilteredQuestionStats();

                if (questionCanvas && questionData.length) {
                    const labels = questionData.map(q => `Q${q.question_number}`);
                    const data = questionData.map(q => q.percent_correct);
                    const colors = data.map(value => {
                        if (value >= 80) return '#22c55e';
                        if (value >= 50) return '#fbbf24';
                        return '#f87171';
                    });

                    this.questionChart = new Chart(questionCanvas, {
                        type: 'bar',
                        data: {
                            labels,
                            datasets: [{
                                data,
                                backgroundColor: colors,
                                borderRadius: 6,
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    min: 0,
                                    max: 100,
                                    ticks: {
                                        callback: (value) => `${value}%`
                                    },
                                    grid: { color: '#e2e8f0' }
                                },
                                y: {
                                    grid: { display: false }
                                }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: (context) => `${context.parsed.x}% correct`
                                    }
                                }
                            }
                        }
                    });
                }

                const scoreCanvas = this.$refs.scoreChart;
                const distribution = this.quizData?.score_distribution || {};
                const distributionData = this.scoreLegend.map(item => distribution[item.key] || 0);
                const hasDistribution = distributionData.some(count => count > 0);

                if (scoreCanvas && hasDistribution) {
                    this.scoreChart = new Chart(scoreCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: this.scoreLegend.map(item => item.label),
                            datasets: [{
                                data: distributionData,
                                backgroundColor: this.scoreLegend.map(item => item.color),
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            plugins: {
                                legend: { display: false }
                            }
                        }
                    });
                }
            });
        },

        toggleQuestionFilterMode() {
            this.questionFilterMode = this.questionFilterMode === 'problem' ? 'all' : 'problem';
            this.renderCharts();
        },

        handleEscape() {
            if (this.modalOpen) {
                this.closeModal();
            } else if (this.questionModalOpen) {
                this.closeQuestionModal();
            }
        },

        lockBodyScroll() {
            this._scrollLockCount++;
            document.body.style.overflow = 'hidden';
        },

        unlockBodyScroll() {
            this._scrollLockCount = Math.max(0, this._scrollLockCount - 1);
            if (this._scrollLockCount === 0) {
                document.body.style.overflow = '';
            }
        },

        getQuestionStatsRaw() {
            return (this.quizData?.question_stats || []).filter(q => q.graded_attempts > 0);
        },

        getFilteredQuestionStats(limit = true) {
            let stats = this.questionFilterMode === 'problem'
                ? this.getProblemQuestionStats()
                : this.getQuestionStatsRaw();

            stats = stats.sort((a, b) => a.percent_correct - b.percent_correct);

            if (limit && this.questionFilterMode === 'problem' && stats.length > 5) {
                return stats.slice(0, 5);
            }

            return stats;
        },

        getProblemQuestionStats() {
            return this.getQuestionStatsRaw().filter(q => q.percent_correct < 70);
        },

        hasQuestionStats() {
            return this.getQuestionStatsRaw().length > 0;
        },

        hasProblemQuestions() {
            return this.getQuestionStatsRaw().some(q => q.percent_correct < 70);
        },

        isProblemChartLimited() {
            return this.questionFilterMode === 'problem' && this.getProblemQuestionStats().length > 5;
        },

        problemQuestionCount() {
            return this.getQuestionStatsRaw().filter(q => q.percent_correct < 70).length;
        },

        totalQuestionCount() {
            return this.getQuestionStatsRaw().length;
        },

        hasScoreDistribution() {
            const distribution = this.quizData?.score_distribution || {};
            return this.scoreLegend.some(item => (distribution[item.key] || 0) > 0);
        },

        formatPercent(value, decimals = 1) {
            if (value === null || value === undefined || isNaN(value)) {
                return '—';
            }
            let formatted = Number(value).toFixed(decimals);
            if (formatted.endsWith('.0')) {
                formatted = formatted.slice(0, -2);
            }
            return `${formatted}%`;
        },

        formatDecimal(value, decimals = 1) {
            if (value === null || value === undefined || isNaN(value)) {
                return '0';
            }
            let formatted = Number(value).toFixed(decimals);
            if (formatted.endsWith('.0')) {
                formatted = formatted.slice(0, -2);
            }
            return formatted;
        },

        formatRatio(current, total) {
            if (!total) {
                return current ?? 0;
            }
            return `${current ?? 0} / ${total}`;
        }
    }));
});
</script>

<script>
    @if(session('swal'))
        Swal.fire({
            icon: '{{ session('swal.icon') }}',
            title: '{{ session('swal.title') }}',
            text: '{{ session('swal.text') }}',
            confirmButtonColor: '#000000'
        });
    @endif
</script>
@endsection
