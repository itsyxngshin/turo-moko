<div class="px-6 py-6 max-w-full" wire:poll.20s="refreshData">
    @php
        $activityCollection = collect($activities);
        $studentCollection = collect($students);
        $colspan = $activityCollection->count() + 2;
        $courseName = $course->name ?? $course->course_title ?? '--';
    @endphp

    <h2 class="text-2xl font-bold mb-6">{{ $courseName }} — Grades</h2>

    <div class="overflow-x-auto scrollbar-hide bg-white rounded-2xl shadow-sm border">
        @if ($activityCollection->isEmpty())
            <div class="py-16 flex flex-col items-center justify-center text-center text-gray-500">
                <i data-lucide="inbox" class="w-12 h-12 mb-4 text-gray-300"></i>
                <p class="text-base font-semibold text-gray-700 mb-1">No assignments or quizzes yet</p>
                <p class="text-sm text-gray-500 max-w-sm">
                    Create an assignment or quiz to start tracking learner submissions and grades.
                </p>
            </div>
        @else
            <table class="w-full text-sm border-collapse table-fixed">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                    <th class="px-4 py-3 text-left font-semibold sticky left-0 bg-gray-100 border-r z-10 min-w-40 w-40">
                            Student
                        </th>

                        @foreach ($activityCollection as $activity)
                            <th class="px-6 py-4 text-center font-semibold whitespace-nowrap w-32">
                                {{ $activity['label'] }}
                            </th>
                        @endforeach

                        <th class="px-6 py-4 text-center font-semibold w-32">Final Grade</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($studentCollection as $student)
                    <tr class="border-b hover:bg-gray-50" wire:key="student-{{ $student['id'] }}">
                    <td class="px-4 py-3 sticky left-0 bg-white border-r z-10 w-40">
        <div class="flex flex-col gap-1">
            <div class="flex items-center gap-3">
                <img 
                    src="{{ $student['avatar'] }}"
                    alt="{{ $student['name'] }}"
                    class="w-10 h-10 rounded-full"
                >
                <p class="font-semibold">{{ $student['name'] }}</p>
            </div>
            <p class="text-xs text-gray-500 truncate">
                {{ $student['email'] }}
            </p>
        </div>
    </td>

                            @foreach ($activityCollection as $activity)
                                @php
                                    $cell = $student['activities'][$activity['key']] ?? [
                                        'submitted' => false,
                                        'status' => 'Not Submitted',
                                        'grade' => null,
                                    ];
                                    $submitted = $cell['submitted'] ?? false;
                                    $statusLabel = $cell['status'] ?? ($submitted ? 'Submitted' : 'Not Submitted');
                                    $gradeValue = array_key_exists('grade', $cell) && $cell['grade'] !== null
                                        ? $cell['grade']
                                        : null;
                                @endphp

                                <td class="px-3 py-2 text-center" wire:key="cell-{{ $student['id'] }}-{{ $activity['key'] }}">
                                    <div class="w-28 mx-auto flex flex-col items-center gap-1 p-3 rounded-2xl
                                                shadow-sm transition hover:scale-105
                                                {{ $submitted ? 'bg-green-50' : 'bg-red-50' }}">
                                        <div class="w-6 h-6 flex items-center justify-center rounded-full
                                                    {{ $submitted ? 'bg-green-300 text-green-800' : 'bg-red-300 text-red-800' }}">
                                            @if ($submitted)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="text-xs font-medium text-gray-700">{{ $statusLabel }}</span>
                                        <span class="font-semibold text-blue-600 text-sm">
                                            {{ $gradeValue !== null ? $gradeValue : '--' }}
                                        </span>
                                    </div>
                                </td>
                            @endforeach

                            <td class="px-6 py-4 text-center font-bold text-gray-800 w-32">
                                {{ $student['final_grade'] !== null ? $student['final_grade'] . '%' : '--' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $colspan }}" class="px-6 py-12 text-center text-gray-500">
                                No students enrolled yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif
    </div>

    <!-- Tailwind scrollbar-hide plugin -->
    <style>
    /* hide scrollbar but keep scroll */
    .scrollbar-hide::-webkit-scrollbar {
      display: none;
    }
    .scrollbar-hide {
      -ms-overflow-style: none;  /* IE and Edge */
      scrollbar-width: none;     /* Firefox */
    }
    </style>
</div>
