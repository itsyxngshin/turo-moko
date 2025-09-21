<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Turo-Moko Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen w-screen overflow-hidden font-sans">

  <!-- Left side: Image + Logo -->
  <div class="w-1/2 relative hidden md:block">
    <img
      src="https://images.unsplash.com/photo-1581090700227-1e8eec7d1df7?auto=format&fit=crop&w=1050&q=80"
      alt="Students using computer"
      class="w-full h-full object-cover"
    />
    <div class="absolute bottom-6 left-6 text-white flex items-center gap-3 text-3xl font-bold">
      <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
        <path d="M12 2C6.477 2 2 6.477 2 12h2a8 8 0 1113.535 5.535L16 20a10 10 0 10-4-18z" />
      </svg>
      TURO-MOKO
    </div>
  </div>

  <!-- Right side: Form -->
  <div class="w-full md:w-1/2 flex items-center justify-center bg-white">
    <div class="w-full max-w-sm px-6">
      <h2 class="text-2xl font-bold mb-1 text-center">Welcome!</h2>
      <p class="text-sm text-gray-600 text-center mb-6">Register to continue.</p>

      <!-- Email -->
      <div class="mb-3">
        <label for="email" class="block text-sm font-medium">Email</label>
        <input type="email" id="email" placeholder="Enter your email here" class="w-full mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Phone -->
      <div class="mb-3">
        <label for="phone" class="block text-sm font-medium">Phone Number</label>
        <div class="relative mt-1">
          <span class="absolute inset-y-0 left-0 flex items-center pl-3">
            🇵🇭
          </span>
          <input
            type="text"
            id="phone"
            placeholder="+63XXX-XXXX-XXX"
            class="w-full pl-10 pr-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400"
          />
        </div>
      </div>

      <!-- Password -->
      <div class="mb-3">
        <label for="password" class="block text-sm font-medium">Password</label>
        <input type="password" id="password" placeholder="Enter password" class="w-full mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Confirm Password -->
      <div class="mb-4">
        <label for="confirmPassword" class="block text-sm font-medium">Confirm Password</label>
        <input type="password" id="confirmPassword" placeholder="Confirm password" class="w-full mt-1 px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400" />
      </div>

      <!-- Sign In button -->
      <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-md mb-3 font-medium">
        Sign In
      </button>

      <!-- Terms and Privacy -->
      <p class="text-xs text-gray-500 text-center mb-4">
        By continuing, you agree to our
        <a href="#" class="underline">Terms Of Service</a> and
        <a href="#" class="underline">Privacy Policy</a>.
      </p>

      <!-- Login link -->
      <p class="text-sm text-center text-gray-600">
        Already have an account?
        <a href="#" class="text-black font-semibold hover:underline">Log in here.</a>
      </p>
    </div>
  </div>

</body>
</html>
