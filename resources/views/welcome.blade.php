<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TuroMoko</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="//unpkg.com/alpinejs" defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
    html {
    scroll-behavior: smooth;
    }
  </style>
</head>
<body class="bg-white text-gray-800">

<!-- Navbar -->
<nav 
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
</nav>


<div class="pt-20"></div>

<!-- Hero Section -->
<section 
  id="home"
  x-data="{ images: ['/images/cover.jpg', '/images/cover1.jpg', '/images/cover3.jpg'], index: 0 }"
  x-init="setInterval(() => { index = (index + 1) % images.length }, 5000)"
  class="relative mt-4 mx-4 md:mx-12 h-[650px] rounded-[2.5rem] overflow-hidden"
>
  <!-- Loop through images -->
  <template x-for="(image, i) in images" :key="i">
    <img 
      :src="image" 
      class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 ease-in-out brightness-75"
      x-show="index === i"
      x-transition:enter="opacity-0"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="opacity-100"
      x-transition:leave-end="opacity-0"
    />
  </template>

  <!-- Overlay Text -->
  <div class="absolute inset-0 bg-black bg-opacity-30 flex flex-col justify-center px-6 md:px-20">
    <div class="max-w-2xl text-white">
      <p class="text-md md:text-lg font-medium mb-6 leading-snug animate-fadeIn">
        A Web-Based E-Learning Platform for Advocacy and<br class="hidden md:block">
        Community-based Education of Civil Society<br class="hidden md:block">
        Organizations in Albay
      </p>
      <a href="#" class="inline-flex items-center gap-2 bg-white text-black font-semibold text-sm px-5 py-2 rounded-full hover:bg-gray-200 transition">
        Get Started <span class="text-orange-500">➔</span>
      </a>
    </div>
  </div>

  <!-- Top Center Dots -->
  <div class="absolute top-6 inset-x-0 flex justify-center space-x-2">
    <template x-for="(image, i) in images" :key="i">
      <button 
        class="w-3 h-3 rounded-full border border-white transition-all duration-300"
        :class="index === i ? 'bg-white scale-110' : 'bg-transparent'"
        @click="index = i"
      ></button>
    </template>
  </div>

  <!-- Logo and Title on Bottom Left -->
  <div class="absolute bottom-6 left-6 flex items-center gap-2 text-white animate-fadeIn">
    <img src="/images/turo_moko_logo_white.png" alt="Logo" class="w-11 h-11" />
    <h1 class="text-3xl font-bold tracking-wide">TURO-MOKO</h1>
  </div>
</section>



<!-- Features Section -->
<section class="py-20 px-4 bg-white text-gray-800">
  <div class="max-w-6xl mx-auto text-center">
    <h2 class="text-2xl font-bold mb-4">Empowering Communities<br>Through Accessible Learning</h2>
    <p class="mb-12 max-w-2xl mx-auto text-sm text-gray-600">
      Discover how Turo Moko transforms education through flexibility, inclusivity, and a platform designed for real community impact.
    </p>

    <!-- Icon Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12 justify-items-center text-center">

      <!-- Feature 1 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="https://img.icons8.com/ios-filled/50/ff7f50/conference.png" alt="Community Driven" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Community-Driven</h3>
        <p class="text-sm text-gray-600">Leadership and learning experiences created in collaboration with the community.</p>
      </div>

      <!-- Feature 2 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="https://img.icons8.com/ios-filled/50/ff7f50/door-opened.png" alt="Accessible" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Accessible</h3>
        <p class="text-sm text-gray-600">Open to community members, CSOs, advocates, and volunteers nationwide.</p>
      </div>

      <!-- Feature 3 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="https://img.icons8.com/ios-filled/50/ff7f50/gift.png" alt="Free for All" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Free for All</h3>
        <p class="text-sm text-gray-600">Join or use Turo Moko at no cost. Learning should be free.</p>
      </div>

      <!-- Feature 4 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="https://img.icons8.com/ios-filled/50/ff7f50/smartphone.png" alt="User-Friendly" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">User-Friendly</h3>
        <p class="text-sm text-gray-600">Designed with intuitive, mobile-first design that works great on any device.</p>
      </div>

      <!-- Feature 5 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="https://img.icons8.com/ios-filled/50/ff7f50/synchronize.png" alt="Adaptive" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Adaptive</h3>
        <p class="text-sm text-gray-600">Works online and offline so you can learn anytime, any place.</p>
      </div>

      <!-- Feature 6 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="https://img.icons8.com/ios-filled/50/ff7f50/expand.png" alt="Scalable" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Scalable</h3>
        <p class="text-sm text-gray-600">Designed to grow with your organization, from local to national learning hubs.</p>
      </div>

    </div>
  </div>
</section>



<!-- Section: Learning Path with Image Overlay -->
<section class="relative">
      <img src="/images/cover4.jpg"
       class="w-full h-[420px] object-cover" 
       alt="Learning Group">
  <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center px-6 md:px-20 text-white">
    <h2 class="text-2xl md:text-3xl font-semibold mb-2">Find the Right Learning Path</h2>
    <p class="max-w-2xl text-sm md:text-base leading-relaxed">
      Turo Moko’s Learning Hub helps you explore opportunities based on your interests and needs. 
      Search by topic, advocacy field, organization, or learning style.
    </p>
  </div>
</section>

<!-- Courses Section -->
<section id="courses" class="bg-gray-50 py-16 px-6 md:px-20">
  <div class="max-w-7xl mx-auto text-center mb-12">
    <h2 class="text-2xl md:text-3xl font-bold mb-3">Courses Offered</h2>
    <p class="text-gray-600 text-sm md:text-base max-w-2xl mx-auto">
      Explore advocacy-driven and community-focused courses inspired by real programs that empower children, families, and communities.
    </p>
  </div>

  <!-- Course Cards -->
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 max-w-7xl mx-auto">
    
    <!-- Course 1 -->
    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
      <img src="/images/course1.jpg"
           alt="Child Protection & Rights" 
           class="w-full h-40 object-cover">
      <div class="p-5 text-left">
        <h3 class="font-semibold text-lg mb-2 text-orange-500">Child Protection & Rights</h3>
        <p class="text-sm text-gray-600">Learn strategies to safeguard children and uphold their rights within communities.</p>
      </div>
    </div>

<!-- Course 2 -->
<div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
<img src="/images/course22.jpg"
       alt="Disaster Preparedness" 
       class="w-full h-40 object-cover">
  <div class="p-5 text-left">
    <h3 class="font-semibold text-lg mb-2 text-orange-500">Disaster Preparedness</h3>
    <p class="text-sm text-gray-600">Equip yourself with knowledge on disaster risk reduction and emergency response.</p>
  </div>
</div>

    <!-- Course 3 -->
    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
      <img src="/images/course3.jpg"
           alt="Health & Nutrition" 
           class="w-full h-40 object-cover">
      <div class="p-5 text-left">
        <h3 class="font-semibold text-lg mb-2 text-orange-500">Health & Nutrition</h3>
        <p class="text-sm text-gray-600">Understand essential practices to promote family health and child nutrition.</p>
      </div>
    </div>

    <!-- Course 4 -->
    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
     <img src="/images/course4.jpg"
           alt="Livelihood & Skills Training" 
           class="w-full h-40 object-cover">
      <div class="p-5 text-left">
        <h3 class="font-semibold text-lg mb-2 text-orange-500">Livelihood & Skills Training</h3>
        <p class="text-sm text-gray-600">Develop sustainable income opportunities and practical skills for families.</p>
      </div>
    </div>

    <!-- Course 5 -->
    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
          <img src="/images/course5.jpg"
           alt="Community Development" 
           class="w-full h-40 object-cover">
      <div class="p-5 text-left">
        <h3 class="font-semibold text-lg mb-2 text-orange-500">Community Development</h3>
        <p class="text-sm text-gray-600">Learn how to strengthen local organizations and empower community members.</p>
      </div>
    </div>

    <!-- Course 6 -->
    <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition overflow-hidden">
          <img src="/images/course6.jpg"
           alt="Environmental Sustainability" 
           class="w-full h-40 object-cover">
      <div class="p-5 text-left">
        <h3 class="font-semibold text-lg mb-2 text-orange-500">Environmental Sustainability</h3>
        <p class="text-sm text-gray-600">Explore eco-friendly practices to help communities build resilience and sustainability.</p>
      </div>
    </div>

  </div>
</section>


<!-- Community Section -->
<section id="community" class="bg-white py-16 px-6 md:px-20"> 
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
    
    <!-- Image -->
   <img src="/images/cover6.jpg"
         alt="Community Collaboration" 
         class="rounded-2xl shadow-md w-full h-[350px] object-cover">
    
    <!-- Text -->
    <div class="flex flex-col justify-center items-center text-center">
      <h2 class="text-2xl md:text-3xl font-bold mb-4 text-gray-800">Our Community</h2>
      <p class="text-gray-600 text-base md:text-lg mb-4 max-w-xl">
        Turo Moko thrives through the collective efforts of learners, teachers, and advocates. 
        Our community fosters collaboration, shared knowledge, and support to drive meaningful change. 
      </p>
      <ul class="list-disc list-inside text-gray-600 space-y-2 text-left">
        <li>Connect with like-minded advocates</li>
        <li>Share resources and experiences</li>
        <li>Collaborate on local and national initiatives</li>
      </ul>
    </div>
  </div>
</section>


<!-- About Section -->
<section id="about" class="bg-gray-50 py-16 px-6 md:px-20">
  <div class="max-w-5xl mx-auto text-center">
    <h2 class="text-2xl md:text-3xl font-bold mb-4 text-gray-800">About Turo Moko</h2>
    <p class="text-gray-600 text-base md:text-lg max-w-3xl mx-auto mb-8">
      Turo Moko is a capstone project developed as a prototype e-learning platform to support 
      civil society organizations and communities in Albay. The platform is designed to make 
      learning more accessible, collaborative, and inclusive by offering free online courses, 
      community spaces, and resources for local advocacies.  
    </p>

    <!-- Stats or Values -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mt-10">
      <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="text-2xl font-bold text-orange-500 mb-2">0</h3>
        <p class="text-gray-600 text-sm">Prototype Users</p>
      </div>
      <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="text-2xl font-bold text-orange-500 mb-2">0</h3>
        <p class="text-gray-600 text-sm">Test Communities</p>
      </div>
      <div class="bg-white shadow-md rounded-xl p-6">
        <h3 class="text-2xl font-bold text-orange-500 mb-2">0</h3>
        <p class="text-gray-600 text-sm">Pilot Courses</p>
      </div>
    </div>
  </div>
</section>



<footer class="bg-black text-gray-300 pt-12">
  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-10 px-6 md:px-20">

    <!-- Contact -->
    <div>
      <h3 class="text-white font-semibold mb-4">Contact Us</h3>
      <ul class="space-y-3 text-sm">
        <li class="flex items-center gap-2">
          <span class="text-orange-500">📞</span>
          <span>(+63) 912-345-6789</span>
        </li>
        <li class="flex items-center gap-2">
          <span class="text-orange-500">📍</span>
          <span>123 Albay Road, Legazpi City, Philippines</span>
        </li>
        <li class="flex items-center gap-2">
          <span class="text-orange-500">✉️</span>
          <span>support@turomoko.org</span>
        </li>
        <li class="flex items-center gap-2">
          <span class="text-orange-500">💬</span>
          <span>Chat with Us</span>
        </li>
      </ul>
    </div>

   <!-- Follow Us -->
