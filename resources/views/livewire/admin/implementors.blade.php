@extends('layouts.layout') 

@section('title', 'Admin | Implementors')

@section('content')
<div class="flex h-screen p-5 bg-[#f2f9fb]">
    
    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto">
        
    <h1 class="text-2xl font-bold mb-6">Manage Implementors</h1>
    @livewire('admin.implementors-table')

    </main>

</div>
@endsection