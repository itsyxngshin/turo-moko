<div class="w-full">
    <h2 class="text-xl md:text-2xl font-bold text-center text-gray-800">
        Forgot Password?
    </h2>
    <p class="mt-2 text-center text-sm text-gray-600 px-2">
        No problem. Enter your email and we'll send you a reset link.
    </p>

    @if ($status)
        <div class="mt-4 rounded-lg bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ $status }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="sendResetLink" class="mt-6 space-y-5">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 ml-1">
                Email
            </label>
            <input wire:model="email" id="email" name="email" type="email"
                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm py-3 text-base sm:text-sm focus:border-orange-400 focus:ring-orange-400 placeholder-gray-400"
                   placeholder="juandelacruz@gmail.com"
                   required autofocus>
            
            @error('email')
                <p class="mt-2 text-sm text-orange-600 ml-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <button type="submit"
                    class="w-full justify-center rounded-lg bg-orange-500 px-4 py-3 text-base font-bold text-white shadow-md transition-all duration-200 hover:bg-orange-400 active:scale-[0.98] active:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
                <span wire:loading.remove wire:target="sendResetLink">
                    Email Password Reset Link
                </span>
                <span wire:loading wire:target="sendResetLink">
                    Sending...
                </span>
            </button>
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('auth.login') }}" wire:navigate class="inline-block p-2 text-sm font-medium text-gray-600 transition duration-150 ease-in-out hover:text-orange-500 hover:underline">
                &larr; Return to Login
            </a>
        </div>
    </form>

    @script
    <script>
        $wire.on('reset-link-sent', (event) => {
            Swal.fire({
                title: 'Email Sent!',
                text: event[0].message,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#4f46e5',
                customClass: {
                    popup: 'rounded-xl', // Matches the mobile aesthetic
                    confirmButton: 'rounded-lg px-6 py-2'
                }
            });
        });

        $wire.on('show-error', (event) => {
            Swal.fire({
                title: 'Error',
                text: event[0].message,
                icon: 'error',
                confirmButtonColor: '#ef4444',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg px-6 py-2'
                }
            });
        });
    </script>
    @endscript
</div>