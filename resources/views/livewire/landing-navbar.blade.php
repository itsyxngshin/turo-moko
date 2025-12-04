<div 
    id="navbar"
    x-data="{ scrolled: false, active: 'home' }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
    :class="scrolled ? 'bg-white/95' : 'bg-white/70'"
    class="fixed top-0 left-0 w-full backdrop-blur-md py-3 px-11 pt-4 rounded-b-2xl flex items-center justify-between z-50 shadow-md transition-all duration-300">

    <!-- Left: Logo -->
    <div class="flex items-center gap-5">
        <img src="/images/turo_moko_logo.png" alt="TuroMoko Logo" class="h-10 w-10">
        <span class="text-xl font-semibold text-orange-500">TuroMoko</span>
    </div>

    <!-- Center: Navigation Links -->
    <div class="flex-1 flex justify-center">
        <div class="hidden md:flex gap-8 text-sm font-medium items-center">
        <a 
            href="#home" 
            @click="active = 'home'" 
            :class="active === 'home' 
            ? 'text-orange-500 border border-orange-400 px-3 py-1 rounded-full' 
            : 'text-gray-700 hover:text-orange-500 px-3 py-1 rounded-full'">
            Dashboard
        </a>
        <a 
            href="#courses" 
            @click="active = 'courses'" 
            :class="active === 'courses' 
            ? 'text-orange-500 border border-orange-400 px-3 py-1 rounded-full' 
            : 'text-gray-700 hover:text-orange-500 px-3 py-1 rounded-full'">
            Courses
        </a>
        <a 
            href="#community" 
            @click="active = 'community'" 
            :class="active === 'community' 
            ? 'text-orange-500 border border-orange-400 px-3 py-1 rounded-full' 
            : 'text-gray-700 hover:text-orange-500 px-3 py-1 rounded-full'">
            Community
        </a>
        <a 
            href="#about" 
            @click="active = 'about'" 
            :class="active === 'about' 
            ? 'text-orange-500 border border-orange-400 px-3 py-1 rounded-full' 
            : 'text-gray-700 hover:text-orange-500 px-3 py-1 rounded-full'">
            About
        </a>
        </div>
    </div>

    <!-- Right: Login/Signup or User Dropdown -->
    <div class="flex items-center gap-4 relative">
        @auth
            <div x-data="{ open: false }" class="relative text-orange-500 border border-orange-600 shadow-md px-3 py-2 rounded-lg">
                
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none">
                    <img src="{{ Auth::user()->profile && Auth::user()->profile->photo
                                ? 'data:image/jpeg;base64,' . base64_encode(Auth::user()->profile->photo->photos)
                                : asset('images/turo_moko_logo.png') }}"
                    alt="Profile Photo"
                    class="w-8 h-8 rounded-full object-cover shrink-0">

                    <div class="hidden md:flex flex-col items-start text-left w-28">
                        
                        <span class="text-sm font-semibold text-gray-800 hover:text-orange-500 truncate w-full" 
                              title="{{ Auth::user()->profile->first_name }} {{ Auth::user()->profile->last_name }}">
                            {{ Auth::user()->profile->first_name }} {{ Auth::user()->profile->last_name }}
                        </span>
                        
                        <span class="text-xs text-gray-500 truncate w-full">
                            {{ Auth::user()->role->role_name ?? 'User' }}
                        </span>

                    </div>
                    
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open"
                    @click.away="open = false"
                    x-transition
                    class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50"
                    style="display: none;">

                    {{-- Standard Links --}}
                    <a href="{{ $this->profileUrl }}" wire:navigate class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-600 transition-colors">
                        Profile
                    </a>
                    
                     <a href="{{ $this->dashboardUrl }}" 
                        wire:navigate 
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-600 transition-colors">
                            Dashboard
                    </a>

                    {{-- ========================================== --}}
                    {{-- NEW: ADMIN SPECIFIC LINKS                  --}}
                    {{-- ========================================== --}}
                    @if(Auth::user()->role->role_name === 'admin')
                        <div class="border-t border-gray-100 my-1"></div>
                        <div class="px-4 py-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Management
                        </div>

                        <a href="{{ route('admin.course-moderation') }}" {{-- Ensure this route exists --}}
                           wire:navigate
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-600 transition-colors">
                            Content Moderation
                        </a>

                        <a href="{{ route('admin.courses') }}" {{-- Ensure this route exists --}}
                           wire:navigate
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-600 transition-colors">
                            List of Courses
                        </a>

                        <a href="{{ route('admin.enrollees') }}" {{-- Ensure this route exists --}}
                           wire:navigate
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-600 transition-colors">
                            Enrollees
                        </a>

                        <a href="{{ route('admin.implementors') }}" {{-- Ensure this route exists --}}
                           wire:navigate
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-600 transition-colors">
                            Implementors
                        </a>
                    @endif
                    
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600 transition-colors" type="submit">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('auth.login') }}" class="text-sm font-medium text-gray-800 hover:text-orange-500">
                Log In
            </a>
            <a href="{{ route('auth.register') }}" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold py-2 px-4 rounded-full">
                Sign Up
            </a>
        @endauth
    </div>
</div>