<div 
    id="navbar"
    x-data="{
        scrolled: false, 
        active: 'home', 
        mobileOpen: false,
        scrollToSection(id) {
            const el = document.getElementById(id);
            if(el) {
                const yOffset = -80; // navbar height offset
                const y = el.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        }
    }"
    x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 50 })"
    :class="scrolled ? 'bg-white/95 shadow-md' : 'bg-white/70'"
    class="fixed top-0 left-0 w-full backdrop-blur-md py-3 px-2 md:px-10 md:rounded-b-2xl flex items-center justify-between z-50 transition-all duration-300"
>

    <!-- Left: Logo -->
    <div class="flex items-center gap-3">
        <img src="/images/turo_moko_logo.png" alt="TuroMoko Logo" class="h-10 w-10">
        <span class="text-xl font-semibold text-orange-500">TuroMoko</span>
    </div>

    <!-- Center: Navigation Links -->
    <div class="flex-1 flex justify-center">
        <!-- Desktop Links -->
        <div class="hidden md:flex gap-6 text-sm font-medium items-center">
            <a href="#home" @click.prevent="scrollToSection('home'); active='home'" 
               :class="active==='home' ? 'text-orange-500 border border-orange-400 px-3 py-1 rounded-full' : 'text-gray-700 hover:text-orange-500 px-3 py-1 rounded-full'">
               Dashboard
            </a>
            <a href="#courses" @click.prevent="scrollToSection('courses'); active='courses'" 
               :class="active==='courses' ? 'text-orange-500 border border-orange-400 px-3 py-1 rounded-full' : 'text-gray-700 hover:text-orange-500 px-3 py-1 rounded-full'">
               Courses
            </a>
            <a href="#community" @click.prevent="scrollToSection('community'); active='community'" 
               :class="active==='community' ? 'text-orange-500 border border-orange-400 px-3 py-1 rounded-full' : 'text-gray-700 hover:text-orange-500 px-3 py-1 rounded-full'">
               Community
            </a>
            <a href="#about" @click.prevent="scrollToSection('about'); active='about'" 
               :class="active==='about' ? 'text-orange-500 border border-orange-400 px-3 py-1 rounded-full' : 'text-gray-700 hover:text-orange-500 px-3 py-1 rounded-full'">
               About
            </a>
        </div>
    </div>

    <!-- Right: User/Auth Links + Mobile Toggle -->
    <div class="flex items-center gap-3 relative">

        <!-- Mobile Menu Button -->
        <button class="md:hidden p-2 rounded-md text-gray-700 hover:text-orange-500 focus:outline-none" 
                @click="mobileOpen = !mobileOpen">
            <svg x-show="!mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
            </svg>
            <svg x-show="mobileOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        @auth
        <!-- Desktop/User Dropdown -->
        <div x-data="{ open: false }" class="relative hidden md:block">
            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none text-orange-500 border border-orange-600 shadow-md px-3 py-2 rounded-lg">
                <img src="{{ Auth::user()->profile && Auth::user()->profile->photo
                                ? 'data:image/jpeg;base64,' . base64_encode(Auth::user()->profile->photo->photos)
                                : asset('images/turo_moko_logo.png') }}"
                     alt="Profile Photo"
                     class="w-8 h-8 rounded-full object-cover">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50" style="display: none;">
                <a href="{{ route('learner.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-600">Profile</a>
                <a href="{{ route('learner.hub') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-orange-600">Dashboard</a>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-red-600" type="submit">Logout</button>
                </form>
            </div>
        </div>
        @else
            <a href="{{ route('auth.login') }}" class="hidden md:inline text-sm font-medium text-gray-800 hover:text-orange-500">Log In</a>
            <a href="{{ route('auth.register') }}" class="hidden md:inline bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold py-2 px-4 rounded-full">Sign Up</a>
        @endauth
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen" x-transition class="absolute top-full left-0 w-full bg-white border-t border-gray-200 shadow-md md:hidden z-40">
        <div class="flex flex-col gap-2 p-4">
            <a href="#home" @click.prevent="scrollToSection('home'); active='home'; mobileOpen=false" 
               :class="active==='home' ? 'text-orange-500 font-semibold' : 'text-gray-700'">Dashboard</a>
            <a href="#courses" @click.prevent="scrollToSection('courses'); active='courses'; mobileOpen=false" 
               :class="active==='courses' ? 'text-orange-500 font-semibold' : 'text-gray-700'">Courses</a>
            <a href="#community" @click.prevent="scrollToSection('community'); active='community'; mobileOpen=false" 
               :class="active==='community' ? 'text-orange-500 font-semibold' : 'text-gray-700'">Community</a>
            <a href="#about" @click.prevent="scrollToSection('about'); active='about'; mobileOpen=false" 
               :class="active==='about' ? 'text-orange-500 font-semibold' : 'text-gray-700'">About</a>

            @auth
                <a href="{{ route('learner.profile') }}" class="text-gray-700 hover:text-orange-500">Profile</a>
                <a href="{{ route('learner.hub') }}" class="text-gray-700 hover:text-orange-500">Dashboard</a>
                <form method="POST" action="{{ route('auth.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-gray-700 hover:text-red-600">Logout</button>
                </form>
            @else
                <div class="flex flex-col gap-2">
                    <a href="{{ route('auth.login') }}" class="px-4 py-2 rounded-lg font-medium text-gray-800 border border-gray-300 hover:text-orange-500 hover:border-orange-400 transition-all">Log In</a>
                    <a href="{{ route('auth.register') }}" class="px-4 py-2 rounded-lg font-semibold bg-orange-500 text-white hover:bg-orange-600 shadow-md transition-all">Sign Up</a>
                </div>
            @endauth
        </div>
    </div>

</div>
