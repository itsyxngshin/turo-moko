<div>
    <h2>Forgot your password?</h2>
    <p>No problem. Just let us know your email address and we will email you a password reset link.</p>

    @if ($status)
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ $status }}
        </div>
    @endif

    <form wire:submit="sendResetLink">
        <div>
            <label for="email">Email</label>
            <input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus>
            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit">
                Email Password Reset Link
            </button>
        </div>
    </form>
</div>