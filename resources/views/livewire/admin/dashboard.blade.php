<div class="max-w-[1720px] mx-auto px-6 pb-16">

    <section class="mt-3 grid grid-cols-1 lg:grid-cols-4 gap-6">

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-[335px]">
            <svg class="absolute inset-0 m-auto w-32 h-32 text-indigo-100 opacity-20" fill="currentColor" viewBox="0 0 32 32">
                <path d="M7.5 18A3.5 3.5 0 0 0 4 21.5v.5c0 2.393 1.523 4.417 3.685 5.793C9.859 29.177 12.802 30 16 30s6.14-.823 8.315-2.206C26.477 26.418 28 24.394 28 22v-.5a3.5 3.5 0 0 0-3.5-3.5z"/>
                <path d="M16 16a7 7 0 1 0 0-14a7 7 0 0 0 0 14"/>
            </svg>
            <div class="w-16 h-16 rounded-full bg-indigo-50 flex items-center justify-center absolute top-4 left-4 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-indigo-500" fill="currentColor" viewBox="0 0 32 32">
                    <path d="M7.5 18A3.5 3.5 0 0 0 4 21.5v.5c0 2.393 1.523 4.417 3.685 5.793C9.859 29.177 12.802 30 16 30s6.14-.823 8.315-2.206C26.477 26.418 28 24.394 28 22v-.5a3.5 3.5 0 0 0-3.5-3.5z"/>
                    <path d="M16 16a7 7 0 1 0 0-14a7 7 0 0 0 0 14"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500">Enrollees</h3>
                <div class="mt-1 flex items-center gap-2 justify-end">
                    <p class="text-3xl font-semibold text-slate-900">{{ $enrolleesCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-[335px]">
            <svg class="absolute inset-0 m-auto w-32 h-32 text-yellow-100 opacity-20" fill="currentColor" viewBox="0 0 32 32">
                <path d="M7.5 18A3.5 3.5 0 0 0 4 21.5v.5c0 2.393 1.523 4.417 3.685 5.793C9.859 29.177 12.802 30 16 30s6.14-.823 8.315-2.206C26.477 26.418 28 24.394 28 22v-.5a3.5 3.5 0 0 0-3.5-3.5z"/>
                <path d="M16 16a7 7 0 1 0 0-14a7 7 0 0 0 0 14"/>
            </svg>
            <div class="w-16 h-16 rounded-full bg-yellow-50 flex items-center justify-center absolute top-4 left-4 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-yellow-500" fill="currentColor" viewBox="0 0 32 32">
                    <path d="M7.5 18A3.5 3.5 0 0 0 4 21.5v.5c0 2.393 1.523 4.417 3.685 5.793C9.859 29.177 12.802 30 16 30s6.14-.823 8.315-2.206C26.477 26.418 28 24.394 28 22v-.5a3.5 3.5 0 0 0-3.5-3.5z"/>
                    <path d="M16 16a7 7 0 1 0 0-14a7 7 0 0 0 0 14"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500">Implementors</h3>
                <div class="mt-1 flex items-center gap-2 justify-end">
                    <p class="text-3xl font-semibold text-slate-900">{{ $implementorsCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-[335px]">
            <svg class="absolute inset-0 m-auto w-32 h-32 text-green-200 opacity-20" fill="currentColor" viewBox="0 0 24 24">
                <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
            </svg>
            <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center absolute top-4 left-4 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                    <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500">Courses</h3>
                <div class="mt-1 flex items-center gap-2 justify-end">
                    <p class="text-3xl font-semibold text-slate-900">{{ $coursesCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-[335px] flex flex-col items-center justify-center">
            <h3 class="text-sm font-medium text-slate-500 mb-4">Overall Activity</h3>
            <div class="relative w-40 h-40">
                <svg viewBox="0 0 40 40" class="w-40 h-40 rotate-[-90deg]">
                    <circle cx="20" cy="20" r="18" fill="none" stroke="#E5F6FE" stroke-width="4" />
                    <circle cx="20" cy="20" r="18" fill="none" stroke="#8BDCFC" stroke-width="4"
                        stroke-dasharray="113"
                        stroke-dashoffset="{{ 113 - (($activeStudents ?? 0)/100*113) }}"
                        stroke-linecap="round" />
                </svg>
                <div class="absolute inset-0 grid place-items-center">
                    <svg viewBox="0 0 36 36" class="w-28 h-28 rotate-[-90deg]">
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#E5EEF2" stroke-width="4" />
                        <circle cx="18" cy="18" r="16" fill="none" stroke="#A0BDCB" stroke-width="4"
                            stroke-dasharray="100.5"
                            stroke-dashoffset="{{ 100.5 - (($activeMentors ?? 0)/100*100.5) }}"
                            stroke-linecap="round" />
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-start justify-between w-full px-6">
                <div class="text-center">
                    <div class="text-3xl font-medium">{{ $activeStudents }}%</div>
                    <div class="text-sm text-black/80">Active Students</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-medium">{{ $activeMentors }}%</div>
                    <div class="text-sm text-black/80">Active Mentors</div>
                </div>
            </div>
        </div>

    </section>

    <section class="mt-10 bg-white rounded-2xl shadow-md border border-black/20">
        <div class="flex items-center justify-between px-6 py-4">
            <h2 class="text-2xl md:text-[28px] font-semibold mt-4 ml-4">All Courses</h2>
            <a href="{{ route('admin.courses') }}" class="text-sm text-gray-500 hover:underline">View All</a>
        </div>

        <div class="px-6 pb-8 grid grid-cols-1 xl:grid-cols-2 gap-6">
            @forelse($latestCourses as $course)
                <article class="relative rounded-2xl border border-gray-300 bg-white shadow-sm overflow-hidden hover:shadow-md transition">
                    <div class="grid md:grid-cols-[300px,1fr] grid-cols-1 gap-4 p-4">
                        <div class="w-full md:w-[300px] h-[180px] rounded-xl overflow-hidden bg-gray-200">
                            @if($course->activeCoverPhoto)
                                <img src="{{ asset('storage/' . $course->activeCoverPhoto->path) }}" 
                                     alt="{{ $course->course_title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                        </div>

                        <div class="pr-4 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg md:text-xl font-medium text-black line-clamp-1" title="{{ $course->course_title }}">
                                    {{ $course->course_title }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-600">
                                    Duration: {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') }} - 
                                    {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') }}
                                </p>
                                <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                    {{ $course->background }}
                                </p>
                            </div>
                            <div class="mt-4 flex justify-center md:justify-start">
                                
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-1 xl:col-span-2 text-center py-10 text-gray-500">
                    No courses available yet.
                </div>
            @endforelse
        </div>
    </section>

</div>