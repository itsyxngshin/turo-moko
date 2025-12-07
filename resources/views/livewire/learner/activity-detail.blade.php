<div>
<div class="p-6">
    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 max-w-6xl mx-auto">
        
        <!-- Activity Header -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h1 class="text-3xl font-bold mb-4">{{ $assignment->title }}</h1>
            <div class="flex gap-8 text-sm">
                <div>
                    <span class="font-semibold">Opened:</span> 
                    <span class="text-gray-600">{{ \Carbon\Carbon::parse($assignment->start_date)->format('l, j F Y, g:i a') }}</span>
                </div>
                @if($assignment->end_date)
                <div>
                    <span class="font-semibold">Due:</span> 
                    <span class="text-gray-600">{{ \Carbon\Carbon::parse($assignment->end_date)->format('l, j F Y, g:i a') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Activity Description -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <p class="text-gray-700 leading-relaxed">
                {{ $assignment->instruction }}
            </p>
        </div>

        @if(session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <!-- State: NOT SUBMITTED -->
        @if($currentState === 'NOT_SUBMITTED')
        <div>
            <h2 class="text-2xl font-bold mb-6">Submission status</h2>
            
            <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
                <tbody>
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4 bg-gray-50 font-semibold w-1/3">Submission status</td>
                        <td class="px-6 py-4">No attempt</td>
                    </tr>
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4 bg-gray-50 font-semibold">Grading status</td>
                        <td class="px-6 py-4">Not graded</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 bg-gray-50 font-semibold">Time remaining</td>
                        <td class="px-6 py-4 {{ $this->timeRemainingColor }} font-medium">{{ $this->timeRemaining }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-center mt-8">
                <button wire:click="startSubmission" 
                        class="px-8 py-3 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition">
                    Add submission
                </button>
            </div>
        </div>
        @endif

        <!-- State: SUBMITTING -->
        @if($currentState === 'SUBMITTING')
        <form wire:submit.prevent="submitAssignment">
            <!-- Online Text Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4">Online text</h3>
                <textarea 
                    wire:model="onlineText"
                    placeholder="Type a text"
                    class="w-full h-40 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                ></textarea>
            </div>

            <!-- File Submission Section -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-4">File submission</h3>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center hover:border-gray-400 transition">
                    <label for="file-upload" class="cursor-pointer">
                        <div class="flex flex-col items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-gray-500 font-medium">Upload file</span>
                            <input 
                                type="file" 
                                id="file-upload"
                                wire:model="fileUpload"
                                class="hidden"
                            />
                        </div>
                    </label>
                    @if($fileUpload)
                        <div class="mt-4 text-sm text-green-600">
                            Selected: {{ $fileUpload->getClientOriginalName() }}
                        </div>
                    @endif
                    @if($submission && $submission->attachment && !$fileUpload)
                        <div class="mt-4 text-sm text-gray-600">
                            Current file: {{ $submission->attachment_original_name ?? 'Uploaded file' }}
                        </div>
                    @endif
                </div>
                @error('fileUpload') 
                    <span class="text-red-500 text-sm mt-2">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-center">
                <button 
                    type="submit"
                    class="px-8 py-3 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>Add submission</span>
                    <span wire:loading>Submitting...</span>
                </button>
            </div>
        </form>
        @endif

        <!-- State: SUBMITTED -->
        @if($currentState === 'SUBMITTED')
        <div>
            <h2 class="text-2xl font-bold mb-6">Submission status</h2>
            
            <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
                <tbody>
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4 bg-gray-50 font-semibold w-1/3">Submission status</td>
                        <td class="px-6 py-4">Submitted for grading</td>
                    </tr>
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4 bg-gray-50 font-semibold">Grading status</td>
                        <td class="px-6 py-4">Not graded</td>
                    </tr>
                    <tr class="border-b border-gray-300">
                        <td class="px-6 py-4 bg-gray-50 font-semibold">Time remaining</td>
                        <td class="px-6 py-4 {{ $this->timeRemainingColor }} font-medium">{{ $this->timeRemaining }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 bg-gray-50 font-semibold">Submission type</td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                @if($submission->instruction)
                                    <div>Online text</div>
                                @endif
                                @if($submission->attachment)
                                    <div>File submission</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex justify-center gap-4 mt-8">
                <button 
                    wire:click="editSubmission"
                    class="px-8 py-3 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition"
                >
                    Edit submission
                </button>
                <button 
                    wire:click="removeSubmission"
                    class="px-8 py-3 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition"
                    onclick="return confirm('Are you sure you want to remove this submission?')"
                >
                    Remove submission
                </button>
            </div>
        </div>
        @endif

    </div>
</div>
</div>

