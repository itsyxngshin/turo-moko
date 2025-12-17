<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    <!-- Header Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <a href="{{ route('implementor.course-information', $course->course_code) }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 hover:text-black">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>{{ $course->course_title }}</span>
        </a>
    </div>

    <!-- Assignment Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

        <!-- Card Header -->
        <div class="bg-gradient-to-r from-white-900 to-white-800 text-black px-6 py-6 shadow-sm">
            <h1 class="text-2xl sm:text-3xl font-bold truncate">
                Add New Assignment
            </h1>
        </div>

        <!-- Card Body -->
        <div class="p-6 sm:p-8 space-y-8">

            <form wire:submit.prevent="saveAssignment" class="space-y-6">

                <!-- Assignment Name -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Assignment Name</label>
                    <input type="text" wire:model.defer="assignmentName" placeholder="Assignment Name"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-black focus:border-black">
                    @error('assignmentName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Description</label>
                    <textarea wire:model.defer="description" rows="4" placeholder="Description here"
                        class="w-full rounded-xl border border-gray-300 px-4 py-2 focus:ring-black focus:border-black"></textarea>
                    @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block font-semibold mb-2 text-gray-700">Additional Files</label>
                    <div class="border-2 border-dashed rounded-2xl p-6 text-center hover:border-gray-400 transition relative
                        @if($attachment) border-orange-500 bg-orange-50 @endif">

                        @if($attachment)
                            <div class="flex flex-col items-center space-y-2">
                                <p class="text-sm font-semibold text-green-700">{{ $attachment->getClientOriginalName() }}</p>
                                <button type="button" wire:click="removeAttachment"
                                    class="absolute top-3 right-3 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @else
                            <label for="file-upload" class="cursor-pointer space-y-2 block">
                                <p class="font-semibold">Upload File</p>
                                <p class="text-xs text-gray-500">Click to select a file</p>
                                <input id="file-upload" type="file" wire:model="attachment" class="hidden">
                            </label>
                        @endif

                        @error('attachment')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submission Type -->
                <div>
                    <p class="font-semibold mb-2 text-gray-700">Submission Type</p>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="submissionTypes" value="text" class="rounded">
                            <span>Online text</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" wire:model="submissionTypes" value="file" class="rounded">
                            <span>File submissions</span>
                        </label>
                    </div>
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
        class="px-6 py-3 rounded-xl bg-gray-200 font-semibold hover:bg-gray-300 transition"
    >
        Discard
    </button>

    <button type="submit"
        class="px-6 py-3 rounded-xl bg-black text-white font-semibold hover:bg-gray-800 transition">
        Save Assignment
    </button>
</div>


            </form>

        </div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('swal'))
        Swal.fire({
            icon: '{{ session('swal.icon') }}',
            title: '{{ session('swal.title') }}',
            text: '{{ session('swal.text') }}',
            confirmButtonColor: '#000000'
        });
    @endif
</script>