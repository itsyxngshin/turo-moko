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
    <div class="w-full max-w-sm px-6">
      <h2 class="text-2xl font-bold mb-1 text-center">Welcome back!</h2>
      <p class="text-gray-600 mb-6 text-center">Login to continue</p>

      <!-- Email Input -->
      <div class="mb-4">
        <label class="block text-sm mb-1" for="email">Email</label>
        <input type="email" id="email" placeholder="Enter your email here" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Password Input -->
      <div class="mb-4">
        <label class="block text-sm mb-1" for="password">Password</label>
        <input type="password" id="password" placeholder="Enter your password here" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Remember me -->
      <div class="flex items-center justify-between mb-4">
        <label class="flex items-center gap-2">
          <input type="checkbox" class="accent-orange-500" />
          <span class="text-sm">Remember me</span>
        </label>
        <a href="#" class="text-sm text-gray-500 hover:underline">Forgot Password?</a>
      </div>

      <!-- Login Button -->
      <button class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 mb-4">Login</button>

      <!-- Divider -->
      <div class="flex items-center gap-2 mb-4">
        <hr class="flex-1 border-gray-300" />
        <span class="text-sm text-gray-500">Or login with</span>
        <hr class="flex-1 border-gray-300" />
      </div>

      <!-- Google Button -->
      <button class="w-full flex items-center justify-center border rounded-md py-2 hover:bg-gray-100 gap-2">
        <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="w-5 h-5" />
        <span class="text-sm">Continue with Google</span>
      </button>

      <!-- Sign up link -->
      <p class="text-sm text-center mt-6 text-gray-600">
        Don’t have an account?
        <!-- Sign Up Button -->
      <a href="{{ route('auth.register') }}" 
        class=" text-sm font-medium text-gray-800 hover:text-orange-500">
        Sign Up here.
      </a>
      </p>
    </div>
  </div>
</div>
@endsection