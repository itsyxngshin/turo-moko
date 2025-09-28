@extends('layouts.main') 

@section('title', 'Login')

@section('content')
<div class="h-screen w-screen flex overflow-hidden">
  <!-- Left: Image Section -->
  <div class="w-1/2 h-full relative"
     x-data="{ 
         images: [@js(asset('images/cover.jpg')), @js(asset('images/cover7.jpg')), @js(asset('images/cover3.jpg'))], 
         index: 0 
     }"
     x-init="setInterval(() => { index = (index + 1) % images.length }, 5000)">
    
      <template x-for="(image, i) in images" :key="i">
        <img 
          :src="image" 
          class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 ease-in-out brightness-50"
          x-show="index === i"
          x-transition
        />
      </template>

      <div class="absolute bottom-6 left-6 text-white text-3xl font-bold flex items-center gap-2">
          <img src="{{ asset('images/turo_moko_logo_white.png') }}" alt="Logo" class="w-10 h-10 object-contain">
          <span class="tracking-wide">TURO-MOKO</span>
      </div>
  </div>

  <!-- Right: Login Form Section -->
  <div class="w-1/2 flex items-center justify-center bg-white">
    @livewire('auth.login')
  </div>
</div>
@endsection