<div>
<div class="p-6">
    <!-- Navigation Header -->
    <div class="flex items-center gap-3 mb-6">
        <!-- Back to Course Button -->
        <a href="{{ route('learner.course.show', $course->course_code) }}" 
           class="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium px-4 py-2 rounded-full shadow-sm hover:shadow-md transition-all duration-200">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to {{ $course->course_name }}</span>
        </a>
    </div>

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
            
            @if($assignment->attachment)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-blue-900">Assignment Materials</p>
                                <p class="text-sm text-blue-700">{{ $assignment->attachment_original_name }}</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $assignment->attachment) }}" 
                           download="{{ $assignment->attachment_original_name }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition">
                            Download
                        </a>
                    </div>
                </div>
            @endif
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
                @if($this->isPastDue)
                    <div class="text-center">
                        <div class="mb-4 p-6 bg-red-50 border-2 border-red-200 rounded-xl inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-red-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-red-700 mb-1">Past Due Date</h3>
                            <p class="text-red-600 text-sm">This assignment is past the due date.<br>Submissions are no longer accepted.</p>
                        </div>
                    </div>
                @else
                    <button wire:click="startSubmission" 
                            class="px-8 py-3 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition">
                        Add submission
                    </button>
                @endif
            </div>
        </div>
        @endif

        <!-- State: SUBMITTING -->
        @if($currentState === 'SUBMITTING')
        <form wire:submit.prevent="submitAssignment">
            @error('submission')
                <div class="mb-6 p-4 bg-red-50 border-2 border-red-200 rounded-xl">
                    <p class="text-red-600 font-medium">{{ $message }}</p>
                </div>
            @enderror

            @if($assignment->text_allowed)
                <!-- Online Text Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4">
                        Online text 
                        @if($assignment->filetype_allowed && $assignment->text_allowed)
                            <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                        @else
                            <span class="text-red-500">*</span>
                        @endif
                    </h3>
                    <textarea 
                        wire:model="onlineText"
                        placeholder="Type your submission here..."
                        class="w-full h-40 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                    ></textarea>
                    @error('onlineText') 
                        <span class="text-red-500 text-sm mt-2">{{ $message }}</span> 
                    @enderror
                </div>
            @endif

            @if($assignment->filetype_allowed)
                <!-- File Submission Section -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4">
                        File submission 
                        @if($assignment->filetype_allowed && $assignment->text_allowed)
                            <span class="text-gray-500 text-sm font-normal">(Optional)</span>
                        @else
                            <span class="text-red-500">*</span>
                        @endif
                    </h3>
                    <p class="text-sm text-gray-600 mb-3">Maximum file size: {{ $assignment->max_file_size >= 1024 ? round($assignment->max_file_size / 1024, 1) . ' MB' : $assignment->max_file_size . ' KB' }}</p>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-12 text-center hover:border-gray-400 transition"
                         :class="{'border-orange-500 bg-orange-50': $wire.fileUpload}">
                        <!-- Uploading State -->
                        <div wire:loading wire:target="fileUpload" class="w-full">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="animate-spin h-16 w-16 text-orange-500 mb-3 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-orange-500 font-medium">Uploading...</span>
                            </div>
                        </div>

                        <!-- Success/Default State -->
                        <div wire:loading.remove wire:target="fileUpload">
                            @if($fileUpload)
                                <div class="flex flex-col items-center justify-center text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-green-600 mb-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-green-600 font-medium">{{ $fileUpload->getClientOriginalName() }}</span>
                                    <span class="text-gray-500 text-sm mt-1">File uploaded successfully</span>
                                    <!-- Remove File Button -->
                                    <button type="button" 
                                            wire:click="removeFile"
                                            wire:loading.attr="disabled"
                                            wire:target="removeFile"
                                            class="mt-3 flex items-center gap-1 px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 rounded-full text-sm font-medium transition"
                                            onclick="event.stopPropagation();">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Remove file
                                    </button>
                                </div>
                            @else
                                <label for="file-upload" class="cursor-pointer block">
                                    <div class="flex flex-col items-center justify-center text-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400 mb-3 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="text-gray-500 font-medium">Upload file</span>
                                    </div>
                                    <input 
                                        type="file" 
                                        id="file-upload"
                                        wire:model="fileUpload"
                                        class="hidden"
                                    />
                                </label>
                            @endif
                        </div>
                        @if($submission && $submission->attachment && !$fileUpload)
                            <div class="mt-4 text-sm text-gray-600">
                                Current file: {{ $submission->attachment_original_name ?? 'Uploaded file' }}
                            </div>
                        @endif
                    </div>
                    @error('fileUpload') 
                        <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-600 text-sm font-medium">{{ $message }}</p>
                        </div>
                    @enderror
                </div>
            @endif

            @if($assignment->text_allowed && $assignment->filetype_allowed)
                <p class="text-sm text-gray-600 mb-6 text-center">
                    <span class="font-semibold">Note:</span> You can submit either a file, online text, or both.
                </p>
            @endif

            <!-- Action Buttons -->
            <div class="flex justify-center gap-4" wire:loading.remove wire:target="fileUpload">
                <button 
                    type="button"
                    wire:click="cancelSubmission"
                    wire:loading.attr="disabled"
                    class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                    Cancel
                </button>
                <button 
                    type="submit"
                    class="px-8 py-3 bg-black text-white rounded-lg font-medium hover:bg-gray-800 transition"
                    wire:loading.attr="disabled"
                    @if($this->isPastDue) disabled @endif
                    :class="{'opacity-50 cursor-not-allowed': @js($this->isPastDue)}">
                    <span wire:loading.remove wire:target="submitAssignment">Add submission</span>
                    <span wire:loading wire:target="submitAssignment">Submitting...</span>
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
                        <td class="px-6 py-4">
                            @if($submission->grade !== null)
                                <span class="text-green-600 font-semibold">
                                    Graded - {{ number_format($submission->grade, 2) }}/100
                                </span>
                            @else
                                <span class="text-gray-600">Not graded</span>
                            @endif
                        </td>
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

            @if($submission->grade === null)
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
            @else
            <div class="mt-8 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center gap-2 text-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">This submission has been graded and can no longer be edited.</span>
                </div>
                @if($submission->feedback)
                <div class="mt-3 p-3 bg-white rounded border border-green-200">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Feedback:</p>
                    <p class="text-gray-600">{{ $submission->feedback }}</p>
                </div>
                @endif
            </div>
            @endif
        </div>
        @endif

    </div>
</div>
</div>

