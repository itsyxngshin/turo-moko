<div class="flex h-screen w-screen overflow-hidden">

    {{-- LEFT COLUMN (HIDDEN ON MOBILE) --}}
    <div 
        class="hidden md:block w-1/2 relative"
        x-data="{ images: [
            '{{ asset('/images/cover.jpg') }}', 
            '{{ asset('/images/cover7.jpg') }}', 
            '{{ asset('/images/cover3.jpg') }}'
        ], index: 0 }"
        x-init="setInterval(() => { index = (index + 1) % images.length }, 5000)"
    >
        <template x-for="(image, i) in images" :key="i">
            <img 
                :src="image" 
                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 ease-in-out brightness-50"
                x-show="index === i"
                x-transition:enter="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="opacity-100"
                x-transition:leave-end="opacity-0"
            />
        </template>

        <div class="absolute bottom-6 left-6 flex items-center gap-3">
            <img src="{{ asset('/images/turo_moko_logo_white.png') }}" class="w-10 h-10 object-contain" />
            <span class="text-white text-3xl font-bold">TURO-MOKO</span>
        </div>
    </div>

    {{-- RIGHT COLUMN --}}
    <div class="w-full md:w-1/2 bg-white flex justify-center overflow-y-auto py-10 px-4 md:px-8">

        {{-- LOADING OVERLAY --}}
        <div wire:loading.flex wire:target="register" 
            class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-75 backdrop-blur-sm">
            <div class="flex flex-col items-center">
                <svg class="animate-spin h-10 w-10 text-orange-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 
                        5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 
                        3 7.938l3-2.647z">
                    </path>
                </svg>
                <p class="mt-3 text-lg font-medium text-orange-600">Processing Registration...</p>
            </div>
        </div>

        {{-- FORM WRAPPER --}}
        <div class="w-full max-w-sm">

            <h2 class="text-2xl font-bold mb-1 text-center">Welcome!</h2>
            <p class="text-sm text-gray-600 text-center mb-6">Register to continue.</p>

            <form wire:submit.prevent="register">
                {{-- 
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

                --}}

                <div class="mb-3">
                    <label for="firstName" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input 
                        wire:model.blur="firstName"
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
                        wire:model.blur="middleName"
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
                        wire:model.blur="lastName"
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
                        wire:model.blur="email"
                        type="email" 
                        id="email" 
                        placeholder="example@turo-moko.com" 
                        class="w-full mt-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('email') border-red-500 @enderror" 
                    />
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mb-3">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input 
                        wire:model.blur="username"
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
                            wire:model.blur="phonenum"
                            type="text"
                            id="phonenum"
                            placeholder="+639XXXXXXXXX"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('phone_number') border-red-500 @enderror"
                        />
                    </div>
                    @error('phonenum') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- PASSWORD FIELD --}}
                <div class="mb-3">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    
                    <div class="relative mt-1" x-data="{ show: false }">
                        <input 
                            wire:model.live.debounce.500ms="password"
                            :type="show ? 'text' : 'password'" 
                            id="password" 
                            placeholder="Enter password" 
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('password') border-red-500 @enderror" 
                        />
                        
                        {{-- TOGGLE BUTTON WITH SVG ICONS --}}
                        <button 
                            type="button" 
                            @click="show = !show" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-orange-500 focus:outline-none"
                        >
                            {{-- Eye Icon (Show when password is hidden) --}}
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>

                            {{-- Eye Slash Icon (Show when password is visible) --}}
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    
                    {{-- Error & Strength Meter --}}
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                    @if ($password)
                        <div class="mt-2" x-data> 
                            <div class="w-full bg-gray-200 rounded-full h-2.5">
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

                {{-- CONFIRM PASSWORD FIELD --}}
                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>

                    <div class="relative mt-1" x-data="{ show: false }">
                        <input 
                            wire:model.blur="password_confirmation"
                            :type="show ? 'text' : 'password'" 
                            id="password_confirmation" 
                            placeholder="Confirm password" 
                            class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400 @error('password_confirmation') border-red-500 @enderror" 
                        />

                        {{-- TOGGLE BUTTON WITH SVG ICONS --}}
                        <button 
                            type="button" 
                            @click="show = !show" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-orange-500 focus:outline-none"
                        >
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
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

            <p class="text-sm text-center text-gray-600 pb-20">
                Already have an account?
                <a href="{{ route('auth.login') }}"
                    class="text-sm font-medium text-orange-500 hover:text-orange-600 underline">
                    Log In here.
                </a>
            </p>

        </div> 
    </div> {{-- RIGHT COLUMN ENDS HERE --}}
</div>