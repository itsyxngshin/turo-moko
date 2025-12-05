<div>
    {{-- CRITICAL: This @if prevents the modal from existing in the DOM until triggered --}}
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4">
            
            <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-8 relative max-h-[90vh] overflow-y-auto">

                <h2 class="text-2xl font-bold text-[#C28A56] mb-4">Edit Implementor</h2>
                
                {{-- Close Button --}}
                <button wire:click="closeModal" class="absolute top-3 right-3 text-gray-600 hover:text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <form wire:submit.prevent="update" class="space-y-6">
                    <div class="flex flex-col md:flex-row gap-8">
                        
                        <div class="flex flex-col items-center justify-start pt-4 relative min-w-[160px]">
                            <label for="photoEdit" class="cursor-pointer group">
                                <div class="w-40 h-40 rounded-full bg-gray-100 border flex items-center justify-center overflow-hidden relative">
                                    
                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-white text-sm opacity-0 group-hover:opacity-100 transition">Change</div>
                                    @elseif ($existingPhoto)
                                        <img src="{{ Storage::url($existingPhoto) }}" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-white text-sm opacity-0 group-hover:opacity-100 transition">Change</div>
                                    @else
                                        <span class="text-gray-400 text-sm">Click to upload</span>
                                    @endif

                                    {{-- Loading State --}}
                                    <div wire:loading wire:target="photo" class="absolute inset-0 bg-white/80 flex flex-col items-center justify-center text-gray-700 text-sm">
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
                                <button type="button" wire:click="$set('photo', null)" class="mt-3 px-3 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600 transition">Remove New Photo</button>
                            @endif
                            <p class="text-xs text-gray-400 mt-2">Max 1MB, JPEG/PNG</p>
                            @error('photo') <span class="text-red-500 text-sm text-center">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium">First Name</label>
                                <input type="text" wire:model.defer="first_name" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-[#C28A56]">
                                @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium">Middle Name</label>
                                <input type="text" wire:model.defer="middle_name" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-[#C28A56]">
                            </div>
                            <div>
                                <label class="text-sm font-medium">Last Name</label>
                                <input type="text" wire:model.defer="last_name" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-[#C28A56]">
                                @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium">Phone</label>
                                <input type="text" wire:model.defer="phonenum" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-[#C28A56]">
                                @error('phonenum') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium">Email</label>
                                <input type="email" wire:model.defer="email" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-[#C28A56]">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="text-sm font-medium">Username</label>
                                <input type="text" wire:model.defer="username" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-[#C28A56]">
                                @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-1 md:col-span-2 border-t pt-4 mt-2">
                                <p class="text-xs text-gray-500 mb-2">Leave blank to keep current password.</p>
                                
                                <div x-data="{
                                        password: @entangle('password').defer,
                                        password_confirmation: @entangle('password_confirmation').defer,
                                        get strength() {
                                            let s = 0;
                                            if (!this.password) return 0;
                                            if (this.password.length >= 8) s++;
                                            if (/[A-Z]/.test(this.password)) s++;
                                            if (/[a-z]/.test(this.password)) s++;
                                            if (/[0-9]/.test(this.password)) s++;
                                            if (/[^A-Za-z0-9]/.test(this.password)) s++;
                                            return s;
                                        },
                                        get message() {
                                            if (this.strength <= 2) return 'Weak';
                                            if (this.strength <= 4) return 'Medium';
                                            return 'Strong';
                                        },
                                        get color() {
                                            if (this.strength <= 2) return 'bg-red-500';
                                            if (this.strength <= 4) return 'bg-yellow-500';
                                            return 'bg-green-600';
                                        }
                                     }" 
                                     class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    
                                    <div>
                                        <label class="text-sm font-medium">New Password</label>
                                        <input type="password" x-model="password" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-[#C28A56]">
                                        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                        <template x-if="password && password.length > 0">
                                            <div class="mt-2">
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div :class="color" class="h-1.5 rounded-full transition-all duration-300" :style="`width: ${(strength / 5) * 100}%`"></div>
                                                </div>
                                                <p class="text-xs mt-1" x-text="message"></p>
                                            </div>
                                        </template>
                                    </div>

                                    <div>
                                        <label class="text-sm font-medium">Confirm Password</label>
                                        <input type="password" x-model="password_confirmation" class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-[#C28A56]">
                                        
                                        <template x-if="password && password.length > 0 && password_confirmation && password_confirmation.length > 0">
                                            <p class="text-xs mt-2 font-medium" :class="password === password_confirmation ? 'text-green-600' : 'text-red-500'">
                                                <span x-text="password === password_confirmation ? '✅ Match' : '❌ Mismatch'"></span>
                                            </p>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-4 border-t pt-4">
                        {{-- FIXED: Changed from @click to wire:click because logic is server-side --}}
                        <button type="button" wire:click="closeModal" class="px-4 py-2 border rounded-lg hover:bg-gray-50 transition">Cancel</button>
                        
                        <button type="submit" class="px-4 py-2 bg-[#D7A86E] text-white rounded-lg hover:bg-[#c28a56] transition shadow-md">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>