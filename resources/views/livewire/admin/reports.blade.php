@extends('layouts.admin-layout') 

@section('title', 'Admin Reports')

@section('content')
<div class="flex h-screen bg-[#f2f9fb]">
    <!-- Sidebar -->
    <aside class="w-16 bg-white shadow flex flex-col items-center py-4 space-y-6 rounded-r-2xl">
        <div class="h-10 w-10 rounded-full bg-orange-300 flex items-center justify-center text-white font-bold text-sm">
            TM
        </div>
        <button class="text-gray-600 hover:text-blue-500">
            <x-heroicon-o-light-bulb class="w-6 h-6"/>
        </button>
        <button class="text-gray-600 hover:text-blue-500">
            <x-heroicon-o-home class="w-6 h-6"/>
        </button>
        <button class="text-gray-600 hover:text-blue-500">
            <x-heroicon-o-rectangle-stack class="w-6 h-6"/>
        </button>
        <button class="text-gray-600 hover:text-blue-500">
            <x-heroicon-o-photo class="w-6 h-6"/>
        </button>
        <button class="text-gray-600 hover:text-blue-500">
            <x-heroicon-o-bell class="w-6 h-6"/>
        </button>
        <button class="text-gray-600 hover:text-blue-500">
            <x-heroicon-o-cog-6-tooth class="w-6 h-6"/>
        </button>
        <button class="mt-auto text-gray-600 hover:text-red-500">
            <x-heroicon-o-arrow-left-on-rectangle class="w-6 h-6"/>
        </button>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Course Moderation Panel</h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Search courses"
                           class="rounded-full border-gray-300 pl-4 pr-10 py-2 focus:ring-2 focus:ring-blue-400">
                    <x-heroicon-o-magnifying-glass class="absolute right-3 top-2.5 w-5 h-5 text-gray-400"/>
                </div>
                <button class="p-2 rounded-full hover:bg-gray-200">
                    <x-heroicon-o-bell class="w-6 h-6 text-gray-600"/>
                </button>
                <button class="p-2 rounded-full hover:bg-gray-200">
                    <x-heroicon-o-user-circle class="w-6 h-6 text-gray-600"/>
                </button>
            </div>
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
                    <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Approve Entire Course</button>
                    <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Publish Course</button>
                    <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Archive Course</button>
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
                    <span class="text-sm text-gray-500">Pending Review</span>
                </div>
                <div class="divide-y">
                    <div class="flex justify-between items-center px-6 py-3">
                        <span><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded text-xs">Pending</span> Intro to Topic 1</span>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded text-sm hover:bg-gray-100">View</button>
                            <button class="px-3 py-1 border border-green-500 text-green-600 rounded text-sm hover:bg-green-50">Approve</button>
                            <button class="px-3 py-1 border border-red-500 text-red-600 rounded text-sm hover:bg-red-50">Reject</button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-6 py-3">
                        <span><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded text-xs">Pending</span> Video to Topic 1</span>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded text-sm hover:bg-gray-100">View</button>
                            <button class="px-3 py-1 border border-green-500 text-green-600 rounded text-sm hover:bg-green-50">Approve</button>
                            <button class="px-3 py-1 border border-red-500 text-red-600 rounded text-sm hover:bg-red-50">Reject</button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-6 py-3">
                        <span><span class="bg-yellow-100 text-yellow-600 px-2 py-1 rounded text-xs">Pending</span> Notes for Topic 1</span>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border rounded text-sm hover:bg-gray-100">View</button>
                            <button class="px-3 py-1 border border-green-500 text-green-600 rounded text-sm hover:bg-green-50">Approve</button>
                            <button class="px-3 py-1 border border-red-500 text-red-600 rounded text-sm hover:bg-red-50">Reject</button>
                        </div>
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
    </main>
</div>
@endsection