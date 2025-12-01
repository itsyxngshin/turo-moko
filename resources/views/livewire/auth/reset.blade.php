<div>
    <h2>Set your new password</h2>

    <form wire:submit="resetPassword">
        <input wire:model="token" type="hidden">

        <div>
            <label for="email">Email</label>
            <input wire:model="email" id="email" type="email" required autofocus>
            @error('email') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="password">Password</label>
            <input wire:model="password" id="password" type="password" required>
            @error('password') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="password_confirmation">Confirm Password</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" required>
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit">
                Reset Password
            </button>
        </div>
    </form>
</div>