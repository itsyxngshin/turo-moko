<div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100 relative">
    
    {{-- HEADER & SEARCH --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Enrollees Management</h2>
        
        <div class="relative w-64">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Search student or course..." 
                   class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-full text-sm focus:outline-none focus:border-blue-500 transition">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded flex justify-between items-center">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700">&times;</button>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto rounded-lg border border-gray-100">
        <table class="min-w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold tracking-wide">
                <tr>
                    <th class="px-6 py-3 rounded-tl-lg">Student</th>
                    <th class="px-6 py-3">Course Enrolled</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 rounded-tr-lg text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($enrollees as $record)
                    <tr class="hover:bg-gray-50 transition-colors">

                        {{-- Student Column --}}
                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200 flex-shrink-0 bg-gray-100">
                                    @if($record->enrollee->profile && $record->enrollee->profile->photo)
                                        <img src="{{ asset('storage/' . $record->enrollee->profile->photo->photos) }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($record->enrollee->profile->first_name ?? 'U') }}&background=random&color=fff" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $record->enrollee->profile->first_name ?? '—' }} 
                                        {{ $record->enrollee->profile->last_name ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $record->enrollee->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Course Column --}}
                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                            {{ $record->course->course_title ?? 'Unknown Course' }}
                        </td>

                        {{-- Date Column --}}
                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $record->enrollment_date ? \Carbon\Carbon::parse($record->enrollment_date)->format('M d, Y') : '—' }}
                        </td>

                        {{-- Status Column --}}
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'Active' => 'bg-green-100 text-green-700 border-green-200',
                                    'Completed' => 'bg-blue-100 text-blue-700 border-blue-200',
                                    'Dropped' => 'bg-red-100 text-red-700 border-red-200',
                                ];
                                $color = $statusColors[$record->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $color }}">
                                {{ $record->status }}
                            </span>
                        </td>

                        {{-- Actions Column --}}
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            
                            {{-- EDIT BUTTON --}}
                            <button wire:click="editEnrollee({{ $record->id }})" 
                                    class="text-blue-500 hover:text-blue-700 hover:bg-blue-50 px-2 py-1.5 rounded transition mr-1" 
                                    title="Edit Information">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            {{-- DROP / UNDROP BUTTONS --}}
                            @if($record->status !== 'Dropped')
                                <button wire:click="confirmDrop({{ $record->id }})" 
                                        class="text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded transition font-bold text-xs uppercase tracking-wide border border-transparent hover:border-red-200">
                                    Drop
                                </button>
                            @else
                                <button wire:click="confirmUndrop({{ $record->id }})" 
                                        class="text-green-500 hover:text-green-700 hover:bg-green-50 px-3 py-1.5 rounded transition font-bold text-xs uppercase tracking-wide border border-transparent hover:border-green-200">
                                    Restore
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                <span>No enrollment records found.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 px-2">
        {{ $enrollees->links() }}
    </div>

    {{-- ======================== MODALS ======================== --}}

    {{-- 1. DROP CONFIRMATION MODAL --}}
    @if($showDropModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Drop Student</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to drop this student? They will lose access to course materials.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="dropEnrollee" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Yes, Drop Student</button>
                    <button wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 2. UNDROP (RESTORE) MODAL --}}
    @if($showUndropModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Restore Student</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to restore this student? Their status will be set back to Active.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="undropEnrollee" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 sm:ml-3 sm:w-auto sm:text-sm">Yes, Restore Student</button>
                    <button wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 3. EDIT INFORMATION MODAL --}}
    @if($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                    
                    <div class="flex justify-between items-center mb-5 border-b pb-3">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Edit Student Information</h3>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-500 text-xl font-bold">&times;</button>
                    </div>

                    <form wire:submit.prevent="updateEnrollee">
                        <div class="flex flex-col md:flex-row gap-8">
                            
                            {{-- LEFT: PHOTO UPLOAD --}}
                            <div class="flex flex-col items-center gap-4 w-full md:w-1/3 border-b md:border-b-0 md:border-r border-gray-100 pb-6 md:pb-0 pr-0 md:pr-6">
                                <div class="relative w-36 h-36 rounded-full overflow-hidden border-4 border-gray-50 bg-gray-100 shadow-sm group">
                                    {{-- Image Logic --}}
                                    @if ($edit_photo)
                                        <img src="{{ $edit_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif ($existing_photo)
                                        <img src="{{ asset('storage/' . $existing_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                        </div>
                                    @endif
                                    
                                    {{-- Loading Overlay --}}
                                    <div wire:loading wire:target="edit_photo" class="absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center z-10">
                                        <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition">
                                        Select Photo
                                        <input type="file" wire:model="edit_photo" class="hidden" accept="image/*">
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500">JPG, PNG up to 2MB</p>
                                    @error('edit_photo') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- RIGHT: INPUT FIELDS --}}
                            <div class="w-full md:w-2/3 space-y-5">
                                
                                {{-- Name Section --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" wire:model="edit_first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                                        @error('edit_first_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" wire:model="edit_last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                                        @error('edit_last_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Middle Name (Optional)</label>
                                    <input type="text" wire:model="edit_middle_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                                </div>

                                <div class="border-t border-gray-100 my-4"></div>

                                {{-- Contact Info --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                        <input type="email" wire:model="edit_email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border">
                                        @error('edit_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <div class="relative mt-1 rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">+63</span>
                                            </div>
                                            <input type="text" wire:model="edit_phonenum" maxlength="10" class="block w-full pl-12 border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border" placeholder="9xxxxxxxxx">
                                        </div>
                                        @error('edit_phonenum') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- Account --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Username</label>
                                    <input type="text" wire:model="edit_username" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border bg-gray-50">
                                    @error('edit_username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">
                                        New Password 
                                        <span class="text-xs text-gray-400 font-normal ml-1">(Leave blank to keep current)</span>
                                    </label>
                                    <input type="password" wire:model="edit_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2 px-3 border" placeholder="••••••••">
                                    @error('edit_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" wire:click="closeModal" class="px-5 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">Cancel</button>
                            <button type="submit" class="px-5 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                                <span wire:loading.remove wire:target="updateEnrollee">Save Changes</span>
                                <span wire:loading wire:target="updateEnrollee">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>