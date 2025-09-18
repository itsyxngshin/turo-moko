@extends('layouts.admin-layout') 

@section('title', 'Admin | Enrollees')

@section('content')
<div class="flex h-screen bg-[#f2f9fb]">
    <!-- Sidebar -->
    <aside class="w-16 bg-white shadow flex flex-col items-center py-4 space-y-6 rounded-r-2xl">
      <!-- Logo -->
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
    <main class="flex-1 p-8">
        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Manage Enrollees</h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Search courses"
                           class="rounded-full border-gray-300 pl-4 pr-10 py-2 focus:ring-2 focus:ring-blue-400"
                           wire:model="search">
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

        <!-- Add Enrollees Button -->
        <div class="flex justify-end mb-4">
            <button class="px-4 py-2 bg-white rounded-lg shadow border flex items-center space-x-2 hover:bg-gray-100">
                <x-heroicon-o-plus class="w-5 h-5"/>
                <span>Add Enrollees</span>
            </button>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Affiliation</th>
                        <th class="px-6 py-3">Enrollee ID</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Courses Enrolled</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">Jane Doe</td>
                        <td class="px-6 py-4">World Vision</td>
                        <td class="px-6 py-4">001</td>
                        <td class="px-6 py-4 text-yellow-600">Pending</td>
                        <td class="px-6 py-4">Living in the IT Era</td>
                        <td class="px-6 py-4 text-blue-500 flex space-x-4">
                                <button class="hover:underline">View</button>
                                <button class="hover:underline">Edit</button>
                                <button class="hover:underline">Remove</button>
                            </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">Jane Doe</td>
                        <td class="px-6 py-4">World Vision</td>
                        <td class="px-6 py-4">001</td>
                        <td class="px-6 py-4 text-yellow-600">Pending</td>
                        <td class="px-6 py-4">Living in the IT Era</td>
                        <td class="px-6 py-4 text-blue-500 flex space-x-4">
                                <button class="hover:underline">View</button>
                                <button class="hover:underline">Edit</button>
                                <button class="hover:underline">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>
@endsection