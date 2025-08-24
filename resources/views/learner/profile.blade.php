<!-- resources/views/learner/dashboard.blade.php -->
@extends('layouts.layout')

@section('title', 'Learner Profile')

@section('content')
<div class="flex h-screen">
    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-auto">
        <main class="px-8 pb-8">
            <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
                <div class="flex items-center space-x-6">
                    <img src="https://i.imgur.com/ZcLLrkY.png" alt="Profile" class="w-24 h-24 rounded-full object-cover">
                    <div>
                        <h2 class="text-xl font-semibold">Student name</h2>
                        <p class="text-gray-500 text-sm">Any description here</p>
                        <p class="text-gray-500 text-sm">student.name@example.bicol-u.edu.ph</p>
                    </div>
                </div>
            </div>

            <div class="flex space-x-4 mb-6">
                <div class="flex-1 bg-white p-6 rounded-xl shadow-sm text-center">
                    <div class="text-2xl mb-2 text-indigo-600">📘</div>
                    <h3 class="font-semibold">Active courses</h3>
                    <p class="text-gray-500 text-sm">5</p>
                </div>
                <div class="flex-1 bg-white p-6 rounded-xl shadow-sm text-center">
                    <div class="text-2xl mb-2 text-yellow-600">📁</div>
                    <h3 class="font-semibold">Archived courses</h3>
                    <p class="text-gray-500 text-sm">5</p>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Your courses</h3>
                    <button class="text-sm text-indigo-600 hover:underline">View all</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl shadow-sm p-4 flex space-x-4">
                        <img src="https://i.imgur.com/3Y1cO1X.png" alt="Course" class="w-32 h-24 object-cover rounded-lg">
                        <div class="flex-1">
                            <p class="text-xs text-gray-400">1st Sem SY 2024-2025</p>
                            <h4 class="font-semibold">Course Name</h4>
                            <p class="text-sm text-gray-500 line-clamp-2">Lorem ipsum dolor sit amet consectetur adipiscing elit...</p>
                            <div class="mt-2 flex items-center justify-between">
                                <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                                    <div class="bg-black h-full w-1/2"></div>
                                </div>
                                <button class="text-sm text-white bg-black rounded px-4 py-1 ml-4">Continue</button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm p-4 flex space-x-4">
                        <img src="https://i.imgur.com/D9g1T2V.png" alt="Course" class="w-32 h-24 object-cover rounded-lg">
                        <div class="flex-1">
                            <p class="text-xs text-gray-400">1st Sem SY 2024-2025</p>
                            <h4 class="font-semibold">Course Name</h4>
                            <p class="text-sm text-gray-500 line-clamp-2">Lorem ipsum dolor sit amet consectetur adipiscing elit...</p>
                            <div class="mt-2 flex items-center justify-between">
                                <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                                    <div class="bg-black h-full w-1/2"></div>
                                </div>
                                <button class="text-sm text-white bg-black rounded px-4 py-1 ml-4">Continue</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
