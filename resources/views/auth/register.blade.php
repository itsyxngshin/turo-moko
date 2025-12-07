@extends('layouts.main') 
@section('title', 'Register')

@section('content')
<div class="flex h-screen w-screen overflow-hidden font-sans">

  <!-- Left side: Image + Logo -->
<div class="w-1/2 relative hidden md:block"
     x-data="{ images: ['/images/cover.jpg', '/images/cover7.jpg', '/images/cover3.jpg'], index: 0 }"
     x-init="setInterval(() => { index = (index + 1) % images.length }, 5000)">
  
  <!-- Loop through images -->
  <template x-for="(image, i) in images" :key="i">
    <img 
      :src="image" 
      alt="Students using computer"
      class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 ease-in-out brightness-50"
      x-show="index === i"
      x-transition:enter="opacity-0"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="opacity-100"
      x-transition:leave-end="opacity-0"
    />
  </template>

  <!-- Logo -->
  <div class="absolute bottom-6 left-6 flex items-center gap-3">
    <img src="/images/turo_moko_logo_white.png" alt="Turo-Moko Logo" class="w-10 h-10 object-contain" />
    <span class="text-white text-3xl font-bold">TURO-MOKO</span>
  </div>
</div>

  <!-- Right side: Form -->
  <div class="w-full md:w-1/2 flex items-center justify-center bg-white">
    @livewire('auth.register')
  </div>
</div>
@endsection