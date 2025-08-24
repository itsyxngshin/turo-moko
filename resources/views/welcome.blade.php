<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TuroMoko</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }
  </style>
</head>
<body class="bg-white text-gray-800">

  <!-- Navbar -->
  <nav class="w-full bg-white py-3 px-11 pt-4 rounded-b-2xl flex items-center justify-between">
  <!-- Left: Logo -->
  <div class="flex items-center gap-5">
    <img src="your-logo-path.svg" alt="TuroMoko Logo" class="h-6 w-6">
    <span class="text-xl font-semibold text-orange-500">TuroMoko</span>
  </div>

<!-- Center: Navigation Links -->
<div class="flex-1 flex justify-center">
  <div class="hidden md:flex gap-8 text-sm font-medium items-center">
    <a href="#" class="text-orange-500 border border-orange-400 px-3 py-1 rounded-full">Home</a>
    <a href="#" class="text-gray-700 hover:text-orange-500">Courses</a>
    <a href="#" class="text-gray-700 hover:text-orange-500">Community</a>
    <a href="#" class="text-gray-700 hover:text-orange-500">About</a>
  </div>
</div>

  <!-- Right: Login Dropdown and Sign Up -->
  <div class="flex items-center gap-4 relative">
    <!-- Log In Dropdown -->
    <div class="relative group">
      <button class="text-sm font-medium text-gray-800 hover:text-orange-500 flex items-center gap-1">
        Log In
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
      </button>
      <div class="absolute right-0 mt-2 hidden group-hover:block bg-white border rounded-md shadow-lg z-10 w-32 text-sm text-gray-700">
        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Learner</a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Teacher</a>
        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Admin</a>
      </div>
    </div>

    <!-- Sign Up Button -->
    <a href="#" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold py-2 px-4 rounded-full">Sign Up</a>
  </div>
</nav>


  <!-- Hero Section -->
  <section class="relative mt-4 mx-4 md:mx-12">
    <!-- Hero Image -->
    <img src="your-hero-image.jpg" alt="Hero" class="w-full h-[650px] object-cover rounded-[2.5rem]" />

    <!-- Overlay Text -->
    <div class="absolute top-0 left-0 w-full h-full rounded-[2.5rem] bg-black bg-opacity-30 flex flex-col justify-center px-6 md:px-20">
      <div class="max-w-2xl text-white">
        <p class="text-md md:text-lg font-medium mb-6 leading-snug">
          A Web-Based E-Learning Platform for Advocacy and<br class="hidden md:block">
          Community-based Education of Civil Society<br class="hidden md:block">
          Organizations in Albay
        </p>
        <a href="#" class="inline-flex items-center gap-2 bg-white text-black font-semibold text-sm px-5 py-2 rounded-full hover:bg-gray-200">
          Get Started <span class="text-orange-500">➔</span>
        </a>
      </div>
    </div>

    <!-- Logo and Title on Bottom Left -->
    <div class="absolute bottom-6 left-6 flex items-center gap-2 text-white">
      <img src="your-logo-icon-white.png" alt="Logo" class="w-6 h-6" />
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
        <img src="community-icon.svg" alt="Community Driven" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Community-Driven</h3>
        <p class="text-sm text-gray-600">Leadership and learning experiences created in collaboration with the community.</p>
      </div>

      <!-- Feature 2 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="accessible-icon.svg" alt="Accessible" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Accessible</h3>
        <p class="text-sm text-gray-600">Open to community members, CSOs, advocates, and volunteers nationwide.</p>
      </div>

      <!-- Feature 3 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="free-icon.svg" alt="Free for All" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Free for All</h3>
        <p class="text-sm text-gray-600">Join or use Turo Moko at no cost. Learning should be free.</p>
      </div>

      <!-- Feature 4 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="user-icon.svg" alt="User-Friendly" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">User-Friendly</h3>
        <p class="text-sm text-gray-600">Designed with intuitive, mobile-first design that works great on any device.</p>
      </div>

      <!-- Feature 5 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="adaptive-icon.svg" alt="Adaptive" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Adaptive</h3>
        <p class="text-sm text-gray-600">Works online and offline so you can learn anytime, any place.</p>
      </div>

      <!-- Feature 6 -->
      <div class="flex flex-col items-center text-center max-w-xs">
        <img src="scalable-icon.svg" alt="Scalable" class="w-12 h-12 mb-4">
        <h3 class="font-semibold text-lg mb-1">Scalable</h3>
        <p class="text-sm text-gray-600">Designed to grow with your organization, from local to national learning hubs.</p>
      </div>
    </div>
  </div>
</section>


<!-- Section: Learning Path with Image Overlay -->
<section class="relative">
  <img src="your-group-class-image1.jpg" class="w-full h-[420px] object-cover" alt="Learning Group">
  <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col justify-center px-6 md:px-20 text-white">
    <h2 class="text-2xl md:text-3xl font-semibold mb-2">Find the Right Learning Path</h2>
    <p class="max-w-2xl text-sm md:text-base leading-relaxed">
      Turo Moko’s Learning Hub helps you explore opportunities based on your interests and needs. Search by topic, advocacy field, organization, or learning style.
    </p>
  </div>
</section>

<!-- Section: Learning Path Side-by-Side -->
<section class="bg-white py-20 px-6 md:px-20 grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
  <img src="your-laptop-image.jpg" alt="Laptop Learning" class="rounded-xl shadow-lg">
  <div>
    <h2 class="text-2xl md:text-3xl font-semibold mb-3">Find the Right Learning Path</h2>
    <p class="text-gray-600 text-base md:text-lg max-w-md">
      Turo Moko’s Learning Hub helps you explore opportunities based on your interests and needs. Search by topic, advocacy field, organization, or learning style.
    </p>
  </div>
</section>

<!-- Section: Call to Action -->
<section class="bg-gray-100 text-center py-14 px-4">
  <h3 class="text-2xl font-semibold mb-4">Got any questions?</h3>
  <a href="mailto:support@turomoko.org" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-full text-base font-medium">
    Email us here
  </a>
</section>


</body>
</html>
