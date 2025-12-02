@extends('layouts.layout2') 
@section('title', 'Login')

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
    <div class="w-full max-w-sm px-6">
      <h2 class="text-2xl font-bold mb-1 text-center">Welcome!</h2>
      <p class="text-sm text-gray-600 text-center mb-6">Register to continue.</p>

      <!-- Role Switch -->
      <div class="mb-6">
        <div class="flex bg-gray-100 rounded-lg overflow-hidden text-sm font-medium">
          <button
            @click="role = 'implementor'"
            :class="role === 'implementor' ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-200'"
            class="flex-1 py-2 text-center"
          >
            Implementor
          </button>
          <button
            @click="role = 'learner'"
            :class="role === 'learner' ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-200'"
            class="flex-1 py-2 text-center"
          >
            Learner
          </button>
        </div>
        <input type="hidden" name="role" :value="role">
      </div>

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="block text-sm font-medium">Email</label>
        <input type="email" id="email" placeholder="Enter your email here" class="w-full mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Phone -->
      <div class="mb-3">
        <label for="phone" class="block text-sm font-medium">Phone Number</label>
        <div class="relative mt-1">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3">🇵🇭</span>
          <input
            type="text"
            id="phone"
            placeholder="+63XXX-XXXX-XXX"
            class="w-full pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400"
          />
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="block text-sm font-medium">Password</label>
        <input type="password" id="password" placeholder="Enter password" class="w-full mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Confirm Password -->
      <div class="mb-4">
        <label for="confirmPassword" class="block text-sm font-medium">Confirm Password</label>
        <input type="password" id="confirmPassword" placeholder="Confirm password" class="w-full mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Sign In button -->
      <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-md mb-3 font-medium">
        Sign In
      </button>

      <!-- Terms and Privacy -->
      <p class="text-xs text-gray-500 text-center mb-4">
        By continuing, you agree to our
        <a href="#" class="underline">Terms Of Service</a> and
        <a href="#" class="underline">Privacy Policy</a>.
      </p>

      <!-- Google Login -->
      <button class="w-full border rounded-md py-2 flex items-center justify-center gap-2 hover:bg-gray-100 text-sm mb-4">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="w-5 h-5" />
        Continue with Google
      </button>

      <!-- Login link -->
      <p class="text-sm text-center text-gray-600">
        Already have an account?
        <!-- Log In Button -->
      <a href="{{ route('auth.login') }}" 
        class="text-sm font-medium text-gray-800 hover:text-orange-500">
        Log In here.
      </a>
      </p>
    </div>
  </div>
</div>
@endsection