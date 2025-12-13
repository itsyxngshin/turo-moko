<div class="space-y-4 px-4 md:px-6">

    <!-- Page Header with Back Button -->
    <div class="flex items-center gap-3 mb-6">
        <!-- Back Button (Icon Only) -->
        <button onclick="history.back()"
                class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium p-2 md:p-2.5 rounded-full shadow-sm hover:shadow-md transition-all duration-200">
            <i data-lucide="arrow-left" class="w-4 h-4 md:w-5 md:h-5"></i>
        </button>

        <h1 class="text-2xl font-bold">Evaluations</h1>
    </div>

    <!-- Evaluations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Pending Evaluations -->
        <section class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-yellow-600 flex items-center gap-2">
                <i data-lucide="clock" class="w-5 h-5"></i>
                Pending Evaluations
            </h2>

            @if($pendingEvaluations->isEmpty())
                <p class="text-gray-500 text-center py-10">No pending evaluations 🎉</p>
            @else
                <div class="space-y-4">
                    @foreach($pendingEvaluations as $evaluation)
                        <div class="bg-white rounded-2xl shadow-md flex flex-col md:flex-row overflow-hidden border hover:shadow-lg transition">
                            
                            <!-- Icon / Thumbnail -->
                            <div class="flex-shrink-0 flex items-center justify-center h-20 md:w-20 md:h-auto bg-yellow-100">
                                <i data-lucide="clipboard-check" class="w-8 h-8 text-yellow-500"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-5 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                                        <p class="text-xs text-gray-400">Course: {{ $evaluation->title }}</p>
                                        <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-0.5 rounded-full">
                                            Pending
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold">Course Evaluation</h3>
                                    <p class="text-xs text-gray-400 mt-1">{{ $evaluation->description }}</p>
                                </div>

                                <!-- Button -->
                                <div class="flex justify-end mt-4">
                                    <a href="{{ route('learner.course.show', ['course' => $evaluation->course_code]) }}"
                                       class="bg-black text-white px-4 py-1.5 rounded-full text-sm hover:bg-gray-800 w-full md:w-auto text-center">
                                        Submit Evaluation
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- Completed Evaluations -->
        <section class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-green-600 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                Completed Evaluations
            </h2>

            @if($completedEvaluations->isEmpty())
                <p class="text-gray-500 text-center py-10">No completed evaluations yet</p>
            @else
                <div class="space-y-4">
                    @foreach($completedEvaluations as $evaluation)
                        <div class="bg-white rounded-2xl shadow-md flex flex-col md:flex-row overflow-hidden border hover:shadow-lg transition">
                            
                            <!-- Icon / Thumbnail -->
                            <div class="flex-shrink-0 flex items-center justify-center h-20 md:w-20 md:h-auto bg-green-100">
                                <i data-lucide="check-circle" class="w-8 h-8 text-green-500"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-5 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-2 flex-wrap gap-2">
                                        <p class="text-xs text-gray-400">Course: {{ $evaluation->title }}</p>
                                        <span class="text-xs bg-green-100 text-green-600 px-2 py-0.5 rounded-full">
                                            Completed
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold">Course Evaluation</h3>
                                    <p class="text-xs text-gray-400 mt-1">Submitted: {{ $evaluation->completed_at ? $evaluation->completed_at->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</div>