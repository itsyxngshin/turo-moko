
<!-- Navbar -->
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

    <!-- Right: Login Dropdown and Sign Up -->
    <div class="flex items-center gap-4 relative">
        <!-- Log In Button -->
    <a href="{{ route('auth.login') }}" 
    class="text-sm font-medium text-gray-800 hover:text-orange-500">
    Log In
    </a>

        <!-- Sign Up Button -->
    <a href="{{ route('auth.register') }}" 
    class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold py-2 px-4 rounded-full">
    Sign Up
    </a>
    </div>
</div>