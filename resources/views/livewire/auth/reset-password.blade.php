<div>
    <h2 class="text-2xl font-bold text-center text-gray-800">Set a New Password</h2>
    <p class="mt-2 text-center text-sm text-gray-600">
        Please enter your new password below.
    </p>

    <form wire:submit="resetPassword" class="mt-6 space-y-6">
        <input wire:model="token" type="hidden">

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email
            </label>
            <input wire:model="email" id="email" name="email" type="email"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500"
                   readonly>
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">
                Password
            </label>
            <input wire:model="password" id="password" name="password" type="password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   required>
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-4">
            <label for="passwordConfirmation" class="block text-sm font-medium text-gray-700">
                Confirm Password
            </label>
            <input wire:model="passwordConfirmation" id="passwordConfirmation" name="passwordConfirmation" type="password"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   required>
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit"
                    class="w-full justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                <span wire:loading.remove wire:target="resetPassword">
                    Reset Password
                </span>
                <span wire:loading wire:target="resetPassword">
                    Resetting...
                </span>
            </button>
        </div>
    </form>
</div>