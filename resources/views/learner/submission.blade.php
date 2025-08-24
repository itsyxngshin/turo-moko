@extends('layouts.layout')

@section('title', 'Submission')
@section('page-title', 'Submit Activity')

@section('content')
<div class="bg-white border rounded-3xl p-6">
  <!-- Activity Header -->
  <h2 class="text-2xl font-semibold">Activity name</h2>
  <p class="mt-2"><span class="font-semibold">Opened:</span> Monday, 25 May 2025, 12:00 am</p>
  <p><span class="font-semibold">Due:</span> Friday, 30 May 2025, 12:00 am</p>

  <!-- Description -->
  <hr class="my-4">
  <p class="text-gray-600">
    Activity description here Activity description here Activity description here Activity description here Activity description here Activity description here Activity description here Activity description here
  </p>

  <!-- Submission Form -->
  <hr class="my-4">
  <div class="space-y-6">
    <!-- Online Text -->
    <div>
      <h3 class="font-semibold mb-2">Online text</h3>
      <textarea class="w-full p-4 border rounded-xl focus:ring focus:ring-gray-300" rows="4" placeholder="Type a text"></textarea>
    </div>

    <!-- File Submission -->
    <div>
      <h3 class="font-semibold mb-2">File submission</h3>
      <label class="w-full flex flex-col items-center justify-center border rounded-xl p-6 cursor-pointer hover:bg-gray-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="text-gray-500">Upload file</span>
        <input type="file" class="hidden" />
      </label>
    </div>

    <!-- Button -->
    <div class="flex justify-center">
      <button class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800">
        Add submission
      </button>
    </div>
  </div>
</div>
@endsection
