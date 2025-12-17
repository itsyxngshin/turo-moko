@extends('layouts.layout')  

@section('title', 'Admin | Courses')
@section('page-title', 'Courses')

@section('content')

<div class="flex h-screen px-5 bg-gray">
    
    <!-- Main Content -->
    <main class="flex-1">
        
    <h1 class="text-2xl font-bold mb-6">Manage Courses</h1>
    @livewire('admin.courses-table')

    </main>

</div>
@endsection
