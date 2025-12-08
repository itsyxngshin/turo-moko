@section('title', 'Login | TURO-MOKO')

<div class="min-h-screen w-screen flex flex-col md:flex-row overflow-hidden">

    <!-- Left: Image Section -->
    <div class="w-full md:w-1/2 h-64 md:h-screen relative"
         x-data="{ 
            images: [
                '{{ asset('/images/cover.jpg') }}', 
                '{{ asset('/images/cover7.jpg') }}', 
                '{{ asset('/images/cover3.jpg') }}'
            ], 
            index: 0 
         }"
         x-init="setInterval(() => { index = (index + 1) % images.length }, 5000)">
      
        <template x-for="(image, i) in images" :key="i">
            <img 
                :src="image" 
                alt="Login Side"
                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 ease-in-out brightness-50"
                x-show="index === i"
                x-transition:enter="opacity-0"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="opacity-100"
                x-transition:leave-end="opacity-0"
            />
        </template>

        <div class="absolute bottom-6 left-6 text-white text-3xl font-bold flex items-center gap-2">
            <img src="{{ asset('/images/turo_moko_logo_white.png') }}" alt="Turo-Moko Logo" class="w-10 h-10 object-contain" />
            <span class="tracking-wide">TURO-MOKO</span>
        </div>
    </div>

    <!-- Right: Login Form Section -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-white py-12 md:py-0">
        <div class="w-full max-w-sm px-6">

            <h2 class="text-2xl font-bold mb-1 text-center">Welcome back!</h2>
            <p class="text-gray-600 mb-6 text-center">Login to continue</p>

            <form wire:submit.prevent="login">
                <!-- Email Input -->
                <div class="mb-4">
                    <label class="block text-sm mb-1" for="email">Email</label>
                    <input type="email"
                        wire:model.defer="email"
                        id="email"
                        placeholder="Enter your email here"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
                    @error('email')
                        <div class="mt-2 flex items-center gap-2 p-3 rounded-md border border-red-500 bg-red-100 text-red-700 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.662 1.732-3L13.732 4
                                    c-.77-1.338-2.694-1.338-3.464 0L4.34 16c-.77 1.338.192 3 1.732 3z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-4 relative">
                    <label class="block text-sm mb-1" for="password">Password</label>
                    <input 
                        type="{{ $showPassword ? 'text' : 'password' }}"
                        wire:model.defer="password"
                        id="password"
                        placeholder="Enter Password"
                        class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-orange-400"
                    >

                    <!-- Toggle Button  -->
                    <button type="button" 
                            wire:click="togglePassword"
                            class="absolute right-3 top-9 text-gray-500">
                        @if ($showPassword)
                            <i class="fa-solid fa-eye-slash"></i>
                        @else
                            <i class="fa-solid fa-eye"></i>
                        @endif
                    </button>

                    @error('password')
                        <div class="mt-2 p-3 rounded-md border border-red-500 bg-red-100 text-red-700 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.662 1.732-3L13.732 4
                                      c-.77-1.338-2.694-1.338-3.464 0L4.34 16c-.77 1.338.192 3 1.732 3z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <!-- Remember me -->
                <div class="flex flex-col sm:flex-row items-center justify-between mb-4 gap-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox"
                            wire:model="remember"
                            class="rounded-sm accent-orange-500 focus:ring-1 focus:ring-orange-400" />
                        <span class="text-sm">Remember me</span>
                    </label>

                    <a href="{{ route('auth.forget-password') }}" 
                      class="text-sm text-gray-500 hover:underline">Forgot Password?</a>
                </div>

                <!-- Login Button -->
                <button type="submit"
                        class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 mb-4 relative">
                    <span wire:loading.remove wire:target="login">Login</span>
                    <span wire:loading wire:target="login">
                        <i class="fa-solid fa-spinner fa-spin"></i> Logging in...
                    </span>
                </button>

                <!-- Sign up link -->
                <p class="text-sm text-center mt-6 text-gray-600">
                    Don’t have an account?
                    <a href="{{ route('auth.register') }}" 
                      class="text-sm font-medium text-gray-800 hover:text-orange-500">
                      Sign Up here.
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>
