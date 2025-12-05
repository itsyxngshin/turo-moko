<div>
    <!-- Search -->
    <div class="mb-4 flex justify-between items-center">
    <input 
        type="text"
        wire:model.live="search"
        placeholder="Search courses..."
        class="border rounded-full px-4 py-2 w-1/3 focus:ring-2 focus:ring-blue-400"
    >
    <div class="text-sm text-gray-500">
        @if($courses->total() > 0)
            Showing {{ $courses->firstItem() }}–{{ $courses->lastItem() }} of {{ $courses->total() }}
        @else
            No courses found
        @endif
    </div>
</div>
    


    <!-- Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                <tr>
                    <th class="px-6 py-3">Course Title</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Course Code</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Implementor In-Charge</th>
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($courses as $course)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $course->name }}</td>
                        <td class="px-6 py-4">{{ $course->category->category_name }}</td>
                        <td class="px-6 py-4">{{ $course->course_code }}</td>
                        <td class="px-6 py-4 text-{{ $course->status == 'Active' ? 'green' : ($course->status == 'Pending' ? 'yellow' : 'red') }}-600">
                            {{ ucfirst($course->status) }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $course->implementer->profile->first_name ?? '—' }} {{ $course->implementer->profile->last_name ?? '' }}
                        </td>


                        <td class="px-6 py-4 text-blue-500 flex space-x-4">
 <a href="{{ route('admin.course.view', $course->course_code) }}" 
       class="px-3 py-2 text-blue rounded transition hover:underline">
        View
    </a>        
        @livewire('admin.modal.modify-course', ['courseId' => $course->id], key('modify-course-'.$course->id))
        
        <a href="{{ route('admin.moderation.course', $course->id) }}" 
        class="px-3 py-2 text-blue rounded transition hover:underline">
            Moderate
        </a>
        <button class="hover:underline">Archive</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4 text-gray-500">No courses found.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-3">
            {{ $courses->links() }}
        </div>
    </div>
</div>
