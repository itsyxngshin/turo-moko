<main class="p-6">
    <h1 class="text-2xl font-semibold mb-6 flex items-center gap-2">
        📁 Archived Courses
    </h1>

    @if(count($archivedCourses) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($archivedCourses as $course)
            <div class="bg-white rounded-2xl shadow-sm border p-5 flex gap-4 hover:bg-gray-50 transition">
                <img rc="{{ $course->activeCoverPhoto
                            ? asset('storage/' . $course->activeCoverPhoto->path)
                            : asset('storage/implementor/course/thumbnail.jpg') }}"
                             alt="{{ $course['name'] }}" class="w-20 h-20 object-contain">
                <div>
                    <p class="text-xs text-gray-400">{{ $course['semester'] }}</p>
                    <h4 class="font-semibold text-base">{{ $course['name'] }}</h4>
                    <p class="text-gray-600 text-sm mt-1 leading-snug line-clamp-2">
                        {{ $course['description'] }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-md p-6 text-center text-gray-500">
            No archived courses yet.
        </div>
    @endif
</main>
