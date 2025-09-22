<div class="grid grid-cols-2 gap-6">
    <!-- Pending Evaluations -->
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-4 text-yellow-600">⏳ Pending Evaluations</h2>
        
        @if($pendingEvaluations->isEmpty())
            <p class="text-gray-500">No pending evaluations 🎉</p>
        @else
            <ul class="space-y-2">
                @foreach($pendingEvaluations as $evaluation)
                    <li class="border-b py-2">
                        <span class="font-medium">{{ $evaluation->title ?? 'Untitled' }}</span>
                        <p class="text-sm text-gray-500">Due: {{ $evaluation->due_date ?? 'N/A' }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Completed Evaluations -->
    <div class="bg-white shadow rounded-xl p-6">
        <h2 class="text-lg font-semibold mb-4 text-green-600">✅ Completed Evaluations</h2>
        
        @if($completedEvaluations->isEmpty())
            <p class="text-gray-500">No completed evaluations yet</p>
        @else
            <ul class="space-y-2">
                @foreach($completedEvaluations as $evaluation)
                    <li class="border-b py-2">
                        <span class="font-medium">{{ $evaluation->title ?? 'Untitled' }}</span>
                        <p class="text-sm text-gray-500">Completed on: {{ $evaluation->completed_at ?? 'N/A' }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
