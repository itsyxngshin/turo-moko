<div>
    @if($isOpen)
        <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 p-4"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-8 relative max-h-[90vh] overflow-y-auto"
                 @click.away="closeModal">
                
                {{-- Header & Tabs --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold text-[#C28A56]">Add Implementor</h2>
                    
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button wire:click="toggleMode('manual')" 
                                class="px-4 py-1.5 rounded-md text-sm font-medium transition {{ $viewMode === 'manual' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Manual Entry
                        </button>
                        <button wire:click="toggleMode('bulk')" 
                                class="px-4 py-1.5 rounded-md text-sm font-medium transition {{ $viewMode === 'bulk' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Bulk Import (CSV)
                        </button>
                    </div>

                    <button wire:click="closeModal" class="text-gray-500 hover:text-black absolute top-6 right-6 md:static">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Alert Box --}}
                @if($alert['show'])
                    <div class="mb-6 p-4 rounded-lg flex items-start gap-3 shadow-sm transition-all
                        {{ $alert['type'] === 'success' ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
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
                        <button wire:click="resetAlert" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                @endif

                {{-- ================= TAB 1: MANUAL ENTRY ================= --}}
                @if($viewMode === 'manual')
                    <form wire:submit.prevent="save" class="space-y-6">
                        <div class="flex flex-col md:flex-row gap-8">
                            
                            {{-- PHOTO UPLOAD (Drag & Drop, No Flicker) --}}
                            <div class="flex flex-col items-center justify-start pt-4 relative min-w-[200px]"
                                x-data="{ 
                                    dragging: false,
                                    imagePreview: null,
                                    showPreview(event) {
                                        const file = event.target.files[0];
                                        if (file) {
                                            this.imagePreview = URL.createObjectURL(file);
                                        }
                                    },
                                    handleDrop(event) {
                                        this.dragging = false;
                                        const file = event.dataTransfer.files[0];
                                        if (file) {
                                            this.imagePreview = URL.createObjectURL(file);
                                            // Assign to Livewire model manually since x-model doesn't catch drops
                                            @this.upload('photo', file);
                                        }
                                    },
                                    removeImage() {
                                        this.imagePreview = null;
                                        $refs.fileInput.value = ''; // Reset file input
                                        @this.set('photo', null);   // Clear Livewire variable
                                    }
                                }">
                            
                                <div 
                                    x-bind:class="dragging ? 'bg-orange-50 border-[#C28A56]' : 'bg-gray-50 border-gray-300'"
                                    class="relative w-40 h-40 rounded-full border-2 border-dashed flex items-center justify-center cursor-pointer overflow-hidden transition-all duration-200 group"
                                    @click="$refs.fileInput.click()"
                                    @dragover.prevent="dragging = true"
                                    @dragleave.prevent="dragging = false"
                                    @drop.prevent="handleDrop($event)"
                                >
                                    
                                    <div wire:loading wire:target="photo" 
                                        class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-white/90 text-gray-600 text-xs font-medium">
                                        <svg class="animate-spin h-8 w-8 mb-2 text-[#C28A56]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                        </svg>
                                        Uploading...
                                    </div>
                            
                                    <template x-if="imagePreview">
                                        <div class="relative w-full h-full">
                                            <img :src="imagePreview" class="w-full h-full object-cover">
                                            
                                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                                                <span class="text-white text-xs font-bold">Change Photo</span>
                                            </div>
                                        </div>
                                    </template>
                            
                                    <template x-if="!imagePreview">
                                        <div class="flex flex-col items-center text-gray-400 p-2 text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="text-[10px] font-medium">Click or Drop</span>
                                        </div>
                                    </template>
                                </div>
                            
                                <div class="mt-3 text-center w-full">
                                    <template x-if="imagePreview">
                                        <button type="button" @click.stop="removeImage()" class="text-xs text-red-500 hover:text-red-700 font-medium hover:underline">
                                            Remove Photo
                                        </button>
                                    </template>
                            
                                    @error('photo') 
                                        <span class="text-red-500 text-xs block mt-1 bg-red-50 px-2 py-1 rounded border border-red-100">
                                            {{ $message }}
                                        </span> 
                                    @enderror
                                </div>
                            
                                <input 
                                    type="file" 
                                    wire:model="photo" 
                                    x-ref="fileInput" 
                                    hidden 
                                    accept="image/png, image/jpeg, image/jpg, image/gif"
                                    @change="showPreview($event)"
                                >
                            </div>

                            <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- First Name --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">First Name</label>
                                    <input type="text" wire:model="first_name" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                    @error('first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                {{-- Middle Name --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Middle Name</label>
                                    <input type="text" wire:model="middle_name" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                </div>
                                {{-- Last Name --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Last Name</label>
                                    <input type="text" wire:model="last_name" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                    @error('last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                {{-- Phone --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Phone</label>
                                    <div class="relative mt-1">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none"><span class="text-gray-500 font-medium border-r border-gray-300 pr-2">+63</span></div>
                                        <input type="text" wire:model="phonenum" placeholder="9XXXXXXXXX" maxlength="10" class="w-full border rounded pl-14 pr-3 py-2 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                    </div>
                                    @error('phonenum') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                {{-- Email --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" wire:model="email" placeholder="example@gmail.com" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                    <p class="text-[10px] text-gray-400 mt-1">Allowed: @gmail.com, @yahoo.com</p>
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                {{-- Username --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-700">Username</label>
                                    <input type="text" wire:model="username" class="w-full border rounded px-3 py-2 mt-1 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                    @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                {{-- Password Fields --}}
                                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div x-data="{ show: false }">
                                        <label class="text-sm font-medium text-gray-700">Password</label>
                                        <div class="relative mt-1">
                                            <input :type="show ? 'text' : 'password'" wire:model.live.debounce.300ms="password" class="w-full border rounded px-3 py-2 pr-10 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                            </button>
                                        </div>
                                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div x-data="{ show: false }">
                                        <label class="text-sm font-medium text-gray-700">Confirm Password</label>
                                        <div class="relative mt-1">
                                            <input :type="show ? 'text' : 'password'" wire:model="password_confirmation" class="w-full border rounded px-3 py-2 pr-10 focus:ring-2 focus:ring-[#C28A56] outline-none">
                                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                                <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                <svg x-show="show" style="display: none;" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                            <button type="button" wire:click="closeModal" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                            <button type="submit" class="px-5 py-2 bg-[#D7A86E] text-white rounded-lg hover:bg-[#c28a56] shadow-sm transition">
                                Save Implementor
                            </button>
                        </div>
                    </form>

                {{-- ================= TAB 2: BULK IMPORT ================= --}}
                @else
                    <div class="space-y-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <h3 class="text-blue-800 font-bold text-sm mb-2">How to Import</h3>
                            <ul class="text-blue-700 text-sm list-disc pl-5 space-y-1">
                                <li><strong>Format:</strong> First Name, Middle Name, Last Name, Email, Phone (9xxxxxxxxx), Username</li>
                                <li>Passwords will be auto-generated and emailed to the user.</li>
                                <li>Photos cannot be imported here (users can update them later).</li>
                            </ul>
                            <button wire:click="downloadTemplate" class="mt-3 text-xs bg-white border border-blue-300 text-blue-700 px-3 py-1.5 rounded hover:bg-blue-50 font-medium flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                Download CSV Template
                            </button>
                        </div>

                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 flex flex-col items-center justify-center text-center cursor-pointer hover:bg-gray-50 transition"
                             onclick="document.getElementById('csvInput').click()">
                            
                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            
                            <span class="text-gray-600 font-medium">Click to upload .CSV file</span>
                            <span class="text-gray-400 text-xs mt-1">Max size 2MB</span>

                            <input id="csvInput" type="file" wire:model="csvFile" class="hidden" accept=".csv">
                        </div>

                        {{-- File Selected Info --}}
                        @if($csvFile)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded border border-gray-200">
                                <div class="bg-green-100 p-2 rounded text-green-600">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>
                                <span class="text-sm text-gray-700 font-medium truncate">{{ $csvFile->getClientOriginalName() }}</span>
                            </div>
                        @endif

                        @error('csvFile') 
                            <div class="text-red-500 text-sm bg-red-50 p-2 rounded border border-red-200">{{ $message }}</div> 
                        @enderror

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                            <button type="button" wire:click="closeModal" class="px-5 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                            <button wire:click="importCsv" 
                                    wire:loading.attr="disabled"
                                    class="px-5 py-2 bg-[#D7A86E] text-white rounded-lg hover:bg-[#c28a56] shadow-sm transition disabled:opacity-50 flex items-center gap-2">
                                <svg wire:loading wire:target="importCsv" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                <span>Import Users</span>
                            </button>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    @endif
</div>