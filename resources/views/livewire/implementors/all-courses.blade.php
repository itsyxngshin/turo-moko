@extends('layouts.layout')

@section('title', 'Show All Courses')
@section('page-title', 'Show All Courses')

@section('content')
<div class="p-0 ml-[20px]">
   
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-semibold">Your courses</h2>
    <div class="flex gap-2 items-center">
      <livewire:modals.implementor.create-course />

      <select class="border cursor-pointer rounded-lg p-2 text-sm">
        <option>All</option>
      </select>
      <input type="text" placeholder="Search" class="border rounded-lg p-2 text-sm" />
      <select class="border cursor-pointer rounded-lg p-2 text-sm">
        <option>Card</option>
      </select>
    </div>
  </div>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Course Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border">
      <img src="https://via.placeholder.com/400x200" alt="Course" class="w-full h-40 object-cover">
      <div class="p-4">
        <p class="text-xs text-gray-400 mb-1">1st Sem SY 2024-2025</p>
        <h3 class="font-semibold">Course Name</h3>
        <p class="text-sm text-gray-500 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut labore et dolore magna aliqua.</p>
        <button class="bg-black text-white px-4 py-1 rounded-full text-sm">View</button>
      </div>
    </div>
    <!-- Duplicate this block for other courses -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border">
      <img src="https://via.placeholder.com/400x200" alt="Course" class="w-full h-40 object-cover">
      <div class="p-4">
        <p class="text-xs text-gray-400 mb-1">1st Sem SY 2024-2025</p>
        <h3 class="font-semibold">Course Name</h3>
        <p class="text-sm text-gray-500 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit ut labore et dolore magna aliqua.</p>
        <button class="bg-black text-white px-4 py-1 rounded-full text-sm">View</button>
      </div>
    </div>
  </div>
</div>
@endsection
