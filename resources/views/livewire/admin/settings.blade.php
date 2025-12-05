<div class="flex flex-col lg:flex-row gap-6 p-6 min-h-screen bg-gray-50">

    <!-- LEFT: Settings Navigation Sidebar -->
    <div class="w-full lg:w-64 flex-shrink-0">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-bold text-gray-800">System Settings</h2>
            </div>
            
            <nav class="flex flex-col p-2 space-y-1">
                <button 
                    wire:click="$set('activeTab', 'general')"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ $activeTab === 'general' ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i data-lucide="globe" class="w-5 h-5"></i>
                    General & Branding
                </button>

                <button 
                    wire:click="$set('activeTab', 'users')"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ $activeTab === 'users' ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i data-lucide="users" class="w-5 h-5"></i>
                    Users & Roles
                </button>

                <button 
                    wire:click="$set('activeTab', 'courses')"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ $activeTab === 'courses' ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    Course Configuration
                </button>

                <button 
                    wire:click="$set('activeTab', 'security')"
                    class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-colors {{ $activeTab === 'security' ? 'bg-orange-50 text-orange-600' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i data-lucide="shield-alert" class="w-5 h-5"></i>
                    Security & Logs
                </button>
            </nav>
        </div>
    </div>

    <!-- RIGHT: Content Area -->
    <div class="flex-1">
        
        <!-- SUCCESS MESSAGE -->
        @if (session()->has('message'))
            <div class="mb-4 p-4 rounded-xl bg-green-50 text-green-700 border border-green-200 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('message') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <!-- TAB 1: GENERAL -->
            @if($activeTab === 'general')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">General Settings</h3>
                        <p class="text-sm text-gray-500">Manage site identity and basic configuration.</p>
                    </div>
                    <hr>

                    <form wire:submit="saveGeneral">
                        <div class="grid gap-6">
                            <!-- Site Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Application Name</label>
                                <input type="text" wire:model="siteName" class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500">
                            </div>

                            <!-- Maintenance Mode Toggle -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div>
                                    <span class="block font-medium text-gray-800">Maintenance Mode</span>
                                    <span class="text-sm text-gray-500">Prevent users from accessing the site during updates.</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="maintenanceMode" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">Save Changes</button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- TAB 2: USERS -->
            @if($activeTab === 'users')
                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">User Configuration</h3>
                        <p class="text-sm text-gray-500">Control how users register and interact.</p>
                    </div>
                    <hr>
                    
                    <form wire:submit="saveUsers">
                        <div class="grid gap-6">
                            <!-- Registration Toggle -->
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-700">Allow New Registrations</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="allowRegistration" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 peer-checked:bg-green-500"></div>
                                </label>
                            </div>

                            <!-- Implementor Verification -->
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="block font-medium text-gray-700">Require Approval for Implementors</span>
                                    <span class="text-xs text-gray-500">New implementors cannot create courses until verified by Admin.</span>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="verifyImplementors" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 peer-checked:bg-orange-500"></div>
                                </label>
                            </div>
                        </div>
                         <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600 transition">Save Changes</button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- TAB 3: COURSES (Categories) -->
            @if($activeTab === 'courses')
                 <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Course Categories</h3>
                        <p class="text-sm text-gray-500">Define topics for courses.</p>
                    </div>
                    <hr>

                    <!-- Add New Category Form -->
                    <form wire:submit.prevent="addCategory" class="flex gap-3 items-start">
                        <div class="flex-1">
                            <input 
                                type="text" 
                                wire:model="newCategoryName" 
                                placeholder="Enter new category name (e.g. Photography)" 
                                class="w-full rounded-lg border-gray-300 focus:ring-orange-500 focus:border-orange-500 text-sm"
                            >
                            @error('newCategoryName') 
                                <span class="text-xs text-red-500 ml-1">{{ $message }}</span> 
                            @enderror
                        </div>
                        <button 
                            type="submit" 
                            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                            <span>+ Add</span>
                            <!-- Loading Spinner -->
                            <div wire:loading wire:target="addCategory">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </div>
                        </button>
                    </form>

                    <!-- Dynamic List of Categories -->
                    <div class="space-y-2 max-h-[400px] overflow-y-auto pr-2">
                        @forelse($categories as $cat)
                            <div wire:key="cat-{{ $cat->id }}" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100 group hover:border-orange-200 transition-colors">
                                <span class="font-medium text-gray-700">{{ $cat->category_name }}</span>
                                
                                <button 
                                    wire:click="deleteCategory({{ $cat->id }})"
                                    wire:confirm="Are you sure you want to delete the category '{{ $cat->category_name }}'?"
                                    class="text-gray-400 hover:text-red-500 p-1 rounded transition-colors"
                                    title="Delete Category">
                                    <!-- Inline SVG (No JavaScript needed) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 6h18"></path>
                                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path>
                                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        @empty
                            <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                <p class="text-sm text-gray-500">No categories found.</p>
                                <p class="text-xs text-gray-400">Add one above to get started.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            <!-- TAB 4: SECURITY & LOGS -->
            <style>
                /* Hide scrollbar for Chrome, Safari and Opera */
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
                /* Hide scrollbar for IE, Edge and Firefox */
                .no-scrollbar {
                    -ms-overflow-style: none;  /* IE and Edge */
                    scrollbar-width: none;  /* Firefox */
                }
            </style>

            <!-- TAB 4: SECURITY & LOGS -->
            @if($activeTab === 'security')
                <div class="space-y-6">
                    <div class="flex justify-between items-end">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Activity Logs</h3>
                            <p class="text-sm text-gray-500">Monitor system activity and security events.</p>
                        </div>
                        <button wire:click="$refresh" class="text-sm text-orange-500 hover:text-orange-600 flex items-center gap-1">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i> Refresh
                        </button>
                    </div>
                    <hr>

                    <!-- Logs Table Wrapper -->
                    <!-- Added: max-h-[600px], overflow-y-auto, no-scrollbar -->
                    <div class="overflow-x-auto overflow-y-auto max-h-[600px] rounded-xl border border-gray-200 no-scrollbar relative">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0 z-10 shadow-sm"> <!-- Added sticky top-0 -->
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Action</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">IP Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-50">Time</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($logs as $log)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($log->user)
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-orange-100 flex items-center justify-center text-xs font-bold text-orange-600">
                                                        {{ substr($log->user->username, 0, 2) }}
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">{{ $log->user->username }}</p>
                                                        <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    System / Guest
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-xs font-mono text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                                {{ $log->action }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm text-gray-700">{{ $log->description }}</p>
                                            @if(!empty($log->properties))
                                                <details class="mt-1">
                                                    <summary class="text-xs text-blue-500 cursor-pointer hover:underline">View Details</summary>
                                                    <pre class="text-[10px] bg-gray-800 text-green-400 p-2 rounded mt-1 overflow-auto max-w-xs">{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</pre>
                                                </details>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->ip_address }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->created_at->format('M d, H:i A') }}
                                            <span class="block text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            No activity logs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>