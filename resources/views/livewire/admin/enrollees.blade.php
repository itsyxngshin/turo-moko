@extends('layouts.admin-layout') 

@section('title', 'Admin | Enrollees')

@section('content')
<div class="min-w-full text-left border-collapse">

    <table class="min-w-full text-left border-collapse">
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

            @forelse ($enrollees as $enrollee)
                <tr class="hover:bg-gray-50 transition-colors">

                    <!-- Photo -->
                    <td class="px-6 py-4">
                        @if($enrollee->profile && $enrollee->profile->photo_id)
                            <img 
                                src="{{ asset('storage/photos/' . $enrollee->profile->photo_id) }}"
                                class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                        @endif
                    </td>

                    <!-- First Name -->
                    <td class="px-6 py-4">
                        {{ $enrollee->profile->first_name ?? '—' }}
                    </td>

                    <!-- Middle Name -->
                    <td class="px-6 py-4">
                        {{ $enrollee->profile->middle_name ?? '—' }}
                    </td>

                    <!-- Last Name -->
                    <td class="px-6 py-4">
                        {{ $enrollee->profile->last_name ?? '—' }}
                    </td>

                    <!-- Actions -->
                    <td class="px-6 py-4 flex flex-wrap gap-3 text-blue-500 mr-16">
                        <button wire:click="view({{ $enrollee->id }})"
                                class="hover:underline text-sm">
                            View
                        </button>

                        <button wire:click="edit({{ $enrollee->id }})"
                                class="hover:underline text-sm">
                            Edit
                        </button>

                        <button wire:click="remove({{ $enrollee->id }})"
                                class="hover:underline text-sm">
                            Remove
                        </button>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                        No enrollees found.
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>

    <!-- Pagination -->
    <div class="px-6 py-4">
        {{ $enrollees->links() }}
    </div>
</div>

@endsection
