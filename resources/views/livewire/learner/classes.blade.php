<div class="space-y-8 px-4 sm:px-6 md:px-8 w-full"> <!-- SINGLE ROOT ELEMENT -->

    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-6 pt-5">

        <!-- Active Courses -->
        <div class="bg-white shadow rounded-2xl p-4 sm:p-5 flex items-center gap-4 cursor-pointer hover:bg-gray-50"
            onclick="window.location='{{ route('learner.courses.index') }}'">
            <img src="https://img.icons8.com/fluency/48/000000/books.png" class="w-10 h-10" alt="Active Courses">
            <div>
                <p class="font-semibold text-base sm:text-lg">Active courses</p>
                <p class="text-gray-500 text-sm">{{ $activeCoursesCount }}</p>
            </div>
        </div>

        <!-- Pending Activities -->
        <div class="bg-white shadow rounded-2xl p-4 sm:p-5 flex items-center gap-4 cursor-pointer hover:bg-gray-50"
            onclick="window.location='{{ route('learner.activities') }}'">
            <img src="https://img.icons8.com/fluency/48/000000/todo-list.png" class="w-10 h-10" alt="Pending">
            <div>
                <p class="font-semibold text-base sm:text-lg">Pending activities</p>
                <p class="text-gray-500 text-sm">{{ $pendingActivities }}</p>
            </div>
        </div>

        <!-- Evaluation Status -->
<div class="bg-white shadow rounded-2xl p-4 sm:p-5 flex items-center gap-4 cursor-pointer hover:bg-gray-50 transition"
     onclick="window.location='{{ route('learner.evaluation-status') }}'">
    <img src="https://img.icons8.com/fluency/48/000000/survey.png" class="w-10 h-10" alt="Evaluation Status">
    <div>
        <p class="font-semibold text-base sm:text-lg">Evaluation status</p>
        <p class="text-gray-500 text-sm">{{ $pendingEvaluations }} pending</p>
    </div>
</div>


        <!-- Completed Courses -->
        <div class="bg-white shadow rounded-2xl p-4 sm:p-5 flex items-center gap-4 cursor-pointer hover:bg-gray-50"
            onclick="window.location='{{ route('learner.courses.completed') }}'">
            <img src="https://img.icons8.com/fluency/48/000000/checked.png" class="w-10 h-10" alt="Completed">
            <div>
                <p class="font-semibold text-base sm:text-lg">Completed courses</p>
                <p class="text-gray-500 text-sm">{{ $completedCoursesCount }}</p>
            </div>
        </div>

    </div> <!-- END Top Stats -->

    <!-- Recently Accessed -->
    @if($recentCourses->isNotEmpty())
    @php $course = $recentCourses->first(); @endphp
    <div class="relative rounded-2xl overflow-hidden shadow-lg h-60 sm:h-72 md:h-80">
<img src="{{ $course->activeCoverPhoto
                            ? asset('storage/' . $course->activeCoverPhoto->path)
                            : asset('storage/implementor/course/thumbnail.jpg') }}"
             alt="{{ $course->course_title }}" 
             class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10 h-full flex flex-col justify-center px-4 sm:px-8 text-white">
            <p class="text-sm sm:text-base">{{ $course->subject }}</p>
            <h2 class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $course->course_title }}</h2>
            <button class="mt-4 bg-white text-black px-4 py-2 sm:px-5 sm:py-3 rounded-full w-fit hover:bg-gray-200 flex items-center gap-2 text-sm sm:text-base">
                <i data-lucide="play" class="w-4 h-4 sm:w-5 sm:h-5"></i> Continue course
            </button>
        </div>
    </div>
    @else
        <p class="text-gray-500">No recently accessed courses.</p>
    @endif

    <!-- Courses Grid -->
    <section class="bg-white rounded-2xl shadow-md p-4 sm:p-6 mt-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-4 sm:mb-6">
            <h3 class="font-semibold text-lg sm:text-xl">Courses you’re taking</h3>
            <button class="text-sm sm:text-base text-gray-500 hover:underline">View all</button>
        </div>

        <!-- Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            @forelse ($activeCourses as $course)
                <div class="bg-white rounded-2xl shadow-md flex flex-col sm:flex-row overflow-hidden border h-auto sm:h-52">
                    <!-- Course Image -->
                    <div class="w-full sm:w-1/2 h-48 sm:h-full">
                        <img src="{{ $course->activeCoverPhoto
                            ? asset('storage/' . $course->activeCoverPhoto->path)
                            : asset('storage/implementor/course/thumbnail.jpg') }}"
                             class="w-full h-full object-cover rounded-t-2xl sm:rounded-l-2xl sm:rounded-tr-none">
                        <p class="text-xs text-gray-400 mt-1 line-clamp-2 sm:line-clamp-3 px-2 sm:px-0">
                            {{ $course->background ?? 'No description available.' }}
                        </p>
                    </div>

                    <!-- Course Info -->
                    <div class="p-4 sm:p-6 flex flex-col justify-between w-full sm:w-1/2 h-full">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-400">{{ $course->semester ?? 'Ongoing' }}</p>
                            <p class="text-sm sm:text-base text-gray-500">Instructor: {{ $course->instructor ?? 'TBA' }}</p>
                            <h3 class="text-lg sm:text-xl font-bold">{{ $course->course_title }}</h3>
                            <p class="text-xs sm:text-sm text-gray-400 mt-1 line-clamp-2 sm:line-clamp-3">
                                {{ $course->description ?? 'No description available.' }}
                            </p>
                        </div>
                        <div class="flex justify-end mt-2">
                            <a href="{{ route('learner.course.show', $course->id) }}"
                               class="bg-black text-white px-4 sm:px-5 py-1.5 sm:py-2 rounded-full hover:bg-gray-800 text-sm sm:text-base">
                                View Course
                            </a>
                        </div>
                    </div>
                </div>
           @empty
               <div class="col-span-1 sm:col-span-2 text-center text-gray-500 py-10">
                   <p class="text-lg font-semibold">No active courses yet 📚</p>
               </div>
           @endforelse
        </div>
    </section>

</div> <!-- END SINGLE ROOT ELEMENT -->
