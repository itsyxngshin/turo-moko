<div class="w-full max-w-sm px-6">
      <h2 class="text-2xl font-bold mb-1 text-center">Welcome back!</h2>
      <p class="text-gray-600 mb-6 text-center">Login to continue</p>

      <form wire:submit.prevent="login">
        @csrf
        <!-- Email Input -->
        <div class="mb-4">
          <label class="block text-sm mb-1" for="email">Email</label>
          <input type="email" 
                 wire:model="email" 
                 id="email" 
                 placeholder="Enter your email here" 
                 class=" w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
          @error('email')
                <div 
                    x-show="showError" 
                    x-transition 
                    class="p-3 rounded-md border border-red-500 bg-red-100 text-red-700 text-sm"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.662 1.732-3L13.732 4c-.77-1.338-2.694-1.338-3.464 0L4.34 16c-.77 1.338.192 3 1.732 3z"/>
                </svg>
                <span>{{ $message }} </span> 
                </div>
            @enderror
        </div>

        <!-- Password Input with Alpine toggle -->
        <div class="mb-4">
          <label class="block text-sm mb-1" for="password">Password</label>
          <input type="password"
                id="password"
                wire:model="password"
                placeholder="Enter password"
                class="w-full px-4 py-2 border rounded-md">

          <div 
            x-data="{ showError: @entangle('showError').defer }" 
            class="mt-2"
            >
            @error('password')
                <div 
                    x-show="showError" 
                    x-transition 
                    class="p-3 rounded-md border border-red-500 bg-red-100 text-red-700 text-sm"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.662 1.732-3L13.732 4c-.77-1.338-2.694-1.338-3.464 0L4.34 16c-.77 1.338.192 3 1.732 3z"/>
                </svg>
                <span>{{ $message }} </span> 
                </div>
            @enderror
          </div>
        </div>

        <!-- Remember me -->
        <div class="flex items-center justify-between mb-4">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" 
                  class="rounded-sm accent-orange-500 focus:ring-1 focus:ring-orange-400"
                  wire:model="remember" />
            <span class="text-sm">Remember me</span>
          </label>

          <a href="#" 
             class="text-sm text-gray-500 hover:underline">Forgot Password?</a>
        </div>

        <!-- Login Button -->
        <button type="submit"
              class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 mb-4">Login
        </button>

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
          <a href="{{ route('auth.register') }}" 
            class="text-sm font-medium text-gray-800 hover:text-orange-500">
            Sign Up here.
          </a>
        </p>
      </form>
</div>