@extends('layouts.layout')

@section('title', 'Confirm Enrollment')

@section('content')
<div class="max-w-lg mx-auto mt-16 bg-white p-6 rounded-lg shadow text-center">
    <h2 class="text-xl font-bold mb-4">
        Join {{ $course->name }}?
    </h2>

    <p class="text-gray-600 mb-6">
        Are you sure you want to enroll in this course?
    </p>

    <form method="POST" action="{{ route('course.enroll.confirm', $course->course_code) }}">
        @csrf

        <div class="flex justify-center gap-4">
            <a href="{{ url()->previous() }}" 
               class="px-4 py-2 border rounded-lg">
                Cancel
            </a>

            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Confirm Enrollment
            </button>
        </div>
    </form>
</div>
@endsection
