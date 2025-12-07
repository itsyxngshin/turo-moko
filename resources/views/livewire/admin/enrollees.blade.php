@extends('layouts.admin-layout') 

@section('title', 'Admin | Enrollees')

@section('content')
<div class="max-w-[1720px] mx-auto px-6">

    <!-- Search Bar -->
    <div class="mt-6 flex justify-end">
        <input type="text" placeholder="Search enrollees..."
               class="rounded-full border-gray-300 pl-4 pr-10 py-2 focus:ring-2 focus:ring-yellow-400">
    </div>

    <!-- Enrollees Table -->
    <div class="mt-4 bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                <tr>
                    <th class="px-6 py-3">Photo</th>
                    <th class="px-6 py-3">First Name</th>
                    <th class="px-6 py-3">Middle Name</th>
                    <th class="px-6 py-3">Last Name</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <!-- Example Row -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                    </td>
                    <td class="px-6 py-4">Jane</td>
                    <td class="px-6 py-4">A.</td>
                    <td class="px-6 py-4">Doe</td>
                    <td class="px-6 py-4 flex space-x-4 text-blue-500">
                        <button class="hover:underline">View</button>
                        <button class="hover:underline">Edit</button>
                        <button class="hover:underline">Remove</button>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                    </td>
                    <td class="px-6 py-4">John</td>
                    <td class="px-6 py-4">B.</td>
                    <td class="px-6 py-4">Smith</td>
                    <td class="px-6 py-4 flex space-x-4 text-blue-500">
                        <button class="hover:underline">View</button>
                        <button class="hover:underline">Edit</button>
                        <button class="hover:underline">Remove</button>
                    </td>
                </tr>
                <!-- No data placeholder -->
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No enrollees found.</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection