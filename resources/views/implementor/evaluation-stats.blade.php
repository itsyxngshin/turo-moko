@extends('layouts.layout')

@section('title', 'Evaluation Statistics')

@section('content')
<div class="p-6" x-data>
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
            <h3 class="font-semibold text-gray-800 mb-3">Course rating distribution</h3>
            <div class="space-y-2 text-sm text-gray-700">
                @foreach([5,4,3,2,1] as $score)
                    @php $val = $courseFeedbackStats['distribution']['overall'][$score] ?? 0; @endphp
                    <div>
                        <div class="flex justify-between">
                            <span>{{ $score }} stars</span>
                            <span>{{ $val }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded">
                            <div class="h-2 bg-blue-500 rounded" style="width: {{ $courseFeedbackStats['total_responses'] ? ($val / max($courseFeedbackStats['total_responses'],1) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Implementor rating distribution</h3>
            <div class="space-y-2 text-sm text-gray-700">
                @foreach([5,4,3,2,1] as $score)
                    @php $val = $implementorFeedbackStats['distribution']['teaching_effectiveness'][$score] ?? 0; @endphp
                    <div>
                        <div class="flex justify-between">
                            <span>{{ $score }} stars</span>
                            <span>{{ $val }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded">
                            <div class="h-2 bg-green-500 rounded" style="width: {{ $implementorFeedbackStats['total_responses'] ? ($val / max($implementorFeedbackStats['total_responses'],1) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Course comments</h3>
            <div class="space-y-2 text-sm text-gray-700 max-h-60 overflow-auto">
                @forelse($comments['course'] ?? [] as $item)
                    <div class="p-3 bg-gray-50 rounded border">
                        <p>{{ $item['comment'] }}</p>
                        <span class="text-xs text-gray-500">{{ $item['created_at'] ?? '' }}</span>
                    </div>
                @empty
                    <p class="text-gray-500">No comments yet.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Implementor comments</h3>
            <div class="space-y-2 text-sm text-gray-700 max-h-60 overflow-auto">
                @forelse($comments['implementor'] ?? [] as $item)
                    <div class="p-3 bg-gray-50 rounded border">
                        <p>{{ $item['comment'] }}</p>
                        <span class="text-xs text-gray-500">{{ $item['created_at'] ?? '' }}</span>
                    </div>
                @empty
                    <p class="text-gray-500">No comments yet.</p>
                @endforelse
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
</div>
@endsection

