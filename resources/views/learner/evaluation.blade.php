@extends('layouts.layout')

@section('title', 'Respond for Evaluation')
@section('page-title', 'Respond for Evaluation')

@section('content')
<div class="bg-white border rounded-3xl p-6">
    <h2 class="text-2xl font-semibold">Evaluation</h2>
    <p class="mt-2 text-gray-600">
        Please fill out the evaluation form below.
    </p>

    <!-- Question 1 -->
    <div class="mt-6">
        <label class="block font-semibold mb-2">Question One</label>
        <input type="text" class="w-full border rounded-lg px-4 py-2" placeholder="Enter your answer here">
    </div>

    <!-- Question 2 -->
    <div class="mt-6">
        <label class="block font-semibold mb-2">Question Two</label>
        <textarea class="w-full border rounded-lg px-4 py-2" rows="3" placeholder="Write your response..."></textarea>
    </div>

    <!-- Multiple Choice -->
    <div class="mt-6">
        <label class="block font-semibold mb-2">Multiple Choice</label>
        <div class="space-y-2">
            <label class="flex items-center space-x-2">
                <input type="radio" name="mcq" value="1">
                <span>Option 1</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="radio" name="mcq" value="2">
                <span>Option 2</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="radio" name="mcq" value="3">
                <span>Option 3</span>
            </label>
        </div>
    </div>

    <!-- True/False -->
    <div class="mt-6">
        <label class="block font-semibold mb-2">True or False</label>
        <div class="space-y-2">
            <label class="flex items-center space-x-2">
                <input type="radio" name="tf" value="true">
                <span>True</span>
            </label>
            <label class="flex items-center space-x-2">
                <input type="radio" name="tf" value="false">
                <span>False</span>
            </label>
        </div>
    </div>

    <!-- Buttons -->
    <div class="mt-8 flex justify-center gap-4">
        <button class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800">
            Submit
        </button>
        <button class="px-6 py-2 bg-gray-300 text-black rounded-full hover:bg-gray-400">
            Cancel
        </button>
    </div>
</div>
@endsection
