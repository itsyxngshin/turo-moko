<div 
    x-data 
    x-on:print-table.window="window.print()"
    class="p-6"
>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        
        <div class="relative flex flex-wrap items-center gap-2 w-full md:w-auto">
            
            <div class="relative w-full md:w-64">
                <input 
                    wire:model.live.debounce.300ms="search"
                    type="text" 
                    placeholder="Search implementors..." 
                    class="w-full rounded-full border-gray-300 pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:border-blue-400 shadow-sm transition"
                />
                <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <select wire:model.live="sortField" class="border-gray-300 rounded-full px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 shadow-sm cursor-pointer text-gray-600">
                <option value="username">Name</option>
                <option value="created_at">Date Added</option>
                <option value="email">Email</option>
            </select>

            <select wire:model.live="sortDirection" class="border-gray-300 rounded-full px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 shadow-sm cursor-pointer text-gray-600">
                <option value="asc">Ascending (A-Z)</option>
                <option value="desc">Descending (Z-A)</option>
            </select>
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
            <button 
                @click="window.print()"
                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition-colors shadow-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Print
            </button>

            <button 
                wire:click="downloadCSV"
                class="bg-white border border-gray-300 text-gray-700 hover:bg-green-50 hover:text-green-700 hover:border-green-300 px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition-colors shadow-sm font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                CSV
            </button>

            <button 
                type="button"
                @click="$dispatch('open-add-implementor')" 
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition-colors shadow-sm font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Add Implementor
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div id="printableTable" class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Profile</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4">Contact Info</th>
                        <th class="px-6 py-4">Date Added</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($implementors as $user)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden border border-gray-200 flex-shrink-0">
                                        @if($user->profile && $user->profile->photo)
                                            <img src="{{ asset('storage/' . $user->profile->photo->photos) }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode(($user->profile->first_name ?? 'I') . ' ' . ($user->profile->last_name ?? '')) }}&background=random&color=fff" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">
                                            {{ $user->profile->first_name ?? '' }} {{ $user->profile->last_name ?? '' }}
                                        </div>
                                        <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                {{ $user->username }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-600">{{ $user->email }}</span>
                                    <span class="text-xs text-gray-400">{{ $user->phonenum ?? '—' }}</span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $status = $user->profile?->status ?? 'Active';
                                    $statusColors = [
                                        'Active' => 'bg-green-50 text-green-700 border-green-100',
                                        'Inactive' => 'bg-gray-50 text-gray-700 border-gray-200',
                                        'Suspended' => 'bg-red-50 text-red-700 border-red-100',
                                    ];
                                    $color = $statusColors[$status] ?? 'bg-blue-50 text-blue-700 border-blue-100';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $color }}">
                                    {{ $status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button 
                                        type="button"
                                        @click="$dispatch('view-implementor', { id: {{ $user->id }} })" 
                                        class="text-blue-500 hover:text-blue-700 font-medium text-xs uppercase tracking-wide">
                                        View
                                    </button>
                                    
                                    <span class="text-gray-300">|</span>

                                    <button 
                                        type="button"
                                        @click="$dispatch('modify-implementor', { id: {{ $user->id }} })" 
                                        class="text-green-500 hover:text-green-700 font-medium text-xs uppercase tracking-wide">
                                        Edit
                                    </button>
                                    
                                    <span class="text-gray-300">|</span>

                                    <button 
                                        type="button"
                                        @click="$dispatch('deactivate-implementor', { id: {{ $user->id }} })" 
                                        class="text-red-500 hover:text-red-700 font-medium text-xs uppercase tracking-wide">
                                        Deactivate
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    <span>No implementors found matching your search.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 px-2">
        {{ $implementors->links() }}
    </div>

    <livewire:admin.modal.add-implementor /> 
    <livewire:admin.modal.view-implementor />
    <livewire:admin.modal.modify-implementor />

</div>