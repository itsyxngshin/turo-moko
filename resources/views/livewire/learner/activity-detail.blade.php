<div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <a href="{{ route('learner.course.show', $course->course_code) }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-black">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>{{ $course->course_name }}</span>
        </a>
    </div>

    <!-- Assignment Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

        <!-- Assignment Header -->
       <div class="bg-white px-6 py-6 rounded-t-lg shadow-md">
    <h1 class="text-2xl sm:text-3xl font-bold truncate text-black-600">
        {{ $assignment->title }}
    </h1>

    <div class="flex flex-wrap gap-6 mt-4 text-sm">
        <div>
            <span class="font-semibold text-green-600">Opened:</span>
            {{ \Carbon\Carbon::parse($assignment->start_date)->format('l, j F Y, g:i a') }}
        </div>

        @if($assignment->end_date)
        <div>
            <span class="font-semibold text-red-600">Due:</span>
            {{ \Carbon\Carbon::parse($assignment->end_date)->format('l, j F Y, g:i a') }}
        </div>
        @endif
    </div>
</div>


        <!-- Body -->
        <div class="p-6 sm:p-8 space-y-8">

            <!-- Instructions -->
            <div class="prose max-w-none text-gray-700">
                {{ $assignment->instruction }}
            </div>

            <!-- Attachment -->
            @if($assignment->attachment)
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4 border border-blue-200 bg-blue-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold text-blue-900">Assignment File</p>
                        <p class="text-xs text-blue-700">{{ $assignment->attachment_original_name }}</p>
                    </div>
                </div>

                <a href="{{ asset('storage/' . $assignment->attachment) }}"
                   download="{{ $assignment->attachment_original_name }}"
                   class="inline-flex items-center justify-center px-5 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                    Download
                </a>
            </div>
            @endif

            @if(session()->has('error'))
            <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700">
                {{ session('error') }}
            </div>
            @endif

            {{-- ================= NOT SUBMITTED ================= --}}
            @if($currentState === 'NOT_SUBMITTED')
            <div class="space-y-6">

                <h2 class="text-xl font-bold">Submission Status</h2>

                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-gray-50 border">
                        <p class="text-xs text-gray-500">Submission</p>
                        <p class="font-semibold">No attempt</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border">
                        <p class="text-xs text-gray-500">Grading</p>
                        <p class="font-semibold">Not graded</p>
                    </div>
                    <div class="p-4 rounded-xl bg-gray-50 border">
                        <p class="text-xs text-gray-500">Time Remaining</p>
                        <p class="font-semibold {{ $this->timeRemainingColor }}">
                            {{ $this->timeRemaining }}
                        </p>
                    </div>
                </div>

                <div class="flex justify-center pt-4">
                    @if($this->isPastDue)
                        <div class="max-w-md text-center p-6 border-2 border-red-200 bg-red-50 rounded-2xl">
                            <h3 class="text-lg font-bold text-red-700 mb-2">Past Due</h3>
                            <p class="text-sm text-red-600">
                                Submissions are no longer accepted.
                            </p>
                        </div>
                    @else
                        <button wire:click="startSubmission"
                                class="px-10 py-3 bg-black text-white rounded-xl font-semibold hover:bg-gray-800 transition">
                            Submit Assignment
                        </button>
                    @endif
                </div>
            </div>
            @endif

            {{-- ================= SUBMITTING ================= --}}
            @if($currentState === 'SUBMITTING')
            <form wire:submit.prevent="submitAssignment" class="space-y-8">

                @error('submission')
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 font-medium">
                    {{ $message }}
                </div>
                @enderror

                @if($assignment->text_allowed)
                <div>
                    <label class="block font-semibold mb-2">
                        Online Text
                    </label>
                    <textarea
    wire:model="onlineText"
    placeholder="Type your submission here..."
    class="w-full h-40 px-4 py-3 rounded-xl 
           bg-gray-100 text-gray-800
           border border-gray-300
           focus:outline-none focus:ring-2 focus:ring-black
           focus:bg-gray-50
           resize-none">
</textarea>
                    @error('onlineText') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                @endif

                @if($assignment->filetype_allowed)
                <div class="border-2 border-dashed rounded-2xl p-8 text-center hover:border-gray-400 transition">
                    <label for="file-upload" class="cursor-pointer space-y-2 block">
                        <p class="font-semibold">Upload File</p>
                        <p class="text-xs text-gray-500">Click to select a file</p>
                        <input id="file-upload" type="file" wire:model="fileUpload" class="hidden">
                    </label>

                    @if($fileUpload)
                        <p class="mt-4 text-sm font-semibold text-green-600">
                            {{ $fileUpload->getClientOriginalName() }}
                        </p>
                    @endif

                    @error('fileUpload')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <div class="flex flex-col sm:flex-row justify-center gap-4 pt-4">
                    <button type="button" wire:click="cancelSubmission"
                            class="px-8 py-3 rounded-xl bg-gray-200 font-semibold">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-8 py-3 rounded-xl bg-black text-white font-semibold hover:bg-gray-800">
                        Submit
                    </button>
                </div>

            </form>
            @endif

            {{-- ================= SUBMITTED ================= --}}
            @if($currentState === 'SUBMITTED')
            <div class="space-y-6">

                <h2 class="text-xl font-bold">Submission Summary</h2>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl border">
                        <p class="text-xs text-gray-500">Status</p>
                        <p class="font-semibold">Submitted</p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl border">
                        <p class="text-xs text-gray-500">Grading</p>
                        <p class="font-semibold">
                            @if($submission->grade !== null)
                                {{ number_format($submission->grade, 2) }}/100
                            @else
                                Not graded
                            @endif
                        </p>
                    </div>
                </div>

                <div class="p-4 bg-green-50 border border-green-200 rounded-xl">
                    <p class="font-semibold text-green-800">
                        This submission can no longer be edited.
                    </p>

                    @if($submission->feedback)
                    <div class="mt-3 p-3 bg-white rounded-lg border">
                        <p class="text-sm font-semibold">Feedback</p>
                        <p class="text-sm text-gray-600">{{ $submission->feedback }}</p>
                    </div>
                    @endif
                </div>

            </div>
            @endif

        </div>
    </div>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('swal', (event) => {
            const data = event[0] || event;
            Swal.fire({
                icon: data.icon,
                title: data.title,
                text: data.text,
                confirmButtonColor: '#111827'
            });
        });
    });
</script>