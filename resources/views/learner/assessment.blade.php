@extends('layouts.layout')

@section('title', 'Assessment')
@section('page-title', 'Assessment Form Title')

@section('content')
<div class="bg-white border rounded-3xl p-6">
    <!-- Title -->
    <h2 class="text-2xl font-bold mb-2">Assessment Form Title</h2>
    <p class="text-gray-600 mb-6">
        Instructions here instructions here instructions here instructions here instructions here instructions here
        instructions here instructions here instructions here instructions here instructions here
    </p>

    <form action="#" method="POST" class="space-y-6">
        @csrf

        <!-- Question 1 -->
        <div>
            <label class="block text-lg font-semibold mb-2">Question one?</label>
            <input type="text" name="question1" placeholder="Answer"
                class="w-full border rounded-lg p-3 focus:ring focus:ring-gray-300" />
        </div>

        <!-- Question 2 (multiple choice) -->
        <div>
            <label class="block text-lg font-semibold mb-2">Question two?</label>
            <div class="space-y-3">
                <label class="flex items-center border rounded-lg p-3 cursor-pointer">
                    <input type="checkbox" class="mr-3" /> Option 1
                </label>
                <label class="flex items-center border rounded-lg p-3 cursor-pointer">
                    <input type="checkbox" class="mr-3" /> Option 2
                </label>
                <label class="flex items-center border rounded-lg p-3 cursor-pointer">
                    <input type="checkbox" class="mr-3" /> Option 3
                </label>
                <label class="flex items-center border rounded-lg p-3 cursor-pointer">
                    <input type="checkbox" class="mr-3" /> Add option
                </label>
            </div>
        </div>

        <!-- True / False -->
        <div class="space-y-3">
            <input type="text" class="w-full border rounded-lg p-3" placeholder="TRUE: True statement" />
            <input type="text" class="w-full border rounded-lg p-3" placeholder="FALSE: False statement" />
        </div>

        <!-- Another Multiple Choice -->
        <div class="space-y-3">
            <label class="flex items-center border rounded-lg p-3 cursor-pointer">
                <input type="checkbox" class="mr-3" /> Option 1
            </label>
            <label class="flex items-center border rounded-lg p-3 cursor-pointer">
                <input type="checkbox" class="mr-3" /> Option 2
            </label>
            <label class="flex items-center border rounded-lg p-3 cursor-pointer">
                <input type="checkbox" class="mr-3" /> Option 3
            </label>
        </div>

        <!-- Submit button -->
        <div class="flex justify-center">
            <button type="submit" class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800">
                Submit
            </button>
        </div>
    </form>
</div>
@endsection
