<div class="space-y-8">
    <!-- Enrolled Courses -->
    <section class="bg-white rounded-2xl shadow p-6">
        <h3 class="font-semibold text-lg mb-6">Enrolled courses</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse ($enrolledCourses as $course)
                <div class="flex bg-white rounded-2xl shadow-sm overflow-hidden border">
                    <img src="{{ $course->image ?? 'https://picsum.photos/200/300' }}" 
                         alt="Course Image" class="w-40 h-auto object-cover">
                    
                    <div class="flex-1 p-5 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-xs text-gray-400">{{ $course->semester ?? '1st Sem SY 2024-2025' }}</p>
                                <button aria-label="More options" class="text-gray-400 hover:text-gray-600">
                                    <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <h4 class="font-semibold text-lg mb-1">{{ $course->title }}</h4>
                            <p class="text-sm text-gray-500 leading-snug">
                                {{ $course->description }}
                            </p>
                        </div>

                        <div class="mt-4 flex items-center gap-4">
                            <div class="flex items-center gap-2 w-full">
                                <span class="text-xs text-gray-500">{{ $course->progress ?? 0 }}%</span>
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-black" style="width: {{ $course->progress ?? 0 }}%;"></div>
                                </div>
                            </div>
                            <button class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800">
                                Start
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">You are not enrolled in any courses yet.</p>
            @endforelse
        </div>
    </section>
</div>
