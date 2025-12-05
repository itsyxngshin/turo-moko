@section('title', 'Implementor Dashboard')

<div class="max-w-[1720px] mx-auto px-6 pb-16">
    
    <!-- 1. Page Header (Establishes Primary Hierarchy) -->
    <div class="mt-8 mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Overview</h1>
        <p class="text-sm text-gray-500 mt-1">Welcome back! Here's what's happening with your courses today.</p>
    </div>

    <!-- 2. Stats Row (Secondary Hierarchy) -->
    <section aria-labelledby="stats-heading" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <h2 id="stats-heading" class="sr-only">Statistics</h2>

        <!-- Enrollees Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-48 group">
            <!-- Background Watermark Icon (Fixed Positioning) -->
            <svg class="absolute -right-6 -bottom-6 w-32 h-32 text-indigo-50 group-hover:text-indigo-100 transition-colors" fill="currentColor" viewBox="0 0 32 32">
                <path d="M7.5 18A3.5 3.5 0 0 0 4 21.5v.5c0 2.393 1.523 4.417 3.685 5.793C9.859 29.177 12.802 30 16 30s6.14-.823 8.315-2.206C26.477 26.418 28 24.394 28 22v-.5a3.5 3.5 0 0 0-3.5-3.5z"/>
                <path d="M16 16a7 7 0 1 0 0-14a7 7 0 0 0 0 14"/>
            </svg>

            <!-- Main Icon -->
            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center absolute top-6 left-6 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" fill="currentColor" viewBox="0 0 32 32">
                    <path d="M7.5 18A3.5 3.5 0 0 0 4 21.5v.5c0 2.393 1.523 4.417 3.685 5.793C9.859 29.177 12.802 30 16 30s6.14-.823 8.315-2.206C26.477 26.418 28 24.394 28 22v-.5a3.5 3.5 0 0 0-3.5-3.5z"/>
                    <path d="M16 16a7 7 0 1 0 0-14a7 7 0 0 0 0 14"/>
                </svg>
            </div>

            <!-- Data & Label -->
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Enrollees</h3>
                <div class="mt-2 flex items-center gap-3 justify-end">
                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium">Current</span>
                    <p class="text-4xl font-bold text-slate-900">{{ $enrolleesCount ?? '--' }}</p>
                </div>
            </div>
        </div>

        <!-- Submissions Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-48 group">
            <svg class="absolute -right-6 -bottom-6 w-32 h-32 text-amber-50 group-hover:text-amber-100 transition-colors" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2 7h12v5a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/>
                <path d="M9.5 2A1.5 1.5 0 0 1 11 3.5V5h1c.506 0 .967.19 1.32.5H2.68C3.034 5.19 3.495 5 4 5h1V3.5A1.5 1.5 0 0 1 6.5 2z"/>
            </svg>
            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center absolute top-6 left-6 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 7h12v5a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2z"/>
                    <path d="M9.5 2A1.5 1.5 0 0 1 11 3.5V5h1c.506 0 .967.19 1.32.5H2.68C3.034 5.19 3.495 5 4 5h1V3.5A1.5 1.5 0 0 1 6.5 2z"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Submissions</h3>
                <div class="mt-2 flex items-center gap-3 justify-end">
                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full font-medium">Active</span>
                    <p class="text-4xl font-bold text-slate-900">{{ $submissionsCount ?? '--' }}</p>
                </div>
            </div>
        </div>

        <!-- Evaluations Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-48 group">
            <svg class="absolute -right-6 -bottom-6 w-32 h-32 text-sky-50 group-hover:text-sky-100 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
            </svg>
            <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center absolute top-6 left-6 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-sky-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                    <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Evaluations</h3>
                <div class="mt-2 flex items-center gap-3 justify-end">
                    <p class="text-4xl font-bold text-slate-900">{{ $evaluationsCount ?? '--' }}</p>
                </div>
            </div>
        </div>

        <!-- Total Courses Card -->
        <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-6 relative overflow-hidden w-full h-48 group">
            <svg class="absolute -right-6 -bottom-6 w-32 h-32 text-green-50 group-hover:text-green-100 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
            </svg>
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center absolute top-6 left-6 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M2 6a2 2 0 0 1 2-2h6c.768 0 2 1 2 2l1 6.5-1 6.736A3 3 0 0 1 10 20H4a2 2 0 0 1-2-2z"/>
                    <path d="M22 6a2 2 0 0 0-2-2h-6c-.768 0-2 1-2 2l-1 6.5 1 6.736c.53.475 1.232.764 2 .764h6a2 2 0 0 0 2-2z"/>
                </svg>
            </div>
            <div class="flex flex-col items-end justify-end h-full text-right relative z-10">
                <h3 class="text-sm font-medium text-slate-500 uppercase tracking-wide">Total Courses</h3>
                <div class="mt-2 flex items-center gap-3 justify-end">
                    <p class="text-4xl font-bold text-slate-900">{{ $coursesCount ?? '--' }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Courses Section (Main Content) -->
    <section class="mt-10 bg-white rounded-2xl shadow-md border border-gray-100">
        <div class="flex items-center justify-between px-8 py-6 border-b border-gray-100">
            <h2 class="text-xl md:text-2xl font-bold text-gray-800">
                @isset($instructor) {{ $instructor->profile->first_name }}'s Courses @else No Instructor Found @endisset
            </h2>
            <a href="{{ route('implementor.courses.create') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                + Create New Course
            </a>
        </div>

        <div class="p-8 grid grid-cols-1 xl:grid-cols-2 gap-8">
            @forelse($courses as $course)
                <article class="relative flex flex-col md:flex-row bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition group">
                    <!-- Image -->
                    <div class="w-full md:w-[320px] h-[200px] flex-shrink-0 overflow-hidden">
                        <img src="{{ $course->activeCoverPhoto ? asset('storage/'.$course->activeCoverPhoto->path) : '/img/default-cover.png' }}"
                             alt="Course cover"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-lg font-bold text-gray-900 line-clamp-1">{{ $course->first_name ?? 'Untitled Course' }}</h3>
                                <span class="text-xs font-semibold px-2 py-1 bg-gray-100 text-gray-600 rounded">
                                    {{ $course->category->category_name ?? 'General' }}
                                </span>
                            </div>
                            
                            <div class="mt-3 space-y-1">
                                <p class="text-sm text-gray-600 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $instructor->first_name ?? 'Instructor' }}
                                </p>
                                <p class="text-sm text-gray-600 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $course->start_date ?? 'TBA' }} &mdash; {{ $course->end_date ?? 'TBA' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <span class="text-xs text-gray-400 font-medium">Status: Active</span>
                            <a href="{{ route('implementor.course-information', $course->course_code) }}"
                               class="inline-flex items-center gap-1 h-9 px-5 rounded-full bg-slate-900 text-white text-sm font-medium hover:bg-slate-800 transition shadow-sm">
                                View Details
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-1 xl:col-span-2 py-12 flex flex-col items-center justify-center text-center border-2 border-dashed border-gray-200 rounded-xl">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No courses yet</h3>
                    <p class="text-gray-500 max-w-sm mt-1 mb-6">Get started by creating your first course to begin interacting with students.</p>
                    <a href="{{ route('implementor.courses.create') }}" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Create Course</a>
                </div>
            @endforelse
        </div>
    </section>
</div> 