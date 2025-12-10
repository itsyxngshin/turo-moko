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
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-700 text-sm">← Back to course</a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Course Responses</p>
            <p class="text-3xl font-bold text-gray-900">{{ $courseFeedbackStats['total_responses'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $courseFeedbackStats['completion_rate'] ?? 0 }}% completion ({{ $enrolledCount ?? 0 }} enrolled)
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Implementor Responses</p>
            <p class="text-3xl font-bold text-gray-900">{{ $implementorFeedbackStats['total_responses'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">
                {{ $implementorFeedbackStats['completion_rate'] ?? 0 }}% completion ({{ $enrolledCount ?? 0 }} enrolled)
            </p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Course Avg Rating</p>
            @php
                $courseAvgs = collect($courseFeedbackStats['questions'] ?? [])->pluck('average')->filter();
                $courseOverall = $courseAvgs->count() > 0 ? round($courseAvgs->avg(), 1) : 0;
            @endphp
            <p class="text-3xl font-bold text-gray-900">{{ number_format($courseOverall, 1) }}</p>
            <p class="text-xs text-gray-500 mt-1">Across {{ $courseFeedbackStats['questions']->count() ?? 0 }} questions</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <p class="text-sm text-gray-500 mb-1">Implementor Avg Rating</p>
            @php
                $impAvgs = collect($implementorFeedbackStats['questions'] ?? [])->pluck('average')->filter();
                $impOverall = $impAvgs->count() > 0 ? round($impAvgs->avg(), 1) : 0;
            @endphp
            <p class="text-3xl font-bold text-gray-900">{{ number_format($impOverall, 1) }}</p>
            <p class="text-xs text-gray-500 mt-1">Across {{ $implementorFeedbackStats['questions']->count() ?? 0 }} questions</p>
        </div>
    </div>

    <!-- Rating Distribution -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <!-- Course Ratings -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Course rating distribution</h3>
            @php
                $firstCourseQuestion = collect($courseFeedbackStats['questions'] ?? [])->first();
                $courseDist = $firstCourseQuestion['distribution'] ?? collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]);
            @endphp
            <div class="space-y-2 text-sm text-gray-700">
                @foreach([5,4,3,2,1] as $score)
                    @php $val = $courseDist[$score] ?? 0; @endphp
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
            
            <!-- Detailed questions -->
            @if(count($courseFeedbackStats['questions'] ?? []) > 1)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-600 mb-2">By Question:</h4>
                    <div class="space-y-2">
                        @foreach($courseFeedbackStats['questions'] ?? [] as $question)
                            <div class="text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $question['text'] }}</span>
                                    <span class="font-medium text-blue-600">{{ $question['average'] !== null ? number_format($question['average'], 1) : '—' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Implementor Ratings -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Implementor rating distribution</h3>
            @php
                $firstImpQuestion = collect($implementorFeedbackStats['questions'] ?? [])->first();
                $impDist = $firstImpQuestion['distribution'] ?? collect([1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0]);
            @endphp
            <div class="space-y-2 text-sm text-gray-700">
                @foreach([5,4,3,2,1] as $score)
                    @php $val = $impDist[$score] ?? 0; @endphp
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
            
            <!-- Detailed questions -->
            @if(count($implementorFeedbackStats['questions'] ?? []) > 1)
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-600 mb-2">By Question:</h4>
                    <div class="space-y-2">
                        @foreach($implementorFeedbackStats['questions'] ?? [] as $question)
                            <div class="text-xs">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">{{ $question['text'] }}</span>
                                    <span class="font-medium text-green-600">{{ $question['average'] !== null ? number_format($question['average'], 1) : '—' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Comments -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Course comments</h3>
            <div class="space-y-2 text-sm text-gray-700 max-h-60 overflow-auto">
                @forelse($comments['course'] ?? [] as $item)
                    <div class="p-3 bg-gray-50 rounded border">
                        @if(isset($item['question']) && $item['question'] !== 'Comment')
                            <p class="text-xs text-blue-600 font-medium mb-1">{{ $item['question'] }}</p>
                        @endif
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
                        @if(isset($item['question']) && $item['question'] !== 'Comment')
                            <p class="text-xs text-green-600 font-medium mb-1">{{ $item['question'] }}</p>
                        @endif
                        <p>{{ $item['comment'] }}</p>
                        <span class="text-xs text-gray-500">{{ $item['created_at'] ?? '' }}</span>
                    </div>
                @empty
                    <p class="text-gray-500">No comments yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Submission Trends -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
        <h3 class="font-semibold text-gray-800 mb-3">Submission trends</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
                <h4 class="font-semibold text-gray-700 mb-2">Course feedback by date</h4>
                <div class="space-y-1">
                    @forelse(($trends['course'] ?? collect())->sortKeysDesc() as $date => $count)
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
                    @forelse(($trends['implementor'] ?? collect())->sortKeysDesc() as $date => $count)
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

