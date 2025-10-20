
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

    <!-- Right: Login/Signup or User Dropdown -->
    <div class="flex items-center gap-4 relative">
        @auth
            <!-- Logged-in User -->
            <div x-data="{ open: false }" class="relative text-orange-500 border border-orange-600 shadow-md px-4 py-3 rounded-lg">
                <!-- User Info Button -->
                <button @click="open = !open" class="flex items-center gap-3 focus:outline-none">
                    <!-- User Photo -->
                    <!-- Profile Photo -->
                    <img src="{{ Auth::user()->profile && Auth::user()->profile->photo
                                ? 'data:image/jpeg;base64,' . base64_encode(Auth::user()->profile->photo->photos)
                                : asset('images/turo_moko_logo.png') }}"
                    alt="Profile Photo"
                    class="w-8 h-8 rounded-full object-cover">


                    <!-- Name & Role -->
                    <div class="hidden md:flex flex-col items-start">
                        <span class="text-sm font-semibold text-gray-800 hover:text-orange-500">{{ Auth::user()->profile->first_name }} {{ Auth::user()->profile->last_name }}</span>
                        <span class="text-xs text-gray-500">{{ Auth::user()->role->role_name ?? 'User' }}</span>
                    </div>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open"
                    @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-2 z-50">

                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Profile
                    </a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        Dashboard
                    </a>

                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf

                        <a href="{{ route('auth.logout') }}"
                        class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                            Logout
                        </a>
                    </form>
                </div>
            </div>
        @else
            <!-- Not logged in -->
            <a href="{{ route('auth.login') }}" 
            class="text-sm font-medium text-gray-800 hover:text-orange-500">
                Log In
            </a>
            <a href="{{ route('auth.register') }}" 
            class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold py-2 px-4 rounded-full">
                Sign Up
            </a>
        @endauth
    </div>
</div>

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, log me out!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Use the modern v3 dispatch method
                Livewire.dispatch('logoutConfirmed');
            }
        });
    }
</script>