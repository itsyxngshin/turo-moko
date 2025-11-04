<div class="flex h-screen w-screen overflow-hidden">

    {{-- This Alpine component is left as-is. Paths are fixed with asset() --}}
    <div class="w-1/2 relative hidden md:block"
         x-data="{ images: [
            '{{ asset('/images/cover.jpg') }}', 
            '{{ asset('/images/cover7.jpg') }}', 
            '{{ asset('/images/cover3.jpg') }}'
         ], index: 0 }"
         x-init="setInterval(() => { index = (index + 1) % images.length }, 5000)">
      
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
  
      <div class="absolute bottom-6 left-6 flex items-center gap-3">
        <img src="{{ asset('/images/turo_moko_logo_white.png') }}" alt="Turo-Moko Logo" class="w-10 h-10 object-contain" />
        <span class="text-white text-3xl font-bold">TURO-MOKO</span>
      </div>
    </div>
  
    {{-- Right Column (Form) --}}
    <div class="w-full md:w-1/2 flex justify-center bg-white overflow-y-auto py-12"
        x-on:swal-redirect.window="
            Swal.fire({
                title: event.detail.title,
                text: event.detail.text,
                icon: event.detail.icon,
                timer: 6000,
                timerProgressBar: true,
                showConfirmButton: false
            }).then(() => {
                window.location.href = event.detail.url;
            });
        ">

        {{-- Loading Overlay --}}
        <div wire:loading.flex wire:target="register" 
            class="absolute inset-0 z-50 flex items-center justify-center bg-white bg-opacity-75 backdrop-blur-sm rounded-xl">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-10 w-10 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-3 text-lg font-medium text-orange-600">Processing Registration...</p>
            </div>
        </div>

        <div class="w-full max-w-sm px-4">

            <h2 class="text-2xl font-bold mb-1 text-center">Welcome!</h2>
            <p class="text-sm text-gray-600 text-center mb-6">Register to continue.</p>

            <form wire:submit.prevent="register">
                
                <div class="mb-6">
                    <div class="flex bg-gray-100 rounded-xl p-1 text-sm font-semibold shadow-inner">
                        <button
                            type="button"
                            wire:click="$set('roleName', 'learner')"
                            wire:loading.attr="disabled"
                            wire:target="roleName"
                            class="flex-1 py-2 text-center transition-all duration-200 rounded-lg 
                                   {{ strtolower($roleName) === 'learner' ? 'bg-orange-500 text-white shadow-md' : 'text-gray-600 hover:bg-gray-200' }}"
                        >
                            Learner
                        </button>
                        <button
                            type="button"
                            wire:click="$set('roleName', 'implementor')"
                            wire:loading.attr="disabled"
                            wire:target="roleName"
                            class="flex-1 py-2 text-center transition-all duration-200 rounded-lg 
                                   {{ strtolower($roleName) === 'implementor' ? 'bg-orange-500 text-white shadow-md' : 'text-gray-600 hover:bg-gray-200' }}"
                        >
                            Implementor
                        </button>
                    </div>
                    <p class="mt-2 text-xs text-center text-gray-500">
                        You are registering as a 
                        <span class="font-bold text-orange-600 capitalize">{{ $roleName }}</span>.
                    </p>
                </div>

                <div class="mb-3">
                    <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input 
                        wire:model.live="firstName"
                        type="text" 
                        id="firstName" 
                        placeholder="Juan" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('firstName') border-red-500 @enderror" 
                    />
                    @error('firstName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="middleName" class="block text-sm font-medium text-gray-700">Middle Name <span class="text-gray-400">(Optional)</span></label>
                    <input 
                        wire:model.live="middleName"
                        type="text" 
                        id="middleName" 
                        placeholder="Santos" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('middleName') border-red-500 @enderror" 
                    />
                    @error('middleName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label for="lastName" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input 
                        wire:model.live="lastName"
                        type="text" 
                        id="lastName" 
                        placeholder="dela Cruz" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('lastName') border-red-500 @enderror" 
                    />
                    @error('lastName') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input 
                        wire:model.live="email"
                        type="email" 
                        id="email" 
                        placeholder="Enter your email here" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('email') border-red-500 @enderror" 
                    />
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input 
                        wire:model.live="username"
                        type="text" 
                        id="username" 
                        placeholder="juan_delacruz" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('lastName') border-red-500 @enderror" 
                    />
                    @error('username') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label for="phonenum" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <div class="relative mt-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-lg">🇵🇭</span>
                        <input
                            wire:model.live="phonenum"
                            type="text"
                            id="phonenum"
                            placeholder="+639XX-XXXX-XXX"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('phone_number') border-red-500 @enderror"
                        />
                    </div>
                    @error('phonenum') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input 
                        wire:model.live="password"
                        type="password" 
                        id="password" 
                        placeholder="Enter password" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('password') border-red-500 @enderror" 
                    />
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                    @if ($password)
                    <div class="mt-2" x-data> <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div 
                                class="h-2.5 rounded-full {{ $this->passwordStrength['color'] }} transition-all duration-300" 
                                style="width: {{ $this->passwordStrength['width'] }}"
                            ></div>
                        </div>
                        
                        <p class="text-xs mt-1
                            @if($this->passwordStrength['strength'] == 'Weak') text-red-500 @endif
                            @if($this->passwordStrength['strength'] == 'Medium') text-yellow-600 @endif
                            @if($this->passwordStrength['strength'] == 'Strong') text-green-600 @endif
                        ">
                            Strength: <strong>{{ $this->passwordStrength['strength'] }}</strong>
                        </p>
                    </div>
                    @endif
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>

                    <input 
                        wire:model.live="password_confirmation"
                        type="password" 
                        id="password_confirmation" 
                        placeholder="Confirm password" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('password_confirmation') border-red-500 @enderror" 
                    />
                    @error('password_confirmation') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <button 
                    type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl mb-4 font-bold transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-orange-200"
                    wire:loading.attr="disabled"
                    wire:target="register"
                >
                    <span wire:loading.remove wire:target="register">Sign Up</span>
                    <span wire:loading wire:target="register">
                        {{-- You'll need Font Awesome for this icon. If not, use the SVG spinner --}}
                        <i class="fas fa-spinner fa-spin mr-2"></i> Signing Up...
                    </span>
                </button>

            </form>
            <p class="text-xs text-gray-500 text-center mb-4">
                By continuing, you agree to our
                <a href="#" class="underline font-medium text-orange-500 hover:text-orange-600">Terms Of Service</a> and
                <a href="#" class="underline font-medium text-orange-500 hover:text-orange-600">Privacy Policy</a>.
            </p>

            <button class="w-full border border-gray-300 rounded-xl py-2 flex items-center justify-center gap-2 hover:bg-gray-100 text-sm font-medium mb-4 transition-colors duration-200">
                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google" class="w-5 h-5" />
                Continue with Google
            </button>

            <p class="text-sm text-center text-gray-600">
                Already have an account?
                <a href="{{ route('auth.login') }}"
                    class="text-sm font-medium text-orange-500 hover:text-orange-600 underline">
                    Log In here.
                </a>
            </p>

        </div> 
        </div> {{-- RIGHT COLUMN ENDS HERE --}}
  
</div>