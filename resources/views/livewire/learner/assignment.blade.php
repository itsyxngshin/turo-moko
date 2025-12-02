<div class="p-0 ml-[20px]">
  <div class="bg-white rounded-2xl shadow-md p-8">

    <!-- Assignment Header -->
    <h2 class="text-2xl font-bold mb-2">{{ $assignment->title }}</h2>
    <p class="text-sm text-gray-600">
      <strong>Opened:</strong> {{ $assignment->open_date->format('l, d M Y, h:i a') }} <br>
      <strong>Due:</strong> {{ $assignment->due_date->format('l, d M Y, h:i a') }}
    </p>

    <!-- Description -->
    <p class="mt-4 text-gray-700 text-sm leading-relaxed">
      {{ $assignment->description }}
    </p>

    <hr class="my-6">

    <!-- Submission Status -->
    <h3 class="text-lg font-semibold mb-4">Submission status</h3>
    <div class="overflow-x-auto">
      <table class="w-full border rounded-lg text-sm">
        <tbody>
          <tr class="border-b">
            <td class="p-3 font-medium w-1/3">Submission status</td>
            <td class="p-3">{{ $assignment->submission_status ?? 'No attempt' }}</td>
          </tr>
          <tr class="border-b">
            <td class="p-3 font-medium">Grading status</td>
            <td class="p-3">{{ $assignment->grading_status ?? 'Not graded' }}</td>
          </tr>
          <tr>
            <td class="p-3 font-medium">Time remaining</td>
            <td class="p-3 text-green-600">
              {{ $assignment->due_date->diffForHumans() }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Button -->
    <div class="mt-6 flex justify-center items-center">
      <button class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800">
        Add submission
      </button>
    </div>
    
  </div>
</div>
