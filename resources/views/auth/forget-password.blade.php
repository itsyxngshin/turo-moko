@extends('layouts.main') 

@section('title', 'Login')

@section('content')
<div class="h-screen w-screen flex overflow-hidden">
  <!-- Left: Image Section -->
  <div class="w-1/2 h-full relative"> 
    @livewire('auth.forget-password')
</div>
@endsection