<div class="space-y-6"> <!-- Single root element for Livewire -->

    <!-- Header with Back Button and Title -->
    <div class="flex items-center gap-3 mb-6 mt-8 ml-6">
        <!-- Back Button -->
        <button onclick="history.back()"
                class="flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 p-2 rounded-full shadow-sm hover:shadow-md transition-all duration-200">
            <i data-lucide="arrow-left" class="w-4 h-4 md:w-5 md:h-5"></i>
        </button>

        <!-- Section Title -->
        <h1 class="text-2xl font-bold">Evaluations</h1>
    </div>

    <!-- Evaluations Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Pending Evaluations -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-yellow-600 flex items-center gap-2">
                <i data-lucide="clock" class="w-5 h-5"></i>
                Pending Evaluations
            </h2>

            @if(empty($pendingEvaluations) || count($pendingEvaluations) === 0)
                <p class="text-gray-500 text-center py-10">No pending evaluations 🎉</p>
            @else
                <div class="space-y-4">
                    @foreach($pendingEvaluations as $evaluation)
                        <div class="flex bg-gray-50 rounded-xl shadow-sm overflow-hidden border hover:shadow-md transition">
                            <!-- Icon -->
                            <div class="w-16 flex items-center justify-center bg-yellow-100">
                                <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-4 flex flex-col justify-between">
                                <div>
                                    <p class="text-sm font-semibold">{{ $evaluation->title ?? 'Untitled' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Due: {{ isset($evaluation->due_date) ? \Carbon\Carbon::parse($evaluation->due_date)->format('M d, Y') : 'N/A' }}</p>
                                </div>
                                <div class="mt-2 flex justify-end">
                                    <button class="bg-yellow-600 text-white px-3 py-1 rounded-full text-xs hover:bg-yellow-700">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Completed Evaluations -->
        <div class="bg-white rounded-2xl shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-green-600 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                Completed Evaluations
            </h2>

            @if(empty($completedEvaluations) || count($completedEvaluations) === 0)
                <p class="text-gray-500 text-center py-10">No completed evaluations yet 🎉</p>
            @else
                <div class="space-y-4">
                    @foreach($completedEvaluations as $evaluation)
                        <div class="flex bg-gray-50 rounded-xl shadow-sm overflow-hidden border hover:shadow-md transition">
                            <!-- Icon -->
                            <div class="w-16 flex items-center justify-center bg-green-100">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 p-4 flex flex-col justify-between">
                                <div>
                                    <p class="text-sm font-semibold">{{ $evaluation->title ?? 'Untitled' }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Completed on: {{ isset($evaluation->completed_at) ? \Carbon\Carbon::parse($evaluation->completed_at)->format('M d, Y') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>
