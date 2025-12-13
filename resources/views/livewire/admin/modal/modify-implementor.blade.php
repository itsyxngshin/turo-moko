<div>
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-8 relative max-h-[90vh] overflow-y-auto"
                 @click.away="closeModal">
                
                <h2 class="text-2xl font-bold text-[#C28A56] mb-4">Edit Implementor</h2>
                
                <button wire:click="closeModal" class="absolute top-4 right-4 text-gray-500 hover:text-black">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                @if($alert['show'])
                    <div class="mb-6 p-4 rounded-lg flex items-start gap-3 shadow-sm transition-all
                        {{ $alert['type'] === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        
                        {{-- Icon --}}
                        @if($alert['type'] === 'success')
                            <svg class="w-5 h-5 text-green-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @else
                            <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @endif

                        <div class="flex-1">
                            <h3 class="text-sm font-bold {{ $alert['type'] === 'success' ? 'text-green-800' : 'text-red-800' }}">
                                {{ $alert['type'] === 'success' ? 'Success!' : 'Error' }}
                            </h3>
                            <p class="text-sm {{ $alert['type'] === 'success' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $alert['message'] }}
                            </p>
                        </div>

                        {{-- Close Alert Button --}}
                        <button wire:click="resetAlert" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endif

                <form wire:submit.prevent="update" class="space-y-6">
                    <div class="flex flex-col md:flex-row gap-8">
                        
                        <div class="flex flex-col items-center justify-start pt-4 relative min-w-[160px]">
    
                            <label for="photoEdit" class="cursor-pointer group relative">
                                <div class="w-40 h-40 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden hover:border-[#C28A56] transition relative">
                                    
                                    {{-- 1. PREVIEW LOGIC --}}
                                    @if ($photo)
                                        <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover" alt="New Preview">
                                    @elseif ($existingPhoto)
                                        <img src="{{ asset('storage/' . $existingPhoto) }}" class="w-full h-full object-cover" alt="Current Photo">
                                    @else
                                        <div class="flex flex-col items-center text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                            <span class="text-xs">No Photo</span>
                                        </div>
                                    @endif

                                    {{-- Hover Overlay --}}
                                    <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center text-white text-xs opacity-0 group-hover:opacity-100 transition z-10">
                                        Change Photo
                                    </div>

                                    {{-- 2. UPLOAD LOADING OVERLAY (Specific to Photo) --}}
                                    {{-- This only shows when the "photo" property is busy (uploading) --}}
                                    <div wire:loading wire:target="photo" class="absolute inset-0 bg-white/90 backdrop-blur-sm flex flex-col items-center justify-center text-gray-600 text-xs font-medium z-20">
                                        <svg class="animate-spin h-6 w-6 mb-2 text-[#C28A56]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                        <span class="animate-pulse">Uploading...</span>
                                    </div>
                                </div>
                            </label>
                            
                            {{-- Input must use .live for instant preview --}}
                            <input id="photoEdit" type="file" wire:model.live="photo" class="hidden" accept="image/png, image/jpeg, image/jpg">

                            <div class="mt-3 text-center">
                                @if ($photo)
                                    {{-- Hide remove button while uploading --}}
                                    <div wire:loading.remove wire:target="photo">
                                        <button type="button" wire:click="$set('photo', null)" class="text-xs text-red-500 hover:text-red-700 font-medium hover:underline mb-1 block mx-auto">
                                            Undo Change
                                        </button>
                                    </div>
                                @endif

                                <p class="text-[10px] text-gray-400 uppercase tracking-wide">Max Size: 1MB <br> Formats: JPG, PNG</p>

                                @error('photo') 
                                    <span class="text-red-500 text-xs block mt-1 bg-red-50 px-2 py-1 rounded border border-red-100">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div>
                                <label class="text-sm font-medium text-gray-700">First Name</label>
                                <input type="text" wire:model="first_name" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Middle Name</label>
                                <input type="text" wire:model="middle_name" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Last Name</label>
                                <input type="text" wire:model="last_name" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Phone</label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 font-medium border-r border-gray-300 pr-2">+63</span>
                                    </div>
                                    <input 
                                        type="text" 
                                        wire:model="phonenum" 
                                        placeholder="9XXXXXXXXX" 
                                        maxlength="10"
                                        class="w-full border rounded pl-14 pr-3 py-2 focus:ring-2 focus:ring-[#C28A56] outline-none"
                                    >
                                </div>
                                @error('phonenum') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Email</label>
                                <input type="email" wire:model="email" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                <p class="text-[10px] text-gray-400 mt-1">Allowed: @gmail.com, @turo-moko.com</p>
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Username</label>
                                <input type="text" wire:model="username" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-1 md:col-span-2 border-t pt-4 mt-2">
                                <p class="text-xs text-gray-500 mb-2 italic">Leave blank to keep current password.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    
                                    {{-- Password Field --}}
                                    <div x-data="{ show: false }">
                                        <label class="text-sm font-medium text-gray-700">New Password</label>
                                        <div class="relative mt-1">
                                            <input 
                                                :type="show ? 'text' : 'password'" 
                                                wire:model.live.debounce.300ms="password" 
                                                class="w-full border rounded px-3 py-2 pr-10 focus:ring-2 focus:ring-[#C28A56] outline-none"
                                            >
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                            </button>
                                        </div>
                                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                        {{-- Strength Meter --}}
                                        @if($password)
                                        <div class="mt-2" x-data="{ 
                                            strength: 0,
                                            calculate() {
                                                let s = 0;
                                                let p = $wire.password;
                                                if (!p) return 0;
                                                if (p.length >= 8) s++;
                                                if (/[A-Z]/.test(p)) s++;
                                                if (/[a-z]/.test(p)) s++;
                                                if (/[0-9]/.test(p)) s++;
                                                if (/[^A-Za-z0-9]/.test(p)) s++;
                                                this.strength = s;
                                            }
                                        }" x-effect="calculate()">
                                            <div class="w-full bg-gray-200 rounded-full h-1">
                                                <div class="h-1 rounded-full transition-all duration-300" 
                                                     :class="{
                                                        'bg-red-500 w-1/4': strength <= 2,
                                                        'bg-yellow-500 w-2/4': strength == 3,
                                                        'bg-blue-500 w-3/4': strength == 4,
                                                        'bg-green-500 w-full': strength == 5
                                                     }"></div>
                                            </div>
                                            <p class="text-[10px] mt-1 text-gray-500" x-text="strength <= 2 ? 'Weak' : (strength <= 4 ? 'Medium' : 'Strong')"></p>
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div x-data="{ show: false }">
                                        <label class="text-sm font-medium text-gray-700">Confirm New Password</label>
                                        <div class="relative mt-1">
                                            <input 
                                                :type="show ? 'text' : 'password'" 
                                                wire:model="password_confirmation" 
                                                class="w-full border rounded px-3 py-2 pr-10 focus:ring-2 focus:ring-[#C28A56] outline-none"
                                            >
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                        <button type="button" wire:click="closeModal" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                        
                        <button 
                            type="submit" 
                            {{-- DISABLE button if "update" is running OR if "photo" is uploading --}}
                            wire:loading.attr="disabled"
                            wire:target="update, photo"
                            class="px-5 py-2 bg-[#D7A86E] text-white rounded-lg hover:bg-[#c28a56] shadow-sm transition flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                            
                            {{-- Normal State --}}
                            <span wire:loading.remove wire:target="update, photo">Update Implementor</span>
                            
                            {{-- Saving State --}}
                            <span wire:loading wire:target="update" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Saving...
                            </span>

                            {{-- Uploading State --}}
                            <span wire:loading wire:target="photo" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                                Uploading Photo...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Script for Alerts (Standardized with Add Modal) --}}
    <script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('swal-notify', (event) => {
            const data = event[0]; 
            Swal.fire({
                icon: data.icon,
                title: data.title,
                text: data.text,
                confirmButtonColor: '#C28A56',
                confirmButtonText: 'Okay',
                timer: data.icon === 'success' ? 3000 : null,
                timerProgressBar: data.icon === 'success'
            });
        });
    });
    </script>
</div>