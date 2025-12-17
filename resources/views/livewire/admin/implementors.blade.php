@extends('layouts.layout') 

@section('title', 'TURO-MOKO Admin')

@section('content')
<div class="flex h-screen p-5 bg-gray">
    
    <!-- Main Content -->
    <main class="flex-1">
        
    <h1 class="text-2xl font-bold mb-6">Manage Implementors</h1>
    @livewire('admin.implementors-table')

    </main>

</div>
@endsection