@extends('layouts.layout')

@section('title', 'Course Grades')
@section('page-title', 'Course Grades')

@section('content')
<div class="px-6 py-6 max-w-full">

    <h2 class="text-2xl font-bold mb-6">{{ $course->name ?? '--' }} — Grades</h2>

    <!-- Scroll wrapper -->
    <div class="overflow-x-auto scrollbar-hide bg-white rounded-2xl shadow-sm border">

        <table class="min-w-[1400px] w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="px-6 py-4 text-left font-semibold sticky left-0 bg-gray-100 border-r z-10 w-64">
                        Student
                    </th>

                    @foreach ([
                        'Assignment 1', 'Assignment 2', 'Assignment 3', 'Assignment 4', 'Assignment 5',
                        'Quiz 1', 'Quiz 2', 'Quiz 3',
                        'Midterm Exam', 'Final Exam'
                    ] as $activity)
                    <th class="px-6 py-4 text-center font-semibold whitespace-nowrap w-32">
                        {{ $activity }}
                    </th>
                    @endforeach

                    <th class="px-6 py-4 text-center font-semibold w-32">Final Grade</th>
                </tr>
            </thead>

            <tbody>
                @for ($i = 1; $i <= 6; $i++)
                <tr class="border-b hover:bg-gray-50">

                    <!-- Student Info -->
                    <td class="px-6 py-4 flex flex-col gap-1 sticky left-0 bg-white border-r z-10 w-64">
                        <div class="flex items-center gap-3">
                            <img 
                                src="https://ui-avatars.com/api/?name=Student+{{ $i }}&background=0D8ABC&color=fff"
                                class="w-10 h-10 rounded-full"
                            >
                            <p class="font-semibold">Student {{ $i }}</p>
                        </div>
                        <p class="text-xs text-gray-500 truncate max-w-[180px]">
                            student{{ $i }}@gmail.com
                        </p>
                    </td>

                  @foreach ([
    'Assignment 1', 'Assignment 2', 'Assignment 3', 'Assignment 4', 'Assignment 5',
    'Quiz 1', 'Quiz 2', 'Quiz 3',
    'Midterm Exam', 'Final Exam'
] as $activity)

@php
    // random submission and grade
    $submitted = rand(0,1); // 0 = Not Submitted, 1 = Submitted
    $score = $submitted ? rand(60, 100) : '--';
@endphp

<td class="px-3 py-2 text-center">
    <div class="w-28 mx-auto flex flex-col items-center gap-1 p-3 rounded-2xl
                shadow-sm transition hover:scale-105
                {{ $submitted ? 'bg-green-50' : 'bg-red-50' }}">
        <div class="w-6 h-6 flex items-center justify-center rounded-full
                    {{ $submitted ? 'bg-green-300 text-green-800' : 'bg-red-300 text-red-800' }}">
            @if($submitted)
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            @endif
        </div>
        <span class="text-xs font-medium text-gray-700">{{ $submitted ? 'Submitted' : 'Not Submitted' }}</span>
        <span class="font-semibold text-blue-600 text-sm">{{ $score }}</span>
    </div>
</td>
@endforeach


                    <td class="px-6 py-4 text-center font-bold text-gray-800 w-32">
                        {{ rand(70,100) }}%
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

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
@endsection
