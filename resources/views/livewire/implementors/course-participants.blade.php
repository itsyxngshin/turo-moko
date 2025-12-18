<div class="m-4 p-6 bg-white rounded-lg shadow"> <div class="flex items-center mb-4">
        <a href="{{ route('implementor.course-information', ['course' => $course->course_code]) }}" 
           class="mr-4 hover:bg-gray-300 text-gray-800 px-2 py-1 rounded-full inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" 
                      d="M244 400L100 256l144-144M120 256h292"/>
            </svg>
        </a>

        <h2 class="text-xl font-bold">Enrollees for: {{ $course->name }}</h2>
    </div>

    @if (session()->has('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="text-red-600 mb-4">{{ session('error') }}</div>
    @endif

    <livewire:implementors.live-search-students :course="$course" />

</div>