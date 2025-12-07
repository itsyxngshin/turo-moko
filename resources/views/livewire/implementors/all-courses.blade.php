@extends('layouts.layout')

@section('title', 'Show All Courses')
@section('page-title', 'Show All Courses')

@section('content')
<div class="p-0 ml-[20px]">

    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">

    <!-- Page Title -->
    <h2 class="text-xl font-semibold">Your Courses</h2>

    <!-- Right-side Controls -->
    <div class="flex items-center gap-3">

        <!-- View Mode Dropdown -->
        <select 
            class="border rounded-full px-4 py-2 text-sm bg-white shadow-sm cursor-pointer"
        >
            <option value="card">Card View</option>
            <option value="list">List View</option>
        </select>

        <!-- Create Course Button -->
<button 
    wire:click="$dispatch('openModal', { component: 'modals.implementor.create-course' })"
    class="bg-black text-white px-5 py-2 rounded-full text-sm font-medium shadow-sm hover:bg-gray-900 transition"
>
    Create Course
</button>


        <!-- Modal Component -->
        <livewire:modals.implementor.create-course />
    </div>

</div>


    <!-- Course Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Course Card -->
        <div class="bg-white rounded-2xl shadow-md border overflow-hidden">
            <div class="grid md:grid-cols-[160px,1fr] gap-4 p-4 items-start">

                <img
                    src="https://via.placeholder.com/400x200"
                    alt="Course"
                    class="w-full h-[140px] object-cover rounded-xl"
                >

                <div class="flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">1st Sem SY 2024-2025</p>
                        <h3 class="font-semibold text-lg">Course Name</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit ut labore et dolore magna aliqua.
                        </p>
                    </div>

                    <div class="mt-3">
                        <button class="bg-black text-white px-4 py-1 rounded-full text-sm">
                            View
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Duplicate -->
        <div class="bg-white rounded-2xl shadow-md border overflow-hidden">
            <div class="grid md:grid-cols-[160px,1fr] gap-4 p-4 items-start">

                <img
                    src="https://via.placeholder.com/400x200"
                    alt="Course"
                    class="w-full h-[140px] object-cover rounded-xl"
                >

                <div class="flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">1st Sem SY 2024-2025</p>
                        <h3 class="font-semibold text-lg">Course Name</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit ut labore et dolore magna aliqua.
                        </p>
                    </div>

                    <div class="mt-3">
                        <button class="bg-black text-white px-4 py-1 rounded-full text-sm">
                            View
                        </button>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection
