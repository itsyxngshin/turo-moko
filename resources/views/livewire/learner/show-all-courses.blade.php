<div x-data="{ showEnrollModal: false, enrollCourse: {} }" class="container mx-auto px-3 py-0">

    {{-- Header --}}
    <div class="px-4 py-4 flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <button 
                onclick="window.location='{{ route('learner.hub') }}'" 
                class="flex items-center gap-2 text-black hover:text-black font-semibold group"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="hidden group-hover:inline translate-x-0 transition-all duration-300">Back</span>
            </button>
            <h2 class="text-2xl font-bold m-0">All Suggested Courses</h2>
        </div>

        {{-- Search Bar --}}
        <div class="relative w-full max-w-md">
            <input 
                type="text" 
                wire:model.live="search"
                placeholder="Search Course"
                class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-black focus:border-transparent"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    {{-- Courses --}}
    @if($suggestedCourses->isEmpty())
        <p class="text-gray-500">No courses available at the moment.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            @foreach($suggestedCourses as $course)
            <div class="bg-white rounded-2xl shadow-md flex flex-col sm:flex-row overflow-hidden border border-gray-100 h-auto sm:h-52 hover:shadow-lg transition">

                {{-- Cover --}}
                <div class="w-full sm:w-1/2 h-48 sm:h-full relative bg-gray-200">
                    <img 
                        src="{{ $course->activeCoverPhoto ? asset('storage/' . $course->activeCoverPhoto->path) : asset('storage/implementor/course/thumbnail.jpg') }}"
                        alt="{{ $course->course_title }}"
                        class="w-full h-full object-cover"
                    >
                    {{-- Mobile category overlay --}}
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 sm:hidden">
                        <p class="text-white text-xs font-bold truncate">
                            {{ $course->category->category_name ?? 'General' }}
                        </p>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-4 sm:p-5 flex flex-col justify-between w-full sm:w-1/2 h-full">
                    <div>
                        <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-500 text-[10px] font-bold uppercase tracking-wider hidden sm:inline-block">
                            {{ $course->category->category_name ?? 'General' }}
                        </span>

                        <h3 class="mt-1 text-lg font-bold text-gray-900 leading-tight line-clamp-2">
                            {{ $course->course_title ?? 'Untitled Course' }}
                        </h3>

                        <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                            <span>Instructor:</span>
                            <span class="font-bold text-gray-700 truncate max-w-[120px]">
                                {{ $course->implementer->profile->first_name ?? 'Unknown' }}
                            </span>
                        </p>

                        <div class="flex items-center gap-2 mt-1 text-[10px] text-gray-400">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') }} –
                            {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') }}
                        </div>
                    </div>

                    {{-- Join Button --}}
                    <button 
                        @click="enrollCourse = {course_code: '{{ $course->course_code }}', title: '{{ $course->course_title }}', id: {{ $course->id }}}; showEnrollModal = true"
                        class="bg-black text-white px-5 py-2 rounded-full text-xs font-bold shadow-md transition transform hover:-translate-y-0.5 hover:bg-gray-800"
                    >
                        Join Class
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    {{-- Confirm Enrollment Modal --}}
    <template x-teleport="body">
        <div 
            x-show="showEnrollModal"
            x-cloak
            class="fixed inset-0 z-[20000] flex items-center justify-center bg-black/50 backdrop-blur-sm"
            style="display: none;"
        >
            <div 
                @click.away="showEnrollModal = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6 text-center"
            >
                <h3 class="text-lg font-bold text-gray-900 mb-2">Confirm Enrollment</h3>
                <p class="text-sm text-gray-500 mb-6">
                    Are you sure you want to enroll in
                    <strong x-text="enrollCourse.title"></strong>?
                </p>

                <div class="flex gap-3 justify-center">
                    <button 
                        @click="showEnrollModal = false"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 font-medium transition-colors"
                    >
                        Cancel
                    </button>

                    <form :action="`{{ url('learner/course') }}/${enrollCourse.course_code}/enroll`" method="POST">
                        @csrf
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-black text-white rounded-xl hover:bg-gray-800 font-medium transition-colors"
                        >
                            Confirm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </template>

</div>

@if(session('swal'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const swalData = @json(session('swal'));
    Swal.fire({
        icon: swalData.icon,
        title: swalData.title,
        text: swalData.text,
        confirmButtonColor: '#111827'
    });
</script>
@endif
