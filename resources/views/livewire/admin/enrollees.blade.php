<div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100">
    
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">Enrollees Management</h2>
        
        <div class="relative w-64">
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Search student or course..." 
                   class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-full text-sm focus:outline-none focus:border-blue-500 transition">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold tracking-wide">
                <tr>
                    <th class="px-6 py-3 rounded-tl-lg">Student</th>
                    <th class="px-6 py-3">Course Enrolled</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 rounded-tr-lg text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse ($enrollees as $record)
                    <tr class="hover:bg-gray-50 transition-colors group">

                        <td class="px-6 py-4 align-middle">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200 flex-shrink-0">
                                   @if($record->user && $record->user->profile && $record->user->profile->photo)
                                        <img src="{{ asset('storage/' . $record->user->profile->photo->photos) }}" alt="Profile Photo" class="w-10 h-10 rounded-full">
                                    @else
                                        <span class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                            </svg>
                                        </span>
                                    @endif


                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ $record->user->profile->first_name ?? '—' }} 
                                        {{ $record->user->profile->last_name ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $record->user->email ?? '--' }}</p>
                                </div>

                            </div>
                        </td>

                        <td class="px-6 py-4 text-sm font-medium text-gray-700">
                            {{ $record->course->course_title ?? 'Unknown Course' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                            {{ $record->enrollment_date ? \Carbon\Carbon::parse($record->enrollment_date)->format('M d, Y') : '—' }}
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'Active' => 'bg-green-100 text-green-700',
                                    'Completed' => 'bg-blue-100 text-blue-700',
                                    'Dropped' => 'bg-red-100 text-red-700',
                                ];
                                $color = $statusColors[$record->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }}">
                                {{ $record->status }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <button wire:click="remove({{ $record->id }})" 
                                    onclick="confirm('Are you sure you want to drop this student from the course?') || event.stopImmediatePropagation()"
                                    class="text-red-500 hover:text-red-700 font-medium text-xs uppercase tracking-wide opacity-0 group-hover:opacity-100 transition-opacity">
                                Drop
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                <span>No enrollment records found.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 px-2">
        {{ $enrollees->links() }}
    </div>
</div>