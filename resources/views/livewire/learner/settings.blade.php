@extends('layouts.layout4')

@section('title', 'General Settings')

@section('content')
<div class="flex justify-center items-center min-h-[calc(100vh-100px)] w-full bg-gray-50">
    <div class="w-full max-w-5xl bg-white rounded-3xl shadow-sm border border-gray-200 p-10">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="https://via.placeholder.com/100" alt="Profile Picture"
                        class="w-24 h-24 rounded-full border-4 border-orange-400 object-cover">
                    <span class="absolute bottom-2 right-2 w-4 h-4 bg-green-400 border-2 border-white rounded-full"></span>
                </div>
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">John Doe</h2>
                    <p class="text-gray-500">Learner • Joined March 15, 2024</p>
                </div>
            </div>
            <button
                class="flex items-center gap-2 px-5 py-2.5 bg-orange-400 text-white rounded-full hover:bg-orange-500 transition">
                <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Profile
            </button>
        </div>

        <!-- Info Section -->
        <div class="grid grid-cols-2 gap-6">
            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Username</label>
                <div class="mt-1 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-800">
                    Username123
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Email Address</label>
                <div class="mt-1 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-800">
                    user@example.com
                </div>
            </div>

            <!-- Contact Number -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Contact Number</label>
                <div class="mt-1 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-800">
                    +63 912 345 6789
                </div>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Password</label>
                <div class="mt-1 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-800">
                    ••••••••
                </div>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Account Type</label>
                <div class="mt-1 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-800">
                    Learner
                </div>
            </div>

            <!-- Student ID -->
            <div>
                <label class="block text-sm font-medium text-gray-600">Student ID</label>
                <div class="mt-1 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-800">
                    TM-2024-01234
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-100 my-8"></div>

        <!-- Additional Info -->
        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-600">Last Login</label>
                <div class="mt-1 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-gray-800">
                    October 3, 2025 – 8:45 PM
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600">Account Status</label>
                <div class="mt-1 w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-green-600 font-semibold">
                    Active
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        lucide.createIcons();
    });
</script>
@endsection
