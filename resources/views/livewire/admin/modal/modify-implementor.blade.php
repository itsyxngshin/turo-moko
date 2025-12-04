<div 
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-transition
    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
    x-init="
        window.addEventListener('modify-implementor', event => {
            // event.detail.id contains the user id
            @this.loadImplementor(event.detail.id);
            open = true;
        });
    "
>

    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-8 relative" @click.away="open = false">

        <h2 class="text-2xl font-bold text-[#C28A56] mb-4">Edit Implementor</h2>
        <button @click="open = false" 
            class="absolute top-3 right-3 text-gray-600 hover:text-black">✕</button>

        <form wire:submit.prevent="update" class="space-y-6">
            <div class="flex gap-8">
                <!-- Photo Upload -->
                <div class="flex flex-col items-center justify-center text-center relative">
                    <label for="photoEdit" class="cursor-pointer group">
                        <div class="w-40 h-40 rounded-full bg-gray-100 border flex items-center justify-center overflow-hidden relative">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" 
                                     class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105">
                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center 
                                            text-white text-sm opacity-0 group-hover:opacity-100 transition">
                                    Change Photo
                                </div>
                            @elseif ($existingPhoto)
                                <img src="{{ asset('storage/' . $existingPhoto) }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <span class="text-gray-400 text-sm">Click to upload</span>
                            @endif
                            <div wire:loading wire:target="photo" 
                                 class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center text-gray-700 text-sm">
                                <svg class="animate-spin h-5 w-5 mb-2 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Uploading...
                            </div>
                        </div>
                    </label>
                    <input id="photoEdit" type="file" wire:model="photo" class="hidden">
                    @if ($photo)
                        <button type="button" wire:click="$set('photo', null)"
                            class="mt-3 px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition">
                            Remove Photo
                        </button>
                    @endif
                    <p class="text-xs text-gray-400 mt-2">Max 1MB, JPEG/PNG</p>
                    @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Fields -->
                <div class="flex-1 grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm">First Name</label>
                        <input type="text" wire:model="first_name" class="w-full border rounded px-3 py-2">
                        @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-sm">Middle Name</label>
                        <input type="text" wire:model="middle_name" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm">Last Name</label>
                        <input type="text" wire:model="last_name" class="w-full border rounded px-3 py-2">
                        @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="text-sm">Phone</label>
                        <input type="text" wire:model="phonenum" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm">Email</label>
                        <input type="email" wire:model="email" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm">Username</label>
                        <input type="text" wire:model="username" class="w-full border rounded px-3 py-2">
                    </div>

                    <!-- Password & Confirm Password -->
                    <div 
                        x-data="{
                            password: @entangle('password').defer || '',
                            password_confirmation: @entangle('password_confirmation').defer || '',
                            get strength() {
                                let s = 0;
                                if (this.password?.length >= 8) s++;
                                if (/[A-Z]/.test(this.password)) s++;
                                if (/[a-z]/.test(this.password)) s++;
                                if (/[0-9]/.test(this.password)) s++;
                                if (/[^A-Za-z0-9]/.test(this.password)) s++;
                                return s;
                            },
                            get message() {
                                if (this.strength <= 2) return 'Weak';
                                if (this.strength === 3 || this.strength === 4) return 'Medium';
                                return 'Strong';
                            },
                            get barColor() {
                                if (this.strength <= 2) return 'bg-red-500';
                                if (this.strength === 3 || this.strength === 4) return 'bg-yellow-500';
                                return 'bg-green-600';
                            }
                        }"
                        class="col-span-2 grid grid-cols-2 gap-4"
                    >
                        <!-- Password -->
                        <div class="col-span-2 sm:col-span-1">
                            <label class="text-sm">Password</label>
                            <input type="password" x-model="password" wire:model.defer="password" 
                                class="w-full border rounded px-3 py-2 focus:ring focus:ring-[#D7A86E]/30">
                            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                            <!-- Strength Bar -->
                            <template x-if="password?.length > 0">
                                <div class="mt-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div :class="barColor" class="h-2.5 rounded-full transition-all duration-300" 
                                             :style="`width: ${(strength / 5) * 100}%`"></div>
                                    </div>
                                    <p class="text-xs mt-1 font-medium" 
                                       :class="{
                                            'text-red-500': strength <= 2,
                                            'text-yellow-600': strength === 3 || strength === 4,
                                            'text-green-600': strength === 5
                                        }"
                                    >
                                        <span x-text="message"></span> password
                                    </p>
                                </div>
                            </template>

                        </div>

                        <!-- Confirm Password -->
                        <div class="col-span-2 sm:col-span-1">
                            <label class="text-sm">Confirm Password</label>
                            <input type="password" x-model="password_confirmation" wire:model.defer="password_confirmation" 
                                class="w-full border rounded px-3 py-2 focus:ring focus:ring-[#D7A86E]/30">

                            <template x-if="password?.length > 0 && password_confirmation?.length > 0">
                                <p class="text-sm mt-2 font-medium" 
                                   :class="password === password_confirmation ? 'text-green-600' : 'text-red-500'">
                                    <span x-text="password === password_confirmation ? '✅ Passwords match' : '❌ Passwords do not match'"></span>
                                </p>
                            </template>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-4">
                <button type="button" @click="open = false" class="px-4 py-2 border rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-[#D7A86E] text-white rounded-lg hover:bg-[#c28a56]">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.addEventListener('implementor-updated', () => {
    Swal.fire({
        icon: 'success',
        title: 'Implementor updated successfully!',
        showConfirmButton: true,
        timer: 3000,
        timerProgressBar: true,
    }).then(() => location.reload());
});
</script>
