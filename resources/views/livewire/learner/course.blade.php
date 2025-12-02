<div class="max-w-screen-xl mx-auto px-6 py-10">

    <!-- Course Info -->
    <div class="relative z-10 text-white mb-10">
        <h1 class="text-3xl font-bold">{{ $course->name }}</h1>

        <p class="text-sm max-w-2xl mt-2">
            {{ $course->background ?? 'No introduction provided.' }}
        </p>
    </div>

    <!-- Course Body -->
    <div class="bg-white p-8 rounded-2xl shadow-md">

        <h2 class="text-xl font-semibold text-center">Course Introduction</h2>

        <p class="text-sm text-center mt-2 text-gray-600 max-w-2xl mx-auto">
            {{ $course->course_description ?? 'No course description available.' }}
        </p>

        <!-- Announcements -->
        <div class="border rounded-xl p-4 mt-6 text-blue-600 font-medium flex items-center gap-3">
            <i data-lucide="megaphone" class="w-5 h-5"></i>
            <span>Announcements</span>
        </div>

        <!-- Modules Section -->
        <h3 class="text-lg font-semibold mt-10 mb-4">Modules</h3>

        <div class="space-y-4">

            @foreach($modules as $module)
            <div class="border rounded-xl p-4 flex items-center gap-3 hover:bg-gray-50 cursor-pointer"
                 onclick="window.location='{{ route('learner.view-module', $module->id) }}'">

                <i data-lucide="file-text" class="text-red-500 w-5 h-5"></i>

                <span class="flex-1">
                    Module {{ $module->module_number }}: {{ $module->module_title }}
                </span>

                @if($module->isCompletedBy(auth()->user()->id))
                    <i data-lucide="check-circle" class="text-green-500 w-5 h-5"></i>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Activities Section -->
        <h3 class="text-lg font-semibold mt-10 mb-4">Activities</h3>

        <div class="space-y-4">

            @foreach($activities as $activity)
            <div class="border rounded-xl p-4 flex items-center gap-3">
                <i data-lucide="clipboard-list" class="text-yellow-500 w-5 h-5"></i>

                <span class="flex-1">{{ $activity->title }}</span>

                @if($activity->deadline)
                    <span class="text-green-600 text-sm mr-2">
                        Due {{ $activity->deadline->format('M d') }}
                    </span>
                @endif

                @if($activity->isCompletedBy(auth()->user()->id))
                    <i data-lucide="check-circle" class="text-green-500 w-5 h-5"></i>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Evaluations Section -->
        <h3 class="text-lg font-semibold mt-10 mb-4">Evaluations</h3>

        <div class="space-y-4">

            @foreach($evaluations as $evaluation)
            <div class="border rounded-xl p-4 flex items-center gap-3">
                <i data-lucide="check-square" class="text-blue-500 w-5 h-5"></i>

                <span class="flex-1">{{ $evaluation->title }}</span>

                @if($evaluation->deadline)
                    <span class="text-green-600 text-sm">
                        Due {{ $evaluation->deadline->format('M d') }}
                    </span>
                @endif
            </div>
            @endforeach
        </div>

        <!-- Assessments Section -->
        <h3 class="text-lg font-semibold mt-10 mb-4">Assessments</h3>

        <div class="space-y-4">

            @foreach($assessments as $assessment)
            <div class="border rounded-xl p-4 flex items-center gap-3">
                <i data-lucide="clipboard" class="text-pink-500 w-5 h-5"></i>

                <span class="flex-1">{{ $assessment->title }}</span>

                @if($assessment->deadline)
                    <span class="text-green-600 text-sm">
                        Due {{ $assessment->deadline->format('M d') }}
                    </span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
