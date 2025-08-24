<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Turo-Moko Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen w-screen flex overflow-hidden">

  <!-- Left: Image Section -->
  <div class="w-1/2 h-full relative">
    <img src="https://i.ibb.co/WGhW6Cd/login-side.jpg" alt="Login Side" class="object-cover h-full w-full" />
    <div class="absolute bottom-6 left-6 text-white text-3xl font-bold flex items-center gap-2">
      <svg class="w-8 h-8" fill="white" viewBox="0 0 24 24">
        <path d="M12 2C6.477 2 2 6.477 2 12h2a8 8 0 1113.535 5.535L16 20a10 10 0 10-4-18z" />
      </svg>
      <span class="tracking-wide">TURO-MOKO</span>
    </div>
  </div>

  <!-- Right: Login Form Section -->
  <div class="w-1/2 flex items-center justify-center bg-white">
    <div class="w-full max-w-sm px-6">
      <h2 class="text-2xl font-bold mb-1 text-center">Welcome back!</h2>
      <p class="text-gray-600 mb-6 text-center">Login to continue</p>

      <!-- Email Input -->
      <div class="mb-4">
        <label class="block text-sm mb-1" for="email">Email</label>
        <input type="email" id="email" placeholder="Enter your email here" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Password Input -->
      <div class="mb-4">
        <label class="block text-sm mb-1" for="password">Password</label>
        <input type="password" id="password" placeholder="Enter your password here" class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Remember me -->
      <div class="flex items-center justify-between mb-4">
        <label class="flex items-center gap-2">
          <input type="checkbox" class="accent-orange-500" />
          <span class="text-sm">Remember me</span>
        </label>
        <a href="#" class="text-sm text-gray-500 hover:underline">Forgot Password?</a>
      </div>

      <!-- Login Button -->
      <button class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 mb-4">Login</button>

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
        <a href="#" class="font-semibold text-black hover:underline">Sign up here.</a>
      </p>
    </div>
  </div>

</body>
</html>
