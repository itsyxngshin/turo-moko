<div>
    @section('title', 'Course Moderation')

    <div class="flex-1 px-5">

        <!-- Top Bar -->
        <div class="flex items-center mb-6">
            <a href="{{ route('admin.courses') }}"
               class="mr-4 hover:bg-gray-300 text-gray-800 px-2 py-1 rounded-full inline-flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 512 512">
                    <path fill="none"
                          stroke="currentColor"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="48"
                          d="M244 400L100 256l144-144M120 256h292"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold">Course Moderation Panel</h1>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-3 gap-6 mb-6">

            <!-- Course Overview -->
            <div class="bg-white p-4 rounded-xl shadow col-span-2">
                <h2 class="font-semibold mb-2">Course Overview</h2>

                <div class="grid grid-cols-2 gap-2 text-sm">
                    <p><span class="font-semibold">Course:</span> {{ $course->course_title }}</p>
                    <p><span class="font-semibold">Status:</span> {{ ucfirst($course->status ?? 'Pending Review') }}</p>

                    <p>
                        <span class="font-semibold">Creator:</span>
                        {{ $course->implementer?->profile?->first_name ?? 'N/A' }}
                        {{ $course->implementer?->profile?->middle_name ?? '' }}
                        {{ $course->implementer?->profile?->last_name ?? '' }}
                        ({{ $course->implementer?->username ?? '-' }})
                    </p>

                    <p><span class="font-semibold">Date Created:</span> {{ $course->created_at->format('m/d/Y') }}</p>
                    <p><span class="font-semibold">Last Updated:</span> {{ $course->updated_at->format('m/d/Y') }}</p>
                </div>

                <div class="flex space-x-3 mt-4">
                    <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                        Hide Course
                    </button>
                    <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">
                        Delete Course
                    </button>
                </div>
            </div>

            <!-- Tutor Assigned -->
            <div class="bg-white p-4 rounded-xl shadow">
                <h2 class="font-semibold">Tutor Assigned</h2>
                <p class="mt-2">
                    {{ $course->implementer?->profile?->first_name ?? 'N/A' }}
                    {{ $course->implementer?->profile?->middle_name ?? '' }}
                    {{ $course->implementer?->profile?->last_name ?? '' }}
                    ({{ $course->implementer?->username ?? '-' }})
                </p>
            </div>

            <!-- No. of Enrollees -->
            <div class="bg-white p-4 rounded-xl shadow">
                <h2 class="font-semibold">No. of Enrollees</h2>
                <p class="text-2xl font-bold mt-2">
                    {{ $this->activeEnrolleesCount ?? 0 }}
                </p>
            </div>

            <!-- Course Performance Chart -->
            <a href="{{ route('admin.course.evaluation-stats', $course) }}"
               class="block bg-white p-4 rounded-xl shadow col-span-2
                      hover:shadow-md transition cursor-pointer">
            
                <div class="flex justify-center items-center space-x-8">
            
                    <!-- Chart -->
                    <div class="relative w-24 h-24">
                        <canvas id="coursePerformanceChart" width="100" height="100"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center text-lg font-bold text-gray-900">
                            {{ number_format($courseFeedbackStats['averages']['overall'] ?? 0, 1) }}
                        </div>
                    </div>
            
                    <!-- Legends -->
                    <div class="flex flex-col justify-center text-sm space-y-2">
                        <div class="flex items-center">
                            <span class="w-3 h-3 inline-block mr-2 bg-yellow-400"></span>
                            Course Overall:
                            {{ number_format($courseFeedbackStats['averages']['overall'] ?? 0, 1) }}
                        </div>
                        <div class="flex items-center">
                            <span class="w-3 h-3 inline-block mr-2 bg-blue-500"></span>
                            Implementor Overall:
                            {{ number_format($implementorFeedbackStats['averages']['teaching_effectiveness'] ?? 0, 1) }}
                        </div>
                    </div>
            
                </div>
            </a>

        </div>

        <!-- Modules & Content -->
        <div>
            <h2 class="text-xl font-bold mb-4">Modules & Content</h2>

            @forelse($modules as $module)
                <div class="bg-white rounded-xl shadow mb-4">
                    <div class="flex justify-between items-center px-6 py-3 border-b">

                        <p class="font-semibold">
                            Module {{ $module->module_number }} :
                            {{ $module->module_title }}
                        </p>

                        @php
                            $statusText = [
                                'pending' => ['text' => 'Pending', 'color' => 'text-gray-500'],
                                'approved' => ['text' => 'Approved', 'color' => 'text-green-500'],
                                'revision_required' => ['text' => 'Revision Required', 'color' => 'text-red-500'],
                            ];
                            $status = $module->status ?? 'pending';
                        @endphp

                        <span class="text-sm {{ $statusText[$status]['color'] }}">
                            {{ $statusText[$status]['text'] }}
                        </span>

                        <div class="flex space-x-2">
                            @livewire(
                                'admin.modal.course-moderation-view-module',
                                ['moduleId' => $module->id],
                                key('course-module-'.$module->id)
                            )

                            <button wire:click="approveModule({{ $module->id }})"
                                    class="px-3 py-1 border border-green-500 text-green-600 rounded text-sm hover:bg-green-50">
                                Approve
                            </button>

                            <button wire:click="rejectModule({{ $module->id }})"
                                    class="px-3 py-1 border border-red-500 text-red-600 rounded text-sm hover:bg-red-50">
                                Reject
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500">No modules available for this course.</p>
            @endforelse
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('coursePerformanceChart').getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Course Overall', 'Implementor Overall'],
                datasets: [{
                    data: [
                        {{ $courseFeedbackStats['averages']['overall'] ?? 0 }},
                        {{ $implementorFeedbackStats['averages']['teaching_effectiveness'] ?? 0 }}
                    ],
                    backgroundColor: ['#FBBF24', '#3B82F6'],
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.label}: ${ctx.raw.toFixed(1)}`
                        }
                    }
                }
            }
        });
    </script>
</div>
