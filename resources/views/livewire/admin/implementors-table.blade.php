<div 
    x-data 
    x-on:print-table.window="window.print()"
>
    <!-- Search bar and Actions -->
    <div class="flex justify-between items-center mb-4">
        <div class="relative">
            <input 
                wire:model.live="search"
                type="text" 
                placeholder="Search implementors"
                class="rounded-full border-gray-300 pl-4 pr-10 py-2 focus:ring-2 focus:ring-blue-400"
            />
            <x-heroicon-o-magnifying-glass class="absolute left-[32%] top-2.5 w-5 h-5 text-gray-400"/>

            <!-- Sort Dropdowns -->
            <select wire:model.live="sortField" class="border-gray-300 rounded-full px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                <option value="username">Sort by Name (A–Z)</option>
                <option value="created_at">Sort by Date Created</option>
            </select>

            <select wire:model.live="sortDirection" class="border-gray-300 rounded-full px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                <option value="asc">Ascending (Oldest / A–Z)</option>
                <option value="desc">Descending (Newest / Z–A)</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button 
                wire:click="downloadCSV"
                class="bg-black hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                Download CSV
            </button>

            <button 
                wire:click="printTable"
                class="bg-black hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M7 17h10v5H7zm12 3v-5H5v5H3a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1zM5 10v2h3v-2zm2-8h10a1 1 0 0 1 1 1v3H6V3a1 1 0 0 1 1-1"/></svg>
            </button>

            <!-- FIX: Use @click for reliable client-side dispatch -->
            <button 
                type="button"
                @click="$dispatch('open-add-implementor')" 
                class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 transition-colors">
                <span class="text-xl leading-none">+</span> Add Implementor
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div id="printableTable">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-sm">
                    <tr>
                        <th class="px-6 py-3">Id</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Username</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($implementors as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                {{ $user->profile->first_name ?? '' }}
                                {{ $user->profile->middle_name ?? '-' }}
                                {{ $user->profile->last_name ?? '' }}
                            </td>
                            <td class="px-6 py-4">{{ $user->username }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->phonenum ?? '—' }}</td>
                            <td class="px-6 py-4">{{ $user->profile?->status ?? 'Active' }}</td>
                            <td class="pl-6 pr-10 py-4 text-blue-500 flex space-x-4 justify-between">
                                <!-- FIX: Use type="button" and @click -->
                                <button 
                                    type="button"
                                    @click="$dispatch('view-implementor', { id: {{ $user->id }} })" 
                                    class="hover:underline"
                                >
                                    View
                                </button>
                                
                                <button 
                                    type="button"
                                    @click="$dispatch('modify-implementor', { id: {{ $user->id }} })" 
                                    class="hover:underline"
                                >
                                    Edit
                                </button>
                                
                                <button 
                                    type="button"
                                    @click="$dispatch('deactivate-implementor', { id: {{ $user->id }} })" 
                                    class="hover:underline text-red-500"
                                >
                                    Deactivate
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No implementors found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $implementors->links() }}
    </div>

    <!-- Modals are loaded here, but hidden by default via their internal logic -->
    <livewire:admin.modal.add-implementor /> 
    <livewire:admin.modal.view-implementor />
    <livewire:admin.modal.modify-implementor />

</div>