@extends('layouts.layout')

@section('title', 'General Settings')

@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-2xl shadow-md border border-gray-200 p-8">
    <h2 class="text-2xl font-semibold mb-6">General Settings</h2>

    <form class="space-y-6">
        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input type="text" id="username" name="username" value="Username123"
                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-black focus:ring-black p-3" disabled>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" value="user@example.com"
                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-black focus:ring-black p-3" disabled>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" name="password" value="••••••••"
                class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-black focus:ring-black p-3" disabled>
        </div>

        <!-- Save Button -->
        <div class="flex justify-center pt-4">
            <button type="button"
                class="px-6 py-3 bg-black text-white rounded-full hover:bg-gray-800 cursor-not-allowed">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
