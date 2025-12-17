<div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <main class="p-6">
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
                <h1 class="text-2xl font-bold text-gray-900">Assignment Submissions</h1>
                <p class="text-gray-500 text-sm mt-1">
                    @if($course)
                        Viewing assignments for: <span class="font-semibold text-gray-700">{{ $course->course_title }}</span>
                    @else
                        View and grade student submissions
                    @endif
                </p>
            </div>
            
            <!-- Assignment Selector -->
            <div class="relative">
                <select 
                    wire:model.live="selectedAssignmentId"
                    class="block w-64 border border-gray-300 rounded-xl px-4 py-3 pr-10 cursor-pointer hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white"
                >
                    <option value="">Select an assignment</option>
                    @foreach($assignments as $assignment)
                        <option value="{{ $assignment->id }}">{{ $assignment->title }}</option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    @if(!$selectedAssignmentId)
        <div wire:loading.remove class="bg-white rounded-3xl border border-gray-200 shadow-sm py-16 px-8 text-center">
            <div class="max-w-md mx-auto">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 mb-2">Select an Assignment</h2>
                <p class="text-gray-500">Choose an assignment from the dropdown above to view submissions and grades.</p>
            </div>
        </div>
    @endif

    <!-- Loading State -->
    <div wire:loading.block class="bg-white rounded-3xl border border-gray-200 shadow-sm py-16 px-8 text-center">
        <div class="flex flex-col items-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mb-4"></div>
            <p class="text-gray-500">Loading results...</p>
        </div>
    </div>

    <!-- Results Content -->
    @if($selectedAssignmentId)
        <div wire:loading.remove>
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Submissions -->
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-blue-50 text-blue-600 font-medium">
                            {{ count(array_filter($submissions, fn($s) => $s['has_submission'])) }}/{{ count($submissions) }}
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">
                        {{ count(array_filter($submissions, fn($s) => $s['has_submission'])) }}
                    </h3>
                    <p class="text-sm text-gray-500">Total Submissions</p>
                </div>

                <!-- Average Grade -->
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">
                        @php
                            $gradedSubs = array_filter($submissions, fn($s) => $s['grade'] !== '-');
                            $avgGrade = count($gradedSubs) > 0 ? array_sum(array_map(fn($s) => $s['grade'], $gradedSubs)) / count($gradedSubs) : 0;
                        @endphp
                        {{ count($gradedSubs) > 0 ? number_format($avgGrade, 1) : '-' }}
                    </h3>
                    <p class="text-sm text-gray-500">Average Grade</p>
                </div>

                <!-- Graded vs Pending -->
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full bg-amber-50 text-amber-600 font-medium">
                            {{ count(array_filter($submissions, fn($s) => $s['status'] === 'Graded')) }} graded
                        </span>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">
                        {{ count(array_filter($submissions, fn($s) => $s['status'] === 'Submitted')) }}
                    </h3>
                    <p class="text-sm text-gray-500">Needs Grading</p>
                </div>

                <!-- No Submission -->
                <div class="bg-white rounded-3xl border border-gray-200 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-50 rounded-2xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-1">
                        {{ count(array_filter($submissions, fn($s) => !$s['has_submission'])) }}
                    </h3>
                    <p class="text-sm text-gray-500">No Submission</p>
                </div>
            </div>

            <!-- Student Submissions Table -->
            <div class="bg-white rounded-3xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Student Submissions</h2>
                </div>
                
                @if(count($submissions) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($submissions as $submission)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <span class="text-blue-600 font-medium text-sm">
                                                        {{ strtoupper(substr($submission['student_name'], 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $submission['student_name'] }}</p>
                                                    <p class="text-sm text-gray-500">{{ $submission['student_email'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($submission['submitted_at'])
                                                {{ \Carbon\Carbon::parse($submission['submitted_at'])->format('M d, Y g:i A') }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-900">
                                                {{ $submission['grade'] !== '-' ? number_format($submission['grade'], 2) : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $submission['status'] === 'Graded' ? 'bg-green-100 text-green-800' : 
                                                   ($submission['status'] === 'Submitted' ? 'bg-amber-100 text-amber-800' : 
                                                   'bg-gray-100 text-gray-800') }}">
                                                {{ $submission['status'] === 'Graded' ? 'Graded' : ($submission['status'] === 'Submitted' ? 'Needs Grading' : 'No Submission') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($submission['has_submission'])
                                                <button 
                                                    wire:click="openGradeModal({{ $submission['id'] }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    <span>{{ $submission['status'] === 'Graded' ? 'Review' : 'Grade' }}</span>
                                                </button>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="py-12 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <p class="text-gray-500">No enrolled students for this course.</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Grading Modal -->
    @if($showGradeModal && $selectedSubmission)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" wire:key="grade-modal-{{ $submissionId }}">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-6 border-b sticky top-0 bg-white z-10 rounded-t-2xl">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Grade Submission</h2>
                        <p class="text-sm text-gray-600 mt-1">{{ $selectedSubmission['assignment_title'] }}</p>
                    </div>
                    <button 
                        wire:click="closeGradeModal" 
                        class="text-gray-400 hover:text-gray-600 text-2xl leading-none w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100"
                    >
                        &times;
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- Student Info -->
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-semibold text-gray-700">Student:</span> 
                                <span class="text-gray-600">{{ $studentName }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Submitted:</span> 
                                <span class="text-gray-600">{{ $submissionDate }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submission Content -->
                    <div class="space-y-4">
                        <!-- Online Text Submission -->
                        @if($selectedSubmission['online_text'])
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                    Online Text Submission
                                </h3>
                                <div class="border border-gray-200 rounded-xl p-4 bg-white whitespace-pre-wrap max-h-60 overflow-y-auto text-sm text-gray-700">
                                    {{ $selectedSubmission['online_text'] }}
                                </div>
                            </div>
                        @endif

                        <!-- File Submission -->
                        @if($selectedSubmission['file_path'])
                            <div>
                                <h3 class="font-semibold text-gray-800 mb-2 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    File Submission
                                </h3>
                                
                                <!-- File Info & Download -->
                                <div class="border border-gray-200 rounded-xl p-4 bg-white mb-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $selectedSubmission['file_name'] }}</p>
                                                <p class="text-xs text-gray-500">
                                                    @if($selectedSubmission['can_preview'])
                                                        Preview available below
                                                    @else
                                                        Download to view file
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a 
                                                href="{{ $selectedSubmission['file_url'] }}" 
                                                target="_blank"
                                                class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition flex items-center gap-2"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                                Open in New Tab
                                            </a>
                                            <a 
                                                href="{{ $selectedSubmission['file_url'] }}" 
                                                target="_blank"
                                                download
                                                class="px-4 py-2 bg-black text-white text-sm rounded-lg hover:bg-gray-800 transition flex items-center gap-2"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- File Preview -->
                                @if($selectedSubmission['can_preview'])
                                    <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50">
                                        @if($selectedSubmission['file_type'] === 'image')
                                            <!-- Image Preview -->
                                            <div class="p-4">
                                                <img 
                                                    src="{{ $selectedSubmission['file_url'] }}" 
                                                    alt="Submission preview" 
                                                    class="max-w-full h-auto rounded-lg shadow-sm mx-auto"
                                                    style="max-height: 600px; object-fit: contain;"
                                                >
                                            </div>
                                        @elseif($selectedSubmission['file_type'] === 'pdf')
                                            <!-- PDF Preview -->
                                            <div class="relative" style="min-height: 600px;">
                                                <iframe 
                                                    src="{{ $selectedSubmission['file_url'] }}" 
                                                    class="w-full" 
                                                    style="height: 600px; border: none;"
                                                    title="PDF Preview"
                                                ></iframe>
                                            </div>
                                        @elseif($selectedSubmission['file_type'] === 'text')
                                            <!-- Text File Preview -->
                                            <div class="p-4">
                                                <div class="bg-white rounded-lg border border-gray-200 p-4 max-h-96 overflow-y-auto">
                                                    <pre class="text-sm text-gray-700 whitespace-pre-wrap font-mono">{{ $textFileContent ?? 'Unable to load file content' }}</pre>
                                                </div>
                                            </div>
                                        @elseif($selectedSubmission['file_type'] === 'document')
                                            <!-- Document Preview via Google Docs Viewer -->
                                            <div class="relative" style="min-height: 600px;">
                                                <iframe 
                                                    src="https://docs.google.com/viewer?url={{ urlencode(url($selectedSubmission['file_url'])) }}&embedded=true" 
                                                    class="w-full" 
                                                    style="height: 600px; border: none;"
                                                    title="Document Preview"
                                                ></iframe>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <!-- No Preview Available -->
                                    <div class="border border-gray-200 rounded-xl p-8 text-center bg-gray-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <p class="text-gray-600 font-medium mb-1">Preview not available</p>
                                        <p class="text-sm text-gray-500">Click the download button to view this file</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if(!$selectedSubmission['online_text'] && !$selectedSubmission['file_path'])
                            <div class="text-center py-12 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>No submission content available</p>
                            </div>
                        @endif
                    </div>

                    <!-- Grading Section -->
                    <div class="border-t pt-6">
                        <h3 class="font-bold text-lg text-gray-800 mb-4">Enter Grade</h3>
                        
                        <!-- Grade Input -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Grade (0-100) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                wire:model="grade"
                                min="0" 
                                max="100" 
                                step="0.01"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-black focus:border-black text-lg"
                                placeholder="Enter grade (e.g., 85.5)"
                            >
                            @error('grade') 
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end gap-3 p-6 border-t bg-gray-50 rounded-b-2xl">
                    <button 
                        wire:click="closeGradeModal" 
                        class="px-6 py-3 border border-gray-300 rounded-xl hover:bg-gray-100 transition font-medium"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="saveGrade"
                        class="px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition font-medium"
                    >
                        Save Grade
                    </button>
                </div>
            </div>
        </div>
    @endif
    </main>
</div>

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