<div>
  <h3 class="text-white font-semibold mb-4">Follow Us</h3>
  <div class="flex gap-4 mb-6">
    <!-- Facebook -->
    <a href="#" class="w-9 h-9 flex items-center justify-center bg-orange-500 rounded-full text-white hover:bg-orange-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
        <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 5 3.66 9.13 8.44 9.88v-6.99h-2.54v-2.89h2.54V9.41c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.23.2 2.23.2v2.45h-1.26c-1.24 0-1.63.77-1.63 1.56v1.87h2.78l-.44 2.89h-2.34v6.99C18.34 21.13 22 17 22 12z"/>
      </svg>
    </a>

    <!-- Twitter (X) -->
    <a href="#" class="w-9 h-9 flex items-center justify-center bg-orange-500 rounded-full text-white hover:bg-orange-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.31l-5.214-6.82-5.97 6.82H1.816l7.73-8.836L1.308 2.25h6.972l4.713 6.231 5.251-6.231z"/>
      </svg>
    </a>

    <!-- LinkedIn -->
    <a href="#" class="w-9 h-9 flex items-center justify-center bg-orange-500 rounded-full text-white hover:bg-orange-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
        <path d="M19 3A2 2 0 0 1 21 5V19A2 2 0 0 1 19 21H5A2 2 0 0 1 3 19V5A2 2 0 0 1 5 3H19M8.34 17V10.67H6V17H8.34M7.17 9.67A1.17 1.17 0 1 0 7.17 7.33 1.17 1.17 0 0 0 7.17 9.67M18 17V13.22C18 11.09 16.66 10.5 15.27 10.5C14.25 10.5 13.65 11 13.41 11.46H13.36V10.67H11V17H13.34V13.47C13.34 12.79 13.73 12.33 14.39 12.33C15.05 12.33 15.34 12.79 15.34 13.47V17H18Z"/>
      </svg>
    </a>

    <!-- Instagram -->
    <a href="#" class="w-9 h-9 flex items-center justify-center bg-orange-500 rounded-full text-white hover:bg-orange-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
        <path d="M7 2C4.243 2 2 4.243 2 7v10c0 2.757 2.243 5 5 5h10c2.757 0 5-2.243 5-5V7c0-2.757-2.243-5-5-5H7zm0 2h10c1.654 0 3 1.346 3 3v10c0 1.654-1.346 3-3 3H7c-1.654 0-3-1.346-3-3V7c0-1.654 1.346-3 3-3zm5 2.5A5.507 5.507 0 0 0 6.5 12 5.507 5.507 0 0 0 12 17.5 5.507 5.507 0 0 0 17.5 12 5.507 5.507 0 0 0 12 6.5zm0 2A3.505 3.505 0 0 1 15.5 12 3.505 3.505 0 0 1 12 15.5 3.505 3.505 0 0 1 8.5 12 3.505 3.505 0 0 1 12 8.5zm4.75-2.75a1.25 1.25 0 1 0 0 2.5 1.25 1.25 0 0 0 0-2.5z"/>
      </svg>
    </a>
  </div>
  <p class="text-sm text-gray-400">Stay connected with our advocacy stories and community impact.</p>
</div>


    <!-- Newsletter -->
    <div>
      <h3 class="text-white font-semibold mb-4">Newsletter Signup</h3>
      <form class="space-y-3">
        <label class="flex items-center text-sm gap-2">
          <input type="checkbox" class="accent-orange-500">
          <span>Send me updates about Turo Moko via email.</span>
        </label>
        <div class="flex">
          <input type="email" placeholder="Email Address" class="w-full px-3 py-2 rounded-l-md text-black text-sm focus:outline-none">
          <button class="bg-orange-500 hover:bg-orange-600 px-4 rounded-r-md text-white">Subscribe</button>
        </div>
      </form>
    </div>

  </div>

  <!-- Divider -->
  <div class="border-t border-gray-800 mt-10"></div>

  <!-- Bottom Bar -->
  <div class="max-w-7xl mx-auto px-6 md:px-20 py-6 flex flex-col md:flex-row justify-between text-sm text-gray-500">
    <p>© 2025 Turo Moko. All Rights Reserved.</p>
    <div class="space-x-4">
      <a href="#" class="hover:underline">Privacy Policy</a>
      <a href="#" class="hover:underline">Terms of Use</a>
    </div>
  </div>
</footer>

<!-- Font Awesome (for icons) -->
<script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>

</body>
</html>
