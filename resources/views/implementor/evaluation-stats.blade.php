@extends('layouts.layout')

@section('title', 'Evaluation Statistics')

@section('content')
<div class="p-6" x-data="{ 
    showCourseCommentsModal: false, 
    showImplementorCommentsModal: false 
}">
    <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6 mb-6">
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

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Course responses</p>
            <p class="text-3xl font-bold text-gray-900">{{ $courseFeedbackStats['total_responses'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">
                Completion: {{ $courseFeedbackStats['completion_rate'] !== null ? $courseFeedbackStats['completion_rate'].'%' : '—' }}
                ({{ $enrolledCount ?? 0 }} enrolled)
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Implementor responses</p>
            <p class="text-3xl font-bold text-gray-900">{{ $implementorFeedbackStats['total_responses'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">
                Completion: {{ $implementorFeedbackStats['completion_rate'] !== null ? $implementorFeedbackStats['completion_rate'].'%' : '—' }}
                ({{ $enrolledCount ?? 0 }} enrolled)
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Course overall (avg)</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($courseFeedbackStats['averages']['overall'] ?? 0, 1) }}</p>
            <p class="text-xs text-gray-500 mt-1">Materials {{ number_format($courseFeedbackStats['averages']['materials'] ?? 0,1) }} · Structure {{ number_format($courseFeedbackStats['averages']['structure'] ?? 0,1) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Implementor overall (avg)</p>
            <p class="text-3xl font-bold text-gray-900">{{ number_format($implementorFeedbackStats['averages']['teaching_effectiveness'] ?? 0, 1) }}</p>
            <p class="text-xs text-gray-500 mt-1">Resp {{ number_format($implementorFeedbackStats['averages']['responsiveness'] ?? 0,1) }} · Clarity {{ number_format($implementorFeedbackStats['averages']['explanation_clarity'] ?? 0,1) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Course ratings breakdown</h3>
            <div class="space-y-4 text-sm text-gray-700">
                @foreach(['overall' => 'Overall Rating', 'materials' => 'Materials Quality', 'structure' => 'Course Structure', 'engagement' => 'Engagement'] as $key => $label)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">{{ $label }}</span>
                            <span class="text-blue-600 font-semibold">{{ number_format($courseFeedbackStats['averages'][$key] ?? 0, 1) }} ⭐</span>
                        </div>
                        <div class="space-y-1">
                            @foreach([5,4,3,2,1] as $score)
                                @php $val = $courseFeedbackStats['distribution'][$key][$score] ?? 0; @endphp
                                <div class="flex items-center gap-2">
                                    <span class="w-8 text-xs">{{ $score }}★</span>
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded">
                                        <div class="h-1.5 bg-blue-500 rounded" style="width: {{ $courseFeedbackStats['total_responses'] ? ($val / max($courseFeedbackStats['total_responses'],1) * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="w-8 text-xs text-right">{{ $val }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-4">Implementor ratings breakdown</h3>
            <div class="space-y-4 text-sm text-gray-700">
                @foreach(['teaching_effectiveness' => 'Teaching Effectiveness', 'responsiveness' => 'Responsiveness', 'explanation_clarity' => 'Explanation Clarity', 'recommendation' => 'Recommendation'] as $key => $label)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="font-medium">{{ $label }}</span>
                            <span class="text-green-600 font-semibold">{{ number_format($implementorFeedbackStats['averages'][$key] ?? 0, 1) }} ⭐</span>
                        </div>
                        <div class="space-y-1">
                            @foreach([5,4,3,2,1] as $score)
                                @php $val = $implementorFeedbackStats['distribution'][$key][$score] ?? 0; @endphp
                                <div class="flex items-center gap-2">
                                    <span class="w-8 text-xs">{{ $score }}★</span>
                                    <div class="flex-1 h-1.5 bg-gray-100 rounded">
                                        <div class="h-1.5 bg-green-500 rounded" style="width: {{ $implementorFeedbackStats['total_responses'] ? ($val / max($implementorFeedbackStats['total_responses'],1) * 100) : 0 }}%"></div>
                                    </div>
                                    <span class="w-8 text-xs text-right">{{ $val }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-semibold text-gray-800">Course comments</h3>
                @if(count($comments['course'] ?? []) > 0)
                    <button @click="showCourseCommentsModal = true" class="text-sm text-blue-600 hover:text-blue-800 font-medium">See all ({{ count($comments['course']) }})</button>
                @endif
            </div>
            <div class="text-sm text-gray-700">
                @php $firstCourseComment = collect($comments['course'] ?? [])->first(); @endphp
                @if($firstCourseComment)
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="line-clamp-3">{{ $firstCourseComment['comment'] }}</p>
                        <span class="text-xs text-gray-500 mt-2 block">{{ $firstCourseComment['created_at'] ?? '' }}</span>
                    </div>
                @else
                    <p class="text-gray-500">No comments yet.</p>
                @endif
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-semibold text-gray-800">Implementor comments</h3>
                @if(count($comments['implementor'] ?? []) > 0)
                    <button @click="showImplementorCommentsModal = true" class="text-sm text-blue-600 hover:text-blue-800 font-medium">See all ({{ count($comments['implementor']) }})</button>
                @endif
            </div>
            <div class="text-sm text-gray-700">
                @php $firstImplementorComment = collect($comments['implementor'] ?? [])->first(); @endphp
                @if($firstImplementorComment)
                    <div class="p-3 bg-gray-50 rounded border">
                        <p class="line-clamp-3">{{ $firstImplementorComment['comment'] }}</p>
                        <span class="text-xs text-gray-500 mt-2 block">{{ $firstImplementorComment['created_at'] ?? '' }}</span>
                    </div>
                @else
                    <p class="text-gray-500">No comments yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-3">Submission trends</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
                <h4 class="font-semibold text-gray-700 mb-2">Course feedback by date</h4>
                <div class="space-y-1">
                    @forelse(($trends['course'] ?? []) as $date => $count)
                        <div class="flex justify-between">
                            <span>{{ $date }}</span>
                            <span>{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500">No submissions yet.</p>
                    @endforelse
                </div>
            </div>
            <div>
                <h4 class="font-semibold text-gray-700 mb-2">Implementor feedback by date</h4>
                <div class="space-y-1">
                    @forelse(($trends['implementor'] ?? []) as $date => $count)
                        <div class="flex justify-between">
                            <span>{{ $date }}</span>
                            <span>{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500">No submissions yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Course Comments Modal -->
    <div x-show="showCourseCommentsModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
         @click.self="showCourseCommentsModal = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-hidden flex flex-col"
             @click.stop>
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold text-gray-900">All Course Comments ({{ count($comments['course'] ?? []) }})</h2>
                <button @click="showCourseCommentsModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="overflow-y-auto p-6 space-y-4">
                @forelse($comments['course'] ?? [] as $item)
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-800 whitespace-pre-wrap">{{ $item['comment'] }}</p>
                        <span class="text-xs text-gray-500 mt-2 block">{{ $item['created_at'] ?? '' }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Implementor Comments Modal -->
    <div x-show="showImplementorCommentsModal" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm"
         @click.self="showImplementorCommentsModal = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-hidden flex flex-col"
             @click.stop>
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-bold text-gray-900">All Implementor Comments ({{ count($comments['implementor'] ?? []) }})</h2>
                <button @click="showImplementorCommentsModal = false" class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="overflow-y-auto p-6 space-y-4">
                @forelse($comments['implementor'] ?? [] as $item)
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
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

