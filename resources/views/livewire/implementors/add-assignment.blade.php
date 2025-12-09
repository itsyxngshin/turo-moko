<div class="min-h-screen bg-[#fdfaf8] flex">

    <!-- Main Content -->
    <main class="flex-1 pl-5 py-3">
        <!-- Header -->
        <div class="flex justify-between items-center mb-2">
            <h1 class="text-2xl font-semibold text-gray-800">{{ $course->course_title }}</h1>
        </div>

        <!-- Assignment Form -->
        <div class="bg-white shadow-md rounded-2xl p-8">
            <h2 class="text-xl font-semibold mb-6">Add an assignment</h2>

            <form wire:submit.prevent="saveAssignment" class="space-y-6">
                <!-- General -->
                <div class="border rounded-xl p-5 bg-gray-50">
                    <details open>
                        <summary class="font-medium text-gray-700 cursor-pointer mb-3">General</summary>

                        <div class="space-y-4 mt-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Assignment Name</label>
                                <input type="text" wire:model.defer="assignmentName"
                                    class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:outline-none">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Description</label>
                                <textarea wire:model.defer="description" rows="4"
                                    class="w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Additional Files</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center cursor-pointer hover:bg-gray-50"
                                     :class="{'border-blue-500 bg-blue-50': $wire.attachment}">
                                    <input type="file" wire:model="attachment" class="hidden" id="uploadFile">
                                    <label for="uploadFile" class="cursor-pointer">
                                        <!-- Uploading State -->
                                        <div wire:loading wire:target="attachment" class="flex flex-col items-center space-y-2">
                                            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span class="text-blue-600 text-sm font-medium">Uploading...</span>
                                        </div>
                                        
                                        <!-- Success State -->
                                        <div wire:loading.remove wire:target="attachment">
                                            @if($attachment)
                                                <div class="flex flex-col items-center space-y-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-green-600 text-sm font-medium">{{ $attachment->getClientOriginalName() }}</span>
                                                    <span class="text-gray-500 text-xs">File uploaded successfully</span>
                                                </div>
                                            @else
                                                <div class="flex flex-col items-center space-y-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                                        <polyline points="14 2 14 8 20 8"/>
                                                    </svg>
                                                    <span class="text-gray-600 text-sm">Upload file</span>
                                                </div>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </details>
                </div>

                <!-- Availability -->
                <div class="border rounded-xl p-5 bg-gray-50">
                    <details>
                        <summary class="font-medium text-gray-700 cursor-pointer mb-3">Availability</summary>

                        <div class="mt-3 space-y-2 px-3">
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-600">● Due date</span>
                                <input type="checkbox" wire:model="enableDueDate" class="rounded">
                                <label class="text-gray-600">Enable</label>
                            
                                <select wire:model="dueDay" class="border border-gray-300 rounded-lg p-2">
                                    @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>

                                <select wire:model="dueMonth" class="border border-gray-300 rounded-lg p-2">
                                    @foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                                        <option>{{ $month }}</option>
                                    @endforeach
                                </select>

                                <select wire:model="dueYear" class="border border-gray-300 rounded-lg p-2">
                                    @for ($y = now()->year; $y <= now()->year + 5; $y++)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>

                                <input type="time" wire:model="dueTime" class="p-2 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                    </details>
                </div>

                <!-- Submission Type -->
                <div class="border rounded-xl p-5 bg-gray-50">
                    <details>
                        <summary class="font-medium text-gray-700 cursor-pointer mb-3">Submission type</summary>

                        <div class="mt-3 space-y-4">
                            <div class="space-y-2 px-3">
                                <p class="text-gray-600">● Submission types</p>
                                <div class="flex items-center px-3 space-x-4">
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" wire:model="submissionTypes" value="text" class="rounded">
                                        <span>Online text</span>
                                    </label>
                                    <label class="flex items-center space-x-2">
                                        <input type="checkbox" wire:model="submissionTypes" value="file" class="rounded">
                                        <span>File submissions</span>
                                    </label>
                                </div>
                            </div>

                            <div class="px-3">
                                <p class="text-gray-600">● Maximum submission size</p>
                                <select wire:model="maxSize" class="border-gray-300 rounded-lg">
                                    <option>1 mb</option>
                                    <option>5 mb</option>
                                    <option>10 mb</option>
                                </select>
                            </div>
                        </div>
                    </details>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3 pt-6">
                    <button 
                        type="button" 
                        @click="
                            Swal.fire({
                                title: 'Discard changes?',
                                text: 'Your unsaved progress will be lost.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, discard'
                            }).then((result) => {
                                if (result.isConfirmed) location.reload();
                            });
                        "
                        class="px-5 py-2 border border-gray-400 rounded-full hover:bg-gray-100"
                    >
                        Discard
                    </button>

                    <button type="submit" class="px-5 py-2 bg-black text-white rounded-full hover:bg-gray-800">
                        Save and display
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>