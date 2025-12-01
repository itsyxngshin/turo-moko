@extends('layouts.main') 

@section('title', 'Reset Password')

@section('content')
<div class="h-screen w-screen flex overflow-hidden">
  <!-- Left: Image Section -->
  <div class="w-1/2 h-full relative"> 
    @livewire('auth.reset')
</div>
@endsection