<div class="w-full">
    <h2 class="text-xl md:text-2xl font-bold text-center text-gray-800">
        {{ $step === 1 ? 'Forgot Password?' : 'Reset Password' }}
    </h2>
    <p class="mt-2 text-center text-sm text-gray-600 px-2">
        {{ $step === 1 
            ? "Enter your email and we'll send you an 8-digit code." 
            : "Enter the code sent to $email and your new password." 
        }}
    </p>

    {{-- Status Banner --}}
    @if ($status)
        <div class="mt-4 rounded-lg bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ $status }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- STEP 1: SEND EMAIL FORM --}}
    @if($step === 1)
        <form wire:submit="sendResetCode" class="mt-6 space-y-5">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 ml-1">Email</label>
                <input wire:model="email" id="email" type="email"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm py-3 text-base sm:text-sm focus:border-orange-400 focus:ring-orange-400 placeholder-gray-400"
                       placeholder="juandelacruz@gmail.com" required autofocus>
                @error('email') 
                    <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            <button type="submit" class="w-full justify-center rounded-lg bg-orange-500 px-4 py-3 text-base font-bold text-white shadow-md hover:bg-orange-400 transition-all duration-200">
                <span wire:loading.remove wire:target="sendResetCode">Send Code</span>
                <span wire:loading wire:target="sendResetCode">Sending...</span>
            </button>
        </form>

    {{-- STEP 2: VERIFY CODE & NEW PASSWORD --}}
    @else
        <form wire:submit="verifyAndReset" class="mt-6 space-y-5">
            
            {{-- Code Input --}}
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 ml-1">Recovery Code</label>
                <input wire:model="code" id="code" type="text"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm py-3 text-center tracking-[0.2em] font-mono text-lg uppercase focus:border-orange-400 focus:ring-orange-400"
                       placeholder="XXXXXXXX" maxlength="8" required>
                @error('code') 
                    <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            {{-- New Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 ml-1">New Password</label>
                <input wire:model="password" id="password" type="password"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm py-3 focus:border-orange-400 focus:ring-orange-400"
                       required>
                @error('password') 
                    <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> 
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 ml-1">Confirm Password</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password"
                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm py-3 focus:border-orange-400 focus:ring-orange-400"
                       required>
            </div>

            <button type="submit" class="w-full justify-center rounded-lg bg-orange-500 px-4 py-3 text-base font-bold text-white shadow-md hover:bg-orange-400 transition-all duration-200">
                <span wire:loading.remove wire:target="verifyAndReset">Reset Password</span>
                <span wire:loading wire:target="verifyAndReset">Verifying...</span>
            </button>
            
            {{-- Option to go back --}}
            <button type="button" wire:click="$set('step', 1)" class="w-full text-sm text-gray-500 hover:text-orange-500">
                Wrong email? Try again.
            </button>
        </form>
    @endif

    <div class="text-center pt-2">
        <a href="{{ route('auth.login') }}" wire:navigate class="inline-block p-2 text-sm font-medium text-gray-600 hover:text-orange-500 hover:underline">
            &larr; Return to Login
        </a>
    </div>

    {{-- Keep your existing Script for Swal --}}
    @script
    <script>
        $wire.on('reset-link-sent', (event) => {
            Swal.fire({
                title: 'Success!',
                text: event[0].message,
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#f97316', // Orange-500
                customClass: { popup: 'rounded-xl', confirmButton: 'rounded-lg px-6 py-2' }
            });
        });
    </script>
    @endscript
</div>