<div>
    <h2 class="text-2xl font-bold text-center text-gray-800">Forgot Password?</h2>
    <p class="mt-2 text-center text-sm text-gray-600">
        No problem. Just let us know your email address and we will email you a
        password reset link.
    </p>

    @if ($status)
        <div class="mt-4 rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-600">
                        {{ $status }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form wire:submit="sendResetLink" class="mt-6 space-y-6">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email
            </label>
            <input wire:model="email" id="email" name="email" type="email"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                   required autofocus>
            
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end">
            <button type="submit"
                    class="w-full justify-center rounded-md bg-red-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-500">
                <span wire:loading.remove wire:target="sendResetLink">
                    Email Password Reset Link
                </span>
                <span wire:loading wire:target="sendResetLink">
                    Sending...
                </span>
            </button>
        </div>
    </form>
</div>