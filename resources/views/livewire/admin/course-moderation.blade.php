@extends('layouts.layout') 

@section('title', 'Course Moderation')

@section('content')
<div  class="flex-1 p-8">
        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Course Moderation Panel</h1>
            
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-3 gap-6 mb-6">
            <!-- Course Overview -->
            <div class="bg-white p-4 rounded-xl shadow col-span-2">
                <h2 class="font-semibold mb-2">Course Overview</h2>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <p><span class="font-semibold">Course:</span> Course Title</p>
                    <p><span class="font-semibold">Status:</span> Pending Review</p>
                    <p><span class="font-semibold">Creator:</span> Jane Doe</p>
                    <p><span class="font-semibold">Date Created:</span> 4/20/25</p>
                    <p><span class="font-semibold">Last Updated:</span> 4/22/25</p>
                </div>
                <div class="flex space-x-3 mt-4">
                    <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Hide Course</button> 
                    <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Delete Course</button>
                </div>
            </div>

            <!-- Tutor Assigned -->
            <div class="bg-white p-4 rounded-xl shadow">
                <h2 class="font-semibold">Tutor Assigned</h2>
                <p class="mt-2">Jane Doe</p>
            </div>

            <!-- No. of Enrollees -->
            <div class="bg-white p-4 rounded-xl shadow">
                <h2 class="font-semibold">No. of Enrollees</h2>
                <p class="text-2xl font-bold mt-2">35</p>
            </div>

            <!-- Course Performance -->
            <div class="bg-white p-4 rounded-xl shadow col-span-2">
                <h2 class="font-semibold">Course Performance</h2>
                <div class="mt-4 flex justify-center">
                    <!-- Placeholder performance circle -->
                    <div class="w-24 h-24 rounded-full border-8 border-yellow-400 border-t-red-500"></div>
                </div>
            </div>
        </div>

        <!-- Modules & Content -->
        <div>
            <h2 class="text-xl font-bold mb-4">Modules & Content</h2>

            <!-- Module 1 -->
            <div class="bg-white rounded-xl shadow mb-4">
                <div class="flex justify-between items-center px-6 py-3 border-b">
                    <p class="font-semibold">Module 1 : Topic 1</p>
                    <span class="text-sm text-left text-gray-500">Pending Review</span>
                    <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded text-sm hover:bg-gray-100">View</button>
                            <button class="px-3 py-1 border border-green-500 text-green-600 rounded text-sm hover:bg-green-50">Approve</button>
                            <button class="px-3 py-1 border border-red-500 text-red-600 rounded text-sm hover:bg-red-50">Reject</button>
                        </div>
                </div>
                
            </div>

            <!-- Module 2 -->
            <div class="bg-white rounded-xl shadow mb-4">
                <div class="flex justify-between items-center px-6 py-3 border-b">
                    <p class="font-semibold">Module 2 : Topic 2</p>
                    <span class="text-sm text-gray-500">Pending Review</span>
                </div>
            </div>

            <!-- Module 3 -->
            <div class="bg-white rounded-xl shadow mb-4">
                <div class="flex justify-between items-center px-6 py-3 border-b">
                    <p class="font-semibold">Module 3 : Topic 3</p>
                    <span class="text-sm text-gray-500">Pending Review</span>
                </div>
            </div>
        </div>
   
</div>
@endsection