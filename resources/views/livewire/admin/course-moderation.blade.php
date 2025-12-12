@section('title', 'Course Moderation')

<div class="flex-1 p-8">
    <!-- Top Bar -->
    <div class="flex justify-between items-center mb-6">
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
                <p><span class="font-semibold">Creator:</span>   {{ $course->implementer?->profile?->first_name ?? 'N/A' }} {{ $course->implementer?->profile?->middle_name ?? '' }} {{ $course->implementer?->profile?->last_name ?? '' }} ({{ $course->implementer?->username ?? '-'}})</p>
                <p><span class="font-semibold">Date Created:</span> {{ $course->created_at->format('m/d/Y') }}</p>
                <p><span class="font-semibold">Last Updated:</span> {{ $course->updated_at->format('m/d/Y') }}</p>
            </div>
            <div class="flex space-x-3 mt-4">
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Hide Course</button> 
                <button class="px-4 py-2 border rounded-lg hover:bg-gray-100">Delete Course</button>
            </div>
        </div>

        <!-- Tutor Assigned -->
        <div class="bg-white p-4 rounded-xl shadow">
            <h2 class="font-semibold">Tutor Assigned</h2>
            <p class="mt-2">{{ $course->implementer?->profile?->first_name ?? 'N/A' }} {{ $course->implementer?->profile?->middle_name ?? '' }} {{ $course->implementer?->profile?->last_name ?? '' }} ({{ $course->implementer?->username ?? '-'}})</p>
        </div>

        <!-- No. of Enrollees -->
<div class="bg-white p-4 rounded-xl shadow">
    <h2 class="font-semibold">No. of Enrollees</h2>
    <p class="text-2xl font-bold mt-2">
        {{ $course->enrollees->count() }}/{{ $course->student_limit }}
    </p>
</div>


        <!-- Course Performance -->
        <div class="bg-white p-4 rounded-xl shadow col-span-2">
            <h2 class="font-semibold">Course Performance</h2>
            <div class="mt-4 flex justify-center">
                <!-- Placeholder performance circle -->
                <div class="w-24 h-24 rounded-full border-8 border-yellow-400 border-t-red-500"></div>
            </div>
        </div>
    </div>

    <!-- Modules & Content -->
    <div>
        <h2 class="text-xl font-bold mb-4">Modules & Content</h2>

        @forelse($modules as $module)
        <div class="bg-white rounded-xl shadow mb-4">
            <div class="flex justify-between items-center px-6 py-3 border-b">
                <p class="font-semibold">Module {{ $module->module_number }} : {{ $module->module_title }}</p>
                @php
                    $statusText = [
                        'pending' => ['text' => 'Pending', 'color' => 'text-gray-500'],
                        'approved' => ['text' => 'Approved', 'color' => 'text-green-500'],
                        'revision_required' => ['text' => 'Revision Required', 'color' => 'text-red-500'],
                    ];

                    $status = $module->status ?? 'pending';
                @endphp

                <span class="text-sm {{ $statusText[$status]['color'] ?? 'text-gray-500' }}">
                    {{ $statusText[$status]['text'] ?? 'Pending' }}
                </span>

                                <div class="flex space-x-2">
                @foreach($modules as $module)
                    @livewire(
                        'admin.modal.course-moderation-view-module',
                        ['moduleId' => $module->id],
                        key('course-module-'.$module->id)
                    )
                @endforeach

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