<div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100">
    
    {{-- HEADER & SEARCH --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Courses Directory</h2>
            <p class="text-sm text-gray-500">Manage all available learning tracks</p>
        </div>
        
        <div class="relative w-full md:w-72">
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search title, code, or category..." 
                class="pl-10 pr-4 py-2 w-full border border-gray-200 rounded-full text-sm focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition shadow-sm"
            >
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm rounded flex items-center justify-between">
            <span>{{ session('message') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 hover:text-green-900">&times;</button>
        </div>
    @endif

    {{-- TABLE --}}
    <div class="overflow-x-auto rounded-lg border border-gray-100">
        <table class="min-w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold tracking-wide">
                <tr>
                    <th class="px-6 py-4 rounded-tl-lg">Course Title</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Course Code</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Implementor</th>
                    <th class="px-6 py-4 rounded-tr-lg text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 bg-white">
                @forelse ($courses as $course)
                    <tr class="hover:bg-gray-50 transition-colors">
                        
                        {{-- TITLE --}}
                        <td class="px-6 py-4">
                            <span class="font-bold text-gray-800 block">{{ $course->course_title }}</span>
                        </td>

                        {{-- CATEGORY --}}
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-md border border-gray-200">
                                {{ $course->category->category_name ?? 'Uncategorized' }}
                            </span>
                        </td>

                        {{-- CODE --}}
                        <td class="px-6 py-4 text-sm font-mono text-gray-500">
                            {{ $course->course_code }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'active'   => 'bg-green-100 text-green-700 border-green-200',
                                    'archived' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'deleted'  => 'bg-red-100 text-red-700 border-red-200',
                                    'closed'   => 'bg-gray-100 text-gray-600 border-gray-200',
                                ];
                                $statusKey = strtolower($course->status);
                                $colorClass = $statusColors[$statusKey] ?? 'bg-gray-50 text-gray-500 border-gray-200';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>

                        {{-- IMPLEMENTOR --}}
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 text-xs font-bold">
                                    {{ substr($course->implementer->profile->first_name ?? 'U', 0, 1) }}
                                </div>
                                <span>
                                    {{ $course->implementer->profile->first_name ?? '—' }} 
                                    {{ $course->implementer->profile->last_name ?? '' }}
                                </span>
                            </div>
                        </td>

                        {{-- ACTIONS --}}
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-3">

                                @if($course->status === 'deleted')
                                    <span class="text-xs text-red-500 font-medium italic mr-2">Deleted</span>
                                    
                                    {{-- Restore Trigger --}}
                                    <button wire:click="confirmAction('restore', {{ $course->id }})"
                                            class="text-green-500 hover:text-green-700 transition"
                                            title="Restore Course">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                    </button>
                                @else
                                    {{-- View --}}
                                    <a href="{{ route('admin.view-course', $course->course_code) }}" 
                                    class="text-gray-500 hover:text-blue-600 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>

                                    {{-- Edit --}}
                                    <span>
                                        @livewire('admin.modal.modify-course', ['courseId' => $course->id], key('modify-course-'.$course->id))
                                    </span>

                                    {{-- Moderate --}}
                                    <a href="{{ route('admin.course-moderation', $course->id) }}" class="text-gray-500 hover:text-orange-500 transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                    </a>

                                    {{-- Archive/Unarchive Trigger --}}
                                    @if($course->status === 'archived')
                                        <button wire:click="confirmAction('unarchive', {{ $course->id }})"
                                                class="text-green-500 hover:text-green-700 transition">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                                        </button>
                                    @else
                                        <button wire:click="confirmAction('archive', {{ $course->id }})"
                                                class="text-gray-500 hover:text-red-400 transition">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                                        </button>
                                    @endif

                                    {{-- Delete Trigger --}}
                                    <button wire:click="confirmAction('delete', {{ $course->id }})"
                                            class="text-gray-400 hover:text-red-600 transition">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 bg-gray-50 bg-opacity-50">
                            <div class="flex flex-col items-center gap-3">
                                <div class="p-3 bg-white rounded-full shadow-sm">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                </div>
                                <span class="font-medium">No courses found matching criteria.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-4 px-2">
        {{ $courses->links() }}
    </div>
    @if($confirmingId)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                {{-- Backdrop --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                    wire:click="cancelAction"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            
                            {{-- Dynamic Icon --}}
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full {{ in_array($actionType, ['delete', 'archive']) ? 'bg-red-100' : 'bg-green-100' }} sm:mx-0 sm:h-10 sm:w-10">
                                @if(in_array($actionType, ['delete', 'archive']))
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>

                            {{-- Dynamic Text --}}
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 capitalize" id="modal-title">
                                    {{ $actionType }} Course?
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        @if($actionType === 'archive')
                                            Are you sure you want to archive this course? It will be hidden from students but can be restored later.
                                        @elseif($actionType === 'delete')
                                            Are you sure you want to move this course to trash? You can still restore it from the "Deleted" status.
                                        @elseif($actionType === 'restore')
                                            Do you want to restore this course? It will become visible again.
                                        @elseif($actionType === 'unarchive')
                                            Do you want to make this course active and visible again?
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Buttons --}}
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" 
                                wire:click="executeAction"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm
                                {{ in_array($actionType, ['delete', 'archive']) ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500' }}">
                            Yes, {{ ucfirst($actionType) }}
                        </button>
                        <button type="button" 
                                wire:click="cancelAction"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
</div>
