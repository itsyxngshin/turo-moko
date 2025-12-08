<div> {{-- ✅ single root element --}}
    <div class="p-0 ml-[20px]">

        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Your Courses</h2>

            <div class="flex items-center gap-3">
                <select class="border rounded-full px-4 py-2 text-sm bg-white shadow-sm cursor-pointer">
                    <option value="card">Card View</option>
                    <option value="list">List View</option>
                </select>

                <livewire:modals.implementor.create-course />
            </div>
        </div>

        <!-- Course Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($courses as $course)
                <div class="bg-white rounded-2xl shadow-md border overflow-hidden">
                    <div class="grid md:grid-cols-[160px,1fr] gap-4 p-4 items-start">
                        <img
                            src="{{ $course->activeCoverPhoto ? asset('storage/' . $course->activeCoverPhoto->path) : asset('storage/implementor/course/thumbnail.jpg') }}"
                            alt="{{ $course->course_title }}"
                            class="w-full h-[140px] object-cover rounded-xl"
                        >
                        <div class="flex flex-col justify-between">
                            <div>
                                <p class="text-xs text-gray-400 mb-1">
                                    {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') ?? '' }} - 
                                    {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') ?? '' }}
                                </p>
                                <h3 class="font-semibold text-lg">{{ $course->course_title }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($course->background, 80) }}</p>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('implementor.course-information', $course) }}" class="bg-black text-white px-4 py-1 rounded-full text-sm">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 col-span-2">No courses found.</p>
            @endforelse
        </div>

    </div>
</div>
