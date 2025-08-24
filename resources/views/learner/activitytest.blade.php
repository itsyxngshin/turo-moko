@extends('layouts.layout')

@section('title', 'Activity Page')

@section('content')
    <div class="p-0 ml-[20px]">
        <div class="bg-white rounded-2xl shadow-md p-8">

            <!-- Activity Header -->
            <h1 class="text-2xl font-bold mb-2">Activity name</h1>
            <p class="text-sm text-gray-600">
                <strong>Opened:</strong> Monday, 25 May 2025, 12:00 am <br>
                <strong>Due:</strong> Friday, 30 May 2025, 12:00 am
            </p>

            <!-- Activity Description -->
            <p class="mt-4 text-gray-700 text-sm leading-relaxed">
                Activity description here Activity description here Activity description here 
                Activity description here Activity description here Activity description here 
                Activity description here Activity description here
            </p>

            <!-- Divider -->
            <hr class="my-6">

            <!-- Submission Status -->
            <h2 class="text-lg font-semibold mb-4">Submission status</h2>

            <div class="overflow-x-auto">
                <table class="w-full border rounded-lg text-sm">
                    <tbody>
                        <tr class="border-b">
                            <td class="p-3 font-medium w-1/3">Submission status</td>
                            <td class="p-3">Submitted for grading</td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-3 font-medium">Grading status</td>
                            <td class="p-3">Not graded</td>
                        </tr>
                        <tr class="border-b">
                            <td class="p-3 font-medium">Time remaining</td>
                            <td class="p-3 text-green-600">Assignment was submitted 4 days early</td>
                        </tr>
                        <tr>
                            <td class="p-3 font-medium">Submission type</td>
                            <td class="p-3">
                                Online text <br>
                                File submission
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Buttons -->
            <div class="flex gap-4 mt-6">
                <button class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800">
                    Edit submission
                </button>
                <button class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800">
                    Remove submission
                </button>
            </div>

        </div>
    </div>
@endsection
