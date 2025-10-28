@extends('layouts.admin-layout') 

@section('title', 'Admin | Implementors')

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
    <main class="flex-1 p-8 overflow-y-auto">
    <h1 class="text-2xl font-bold mb-6">Manage Implementors</h1>
    @livewire('admin.implementors-table')
</main>

</div>
@endsection