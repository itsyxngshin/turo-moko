@extends('layouts.layout')

@section('title', 'Evaluation Statistics')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<div class="p-6" x-data="{ 
    showCourseCommentsModal: false, 
    showImplementorCommentsModal: false,
    showCourseRatings: false,
    showImplementorRatings: false,
    activeCommentTab: 'liked'
}">
    <!-- Back to Course Moderation Button -->
    @if($course)
        <div class="mb-4">
            <a href="{{ route('implementor.course-information', $course) }}" 
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
                <h1 class="text-2xl font-bold text-gray-900">Evaluation Statistics</h1>
                <p class="text-gray-500 text-sm mt-1">
                    Course: <span class="font-semibold text-gray-700">{{ $course->name ?? $course->course_title ?? 'Course' }}</span>
                </p>
            </div>
            <div class="text-sm text-gray-500">
                Responses: {{ $courseFeedbackStats['total_responses'] ?? 0 }} course / {{ $implementorFeedbackStats['total_responses'] ?? 0 }} implementor
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <!-- Course Responses Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 mb-1">Course Responses</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-bold text-gray-900">{{ $courseFeedbackStats['total_responses'] ?? 0 }}/{{ $enrolledCount ?? 0 }}</p>
                        <span class="text-sm text-gray-500">{{ $courseFeedbackStats['completion_rate'] !== null ? $courseFeedbackStats['completion_rate'].'%' : '—' }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Enrolled learners</p>
                </div>
            </div>
        </div>

        <!-- Implementor Responses Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 mb-1">Implementor Responses</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-2xl font-bold text-gray-900">{{ $implementorFeedbackStats['total_responses'] ?? 0 }}/{{ $enrolledCount ?? 0 }}</p>
                        <span class="text-sm text-gray-500">{{ $implementorFeedbackStats['completion_rate'] !== null ? $implementorFeedbackStats['completion_rate'].'%' : '—' }}</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Enrolled learners</p>
                </div>
            </div>
        </div>

        <!-- Course Average Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 mb-1">Course Average</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($courseFeedbackStats['averages']['overall'] ?? 0, 1) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Out of 5 stars</p>
                </div>
            </div>
        </div>

        <!-- Implementor Average Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-500 mb-1">Implementor Average</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($implementorFeedbackStats['averages']['teaching_effectiveness'] ?? 0, 1) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Out of 5 stars</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ratings Breakdown Cards -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-6">
        <!-- Course Ratings Breakdown -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Course Ratings Breakdown</h3>
                    <p class="text-sm text-gray-500">Based on {{ $courseFeedbackStats['total_responses'] ?? 0 }} responses</p>
                </div>
                <button @click="showCourseRatings = !showCourseRatings" 
                        class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-full text-xs font-medium text-gray-600 hover:text-blue-600 hover:border-blue-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span x-text="showCourseRatings ? 'Collapse' : 'See all'"></span>
                </button>
            </div>

            <!-- Summary View (always visible) -->
            <div class="grid grid-cols-2 gap-3">
                @foreach(['overall' => 'Key Topics', 'materials' => 'Content Appropriateness', 'structure' => 'Engagement', 'engagement' => 'Time Management'] as $key => $label)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-400">★</span>
                            <span class="text-sm text-gray-700 font-medium">{{ $label }}</span>
                        </div>
                        <span class="text-sm font-bold text-blue-600">{{ number_format($courseFeedbackStats['averages'][$key] ?? 0, 1) }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Detailed Breakdown (collapsible) -->
            <div x-show="showCourseRatings" x-collapse x-cloak class="mt-4 pt-4 border-t border-gray-100">
                <div class="space-y-4 text-sm text-gray-700">
                    @foreach(['overall' => 'Key Topics Understanding', 'materials' => 'Content Appropriateness', 'structure' => 'Course Engagement', 'engagement' => 'Time Management'] as $key => $label)
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">{{ $label }}</span>
                                <span class="text-blue-600 font-semibold">{{ number_format($courseFeedbackStats['averages'][$key] ?? 0, 1) }} ⭐</span>
                            </div>
                            <div class="space-y-1">
                                @foreach([5,4,3,2,1] as $score)
                                    @php $val = $courseFeedbackStats['distribution'][$key][$score] ?? 0; @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="w-8 text-xs text-gray-500">{{ $score }}★</span>
                                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-2 bg-blue-500 rounded-full transition-all duration-300" style="width: {{ $courseFeedbackStats['total_responses'] ? ($val / max($courseFeedbackStats['total_responses'],1) * 100) : 0 }}%"></div>
                                        </div>
                                        <span class="w-8 text-xs text-gray-500 text-right">{{ $val }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Implementor Ratings Breakdown -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Implementor Ratings Breakdown</h3>
                    <p class="text-sm text-gray-500">Based on {{ $implementorFeedbackStats['total_responses'] ?? 0 }} responses</p>
                </div>
                <button @click="showImplementorRatings = !showImplementorRatings" 
                        class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-full text-xs font-medium text-gray-600 hover:text-green-600 hover:border-green-300 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <span x-text="showImplementorRatings ? 'Collapse' : 'See all'"></span>
                </button>
            </div>

            <!-- Summary View (always visible) -->
            <div class="grid grid-cols-2 gap-3">
                @foreach(['teaching_effectiveness' => 'Teaching Effectiveness', 'responsiveness' => 'Responsiveness', 'explanation_clarity' => 'Explanation Clarity', 'recommendation' => 'Recommendation'] as $key => $label)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-400">★</span>
                            <span class="text-sm text-gray-700 font-medium">{{ $label }}</span>
                        </div>
                        <span class="text-sm font-bold text-green-600">{{ number_format($implementorFeedbackStats['averages'][$key] ?? 0, 1) }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Detailed Breakdown (collapsible) -->
            <div x-show="showImplementorRatings" x-collapse x-cloak class="mt-4 pt-4 border-t border-gray-100">
                <div class="space-y-4 text-sm text-gray-700">
                    @foreach(['teaching_effectiveness' => 'Teaching Effectiveness', 'responsiveness' => 'Responsiveness', 'explanation_clarity' => 'Explanation Clarity', 'recommendation' => 'Recommendation'] as $key => $label)
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">{{ $label }}</span>
                                <span class="text-green-600 font-semibold">{{ number_format($implementorFeedbackStats['averages'][$key] ?? 0, 1) }} ⭐</span>
                            </div>
                            <div class="space-y-1">
                                @foreach([5,4,3,2,1] as $score)
                                    @php $val = $implementorFeedbackStats['distribution'][$key][$score] ?? 0; @endphp
                                    <div class="flex items-center gap-2">
                                        <span class="w-8 text-xs text-gray-500">{{ $score }}★</span>
                                        <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-2 bg-green-500 rounded-full transition-all duration-300" style="width: {{ $implementorFeedbackStats['total_responses'] ? ($val / max($implementorFeedbackStats['total_responses'],1) * 100) : 0 }}%"></div>
                                        </div>
                                        <span class="w-8 text-xs text-gray-500 text-right">{{ $val }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-6">
        <!-- Course Comments with 3 Tabs -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Course Feedback Comments</h3>
            </div>
            
            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-200">
                <button @click="activeCommentTab = 'liked'" 
                        :class="activeCommentTab === 'liked' ? 'border-b-2 border-blue-500 text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 px-4 py-3 text-sm font-medium transition-colors">
                    What they liked
                    <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full" 
                          :class="activeCommentTab === 'liked' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500'">
                        {{ collect($comments['course'] ?? [])->where('question', 'What did you like most?')->count() }}
                    </span>
                </button>
                <button @click="activeCommentTab = 'improve'" 
                        :class="activeCommentTab === 'improve' ? 'border-b-2 border-blue-500 text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 px-4 py-3 text-sm font-medium transition-colors">
                    Could be better
                    <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full" 
                          :class="activeCommentTab === 'improve' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500'">
                        {{ collect($comments['course'] ?? [])->where('question', 'What could be better?')->count() }}
                    </span>
                </button>
                <button @click="activeCommentTab = 'additional'" 
                        :class="activeCommentTab === 'additional' ? 'border-b-2 border-blue-500 text-blue-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 px-4 py-3 text-sm font-medium transition-colors">
                    Additional
                    <span class="ml-1 text-xs px-1.5 py-0.5 rounded-full" 
                          :class="activeCommentTab === 'additional' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-500'">
                        {{ collect($comments['course'] ?? [])->where('question', 'Additional suggestions')->count() }}
                    </span>
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-4 max-h-80 overflow-y-auto">
                <!-- What they liked -->
                <div x-show="activeCommentTab === 'liked'" x-cloak>
                    @php $likedComments = collect($comments['course'] ?? [])->where('question', 'What did you like most?'); @endphp
                    @if($likedComments->count() > 0)
                        <div class="space-y-3">
                            @foreach($likedComments as $item)
                                <div class="p-3 bg-green-50 rounded-xl border border-green-100">
                                    <p class="text-sm text-gray-800">{{ $item['comment'] }}</p>
                                    <span class="text-xs text-gray-500 mt-2 block">{{ $item['created_at'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-gray-500 text-sm">No comments yet</p>
                        </div>
                    @endif
                </div>

                <!-- Could be better -->
                <div x-show="activeCommentTab === 'improve'" x-cloak>
                    @php $improveComments = collect($comments['course'] ?? [])->where('question', 'What could be better?'); @endphp
                    @if($improveComments->count() > 0)
                        <div class="space-y-3">
                            @foreach($improveComments as $item)
                                <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                                    <p class="text-sm text-gray-800">{{ $item['comment'] }}</p>
                                    <span class="text-xs text-gray-500 mt-2 block">{{ $item['created_at'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-gray-500 text-sm">No suggestions yet</p>
                        </div>
                    @endif
                </div>

                <!-- Additional suggestions -->
                <div x-show="activeCommentTab === 'additional'" x-cloak>
                    @php $additionalComments = collect($comments['course'] ?? [])->where('question', 'Additional suggestions'); @endphp
                    @if($additionalComments->count() > 0)
                        <div class="space-y-3">
                            @foreach($additionalComments as $item)
                                <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                                    <p class="text-sm text-gray-800">{{ $item['comment'] }}</p>
                                    <span class="text-xs text-gray-500 mt-2 block">{{ $item['created_at'] ?? '' }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                            <p class="text-gray-500 text-sm">No additional comments yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Implementor Comments -->
        <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Implementor Feedback Comments</h3>
                    <p class="text-sm text-gray-500">{{ count($comments['implementor'] ?? []) }} comments</p>
                </div>
                @if(count($comments['implementor'] ?? []) > 3)
                    <button @click="showImplementorCommentsModal = true" 
                            class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-full text-xs font-medium text-gray-600 hover:text-green-600 hover:border-green-300 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span>See all</span>
                    </button>
                @endif
            </div>
            
            <div class="p-4 max-h-80 overflow-y-auto">
                @if(count($comments['implementor'] ?? []) > 0)
                    <div class="space-y-3">
                        @foreach(collect($comments['implementor'] ?? [])->take(5) as $item)
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                                <p class="text-sm text-gray-800">{{ $item['comment'] }}</p>
                                <span class="text-xs text-gray-500 mt-2 block">{{ $item['created_at'] ?? '' }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <p class="text-gray-500 text-sm">No comments yet</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Submission Trends -->
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Submission Trends</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-200">
            <div class="p-6">
                <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                    Course Feedback by Date
                </h4>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse(($trends['course'] ?? []) as $date => $count)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">{{ $date }}</span>
                            <span class="font-medium text-gray-900 bg-blue-50 px-2 py-0.5 rounded">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No submissions yet.</p>
                    @endforelse
                </div>
            </div>
            <div class="p-6">
                <h4 class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                    Implementor Feedback by Date
                </h4>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse(($trends['implementor'] ?? []) as $date => $count)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">{{ $date }}</span>
                            <span class="font-medium text-gray-900 bg-green-50 px-2 py-0.5 rounded">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No submissions yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Implementor Comments Modal -->
    <div x-show="showImplementorCommentsModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
         @click.self="showImplementorCommentsModal = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-hidden flex flex-col"
             @click.stop
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold text-gray-900">All Implementor Comments ({{ count($comments['implementor'] ?? []) }})</h2>
                <button @click="showImplementorCommentsModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="overflow-y-auto p-6 space-y-4">
                @forelse($comments['implementor'] ?? [] as $item)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $item['comment'] }}</p>
                        <span class="text-xs text-gray-500 mt-2 block">{{ $item['created_at'] ?? '' }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

