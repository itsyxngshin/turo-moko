<!DOCTYPE html>
<html lang="en" x-data="{ role: 'implementor' }">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Turo-Moko Register</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Alpine -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <link rel="icon" href="{{ asset('images/turo_moko_logo.png') }}" type="image/png">

  <!-- ✅ Mobile fixes -->
  <style>
    html, body {
      height: auto;
      overscroll-behavior-y: contain;
      -webkit-overflow-scrolling: touch;
    }
  </style>
</head>

<body class="bg-white overflow-x-hidden font-sans">

  <!-- ✅ DO NOT FLEX BODY -->
  <div class="flex min-h-[100dvh] flex-col md:flex-row">

    <!-- ✅ LEFT IMAGE (DESKTOP ONLY) -->
    <div class="hidden md:block md:w-1/2 relative"
         x-data="{ images: ['/images/cover.jpg', '/images/cover7.jpg', '/images/cover3.jpg'], index: 0 }"
         x-init="setInterval(() => index = (index + 1) % images.length, 5000)">

      <template x-for="(image, i) in images" :key="i">
        <img
          :src="image"
          class="absolute inset-0 w-full h-full object-cover brightness-50 transition-opacity duration-700"
          x-show="index === i"
        />
      </template>

      <div class="absolute bottom-6 left-6 flex items-center gap-3">
        <img src="/images/turo_moko_logo_white.png" class="w-10 h-10" />
        <span class="text-white text-3xl font-bold">TURO-MOKO</span>
      </div>
    </div>

    <!-- ✅ FORM SIDE -->
    <div class="w-full md:w-1/2 flex items-center justify-center
                px-4 py-10
                pt-[calc(1rem+env(safe-area-inset-top))]
                pb-[calc(1rem+env(safe-area-inset-bottom))]">

      <div class="w-full max-w-sm">

        <h2 class="text-2xl font-bold text-center mb-1">Welcome!</h2>
        <p class="text-sm text-gray-600 text-center mb-6">Register to continue.</p>

        <!-- Role Toggle -->
        <div class="mb-6">
          <div class="flex bg-gray-100 rounded-lg overflow-hidden text-sm font-medium">
            <button
              type="button"
              @click="role='implementor'"
              :class="role==='implementor' ? 'bg-orange-500 text-white' : 'text-gray-600'"
              class="flex-1 py-2">
              Implementor
            </button>
            <button
              type="button"
              @click="role='learner'"
              :class="role==='learner' ? 'bg-orange-500 text-white' : 'text-gray-600'"
              class="flex-1 py-2">
              Learner
            </button>
          </div>
          <input type="hidden" name="role" :value="role">
        </div>

        <!-- Email -->
        <div class="mb-3">
          <label class="block text-sm font-medium">Email</label>
          <input type="email"
                 class="w-full mt-1 px-4 py-2 border rounded-md text-base
                        focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>

        <!-- Phone -->
        <div class="mb-3">
          <label class="block text-sm font-medium">Phone Number</label>
          <div class="relative mt-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">🇵🇭</span>
            <input
              type="tel"
              placeholder="+63XXXXXXXXXX"
              class="w-full pl-10 pr-4 py-2 border rounded-md text-base
                     focus:outline-none focus:ring-2 focus:ring-orange-400">
          </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
          <label class="block text-sm font-medium">Password</label>
          <input type="password"
                 class="w-full mt-1 px-4 py-2 border rounded-md text-base
                        focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>

        <!-- Confirm -->
        <div class="mb-4">
          <label class="block text-sm font-medium">Confirm Password</label>
          <input type="password"
                 class="w-full mt-1 px-4 py-2 border rounded-md text-base
                        focus:outline-none focus:ring-2 focus:ring-orange-400">
        </div>

        <!-- Submit -->
        <button class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 rounded-md mb-4">
          Sign In
        </button>

        <!-- Terms -->
        <p class="text-xs text-gray-500 text-center mb-4">
          By continuing, you agree to our
          <a class="underline">Terms</a> and
          <a class="underline">Privacy Policy</a>.
        </p>

        <!-- Login -->
        <p class="text-sm text-center">
          Already have an account?
          <a href="{{ route('login') }}" class="font-medium hover:text-orange-500">
            Log in
          </a>
        </p>

      </div>
    </div>

  </div>

</body>
</html>
