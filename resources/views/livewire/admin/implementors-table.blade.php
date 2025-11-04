<div>
    <!-- Search bar and Add Implementor -->
    <div class="flex justify-between items-center mb-4">
        <div class="relative">
            <input 
                wire:model.debounce.300ms="search" 
                type="text" 
                placeholder="Search implementors"
                class="rounded-full border-gray-300 pl-4 pr-10 py-2 focus:ring-2 focus:ring-blue-400"
            >
            <x-heroicon-o-magnifying-glass class="absolute right-3 top-2.5 w-5 h-5 text-gray-400"/>
        </div>

        <livewire:admin.modal.add-implementor />
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
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
                            <button 
                                wire:click="$dispatch('view-implementor', { id: {{ $user->id }} })" 
                                class="hover:underline"
                            >
                                View
                            </button>

                            <button 
                                wire:click="$dispatch('edit-implementor', { id: {{ $user->id }} })" 
                                class="hover:underline"
                            >
                                Edit
                            </button>

                            <button 
                                wire:click="$dispatch('deactivate-implementor', { id: {{ $user->id }} })" 
                                class="hover:underline text-red-500"
                            >
                                Deactivate
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No implementors found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 👇 Place modals OUTSIDE the loop -->
    <livewire:admin.modal.view-implementor />
</div>
