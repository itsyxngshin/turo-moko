<div class="max-w-screen-xl mx-auto">
    @if($course)
        <div>
            <!-- Banner Section -->
            <div class="relative h-64 overflow-hidden rounded-2xl shadow-lg">
                <img src="{{ asset($course->background ?? 'images/default-course.jpg') }}" 
                     alt="{{ $course->name }}" 
                     class="absolute inset-0 w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-transparent"></div>

                <!-- Back Button (top-left) -->
                <a href="{{ route('learner.courses.index') }}" 
                   class="absolute top-4 left-4 z-20 bg-black/60 p-2 rounded-full hover:bg-black transition">
                    <i data-lucide="arrow-left" class="w-5 h-5 text-white"></i>
                </a>

                <!-- Text on top of Banner -->
                <div class="relative z-10 text-white px-6 py-10">
                    <h1 class="text-4xl font-bold">{{ $course->name }}</h1>
                    <p class="text-base mt-2 text-gray-200">{{ $course->subject }}</p>
                </div>
            </div>

            <!-- Course Content -->
            <div class="bg-white p-8 rounded-2xl shadow-md mt-10">
                <!-- Introduction -->
                <h2 class="text-2xl font-semibold text-center">Course Introduction</h2>
                <p class="text-gray-600 text-center mt-3 leading-relaxed max-w-2xl mx-auto">
                    {{ $course->description ?? 'No introduction available.' }}
                </p>

                <!-- Lessons / Topics -->
                <h3 class="text-xl font-semibold mt-10 mb-4">Topics</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($course->lessons as $lesson)
                        <div class="border rounded-xl p-5 flex items-center gap-3 hover:shadow-md hover:bg-gray-50 transition">
                            <i data-lucide="file-text" class="text-red-500 w-6 h-6"></i>
                            <span class="flex-1 font-medium">{{ $lesson->title }}</span>
                            @if($lesson->completed)
                                <i data-lucide="check-circle" class="text-green-500 w-6 h-6"></i>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No lessons available for this course.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <p class="text-center text-gray-500 mt-10">No courses available.</p>
    @endif
</div>
