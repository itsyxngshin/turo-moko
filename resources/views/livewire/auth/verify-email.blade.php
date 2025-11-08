@section('title', 'Verify your TURO-MOKO Account')

<div class="min-h-screen lg:grid lg:grid-cols-2">

    <div class="flex flex-col justify-center bg-white px-6 py-12 sm:px-12 lg:px-20 xl:px-24">
        <div class="mx-auto w-full max-w-md">

            <div class="mt-8">
                <svg class="h-12 w-12 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91A2.25 2.25 0 012.25 6.993V6.75" />
                </svg>
                <h2 class="mt-4 text-3xl font-bold tracking-tight text-gray-900">
                    Check your inbox
                </h2>
                <p class="mt-2 text-gray-600">
                    Thanks for signing up! We've sent a verification link to your email.
                </p>
            </div>

            <div class="mt-6 space-y-6">

                @if (session('message'))
                    <div class="rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('message') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full justify-center rounded-md bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
                        Resend Verification Email
                    </button>
                </form>

                <div class="text-center">
                    <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-500 hover:text-gray-800 hover:underline">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="relative hidden lg:block"
         x-data="{ images: [
            '{{ asset('/images/cover.jpg') }}', 
            '{{ asset('/images/cover7.jpg') }}', 
            '{{ asset('/images/cover3.jpg') }}'
         ], index: 0 }"
         x-init="setInterval(() => { index = (index + 1) % images.length }, 5000)">
      
      <template x-for="(image, i) in images" :key="i">
        <img 
          :src="image" 
          alt="Students using computer"
          class="absolute inset-0 w-full h-full object-cover transition-opacity duration-500 ease-in-out brightness-50"
          x-show="index === i"
          x-transition:enter="opacity-0"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="opacity-100"
          x-transition:leave-end="opacity-0"
        />
      </template>
  
      <div class="absolute bottom-6 left-6 flex items-center gap-3">
        <img src="{{ asset('/images/turo_moko_logo_white.png') }}" alt="Turo-Moko Logo" class="w-10 h-10 object-contain" />
        <span class="text-white text-3xl font-bold">TURO-MOKO</span>
      </div>
    </div>

</div>