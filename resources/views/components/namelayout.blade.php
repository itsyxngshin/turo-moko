@php
    $user = Auth::user();
    $profile = $user->profile;
    // Fallback initials
    $initials = strtoupper(substr($profile->first_name ?? 'U', 0, 1) . substr($profile->last_name ?? '', 0, 1));
@endphp

<nav class="flex items-center justify-between w-full px-6 py-2">

    {{-- LEFT: User Profile --}}
    <div class="flex items-center gap-4 transition-all hover:bg-orange-50/50 rounded-xl p-2 cursor-default">
        <div class="relative">
            <div class="h-11 w-11 rounded-full p-[2px] bg-gradient-to-tr from-orange-400 to-yellow-300 shadow-sm">
                @if($profile && $profile->photo)
                    <img src="{{ asset('storage/' . $profile->photo->photos) }}" 
                         alt="{{ $profile->first_name }}" 
                         class="h-full w-full rounded-full object-cover border-2 border-white bg-white">
                @else
                    <div class="h-full w-full rounded-full bg-white border-2 border-white flex items-center justify-center text-orange-500 font-bold text-sm">
                        {{ $initials }}
                    </div>
                @endif
            </div>
            <span class="absolute bottom-0.5 right-0.5 h-3 w-3 bg-green-500 border-2 border-white rounded-full"></span>
        </div>

        <div class="flex flex-col justify-center">
            <h1 class="text-lg font-bold text-gray-800 leading-tight">
                Hello, <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-600">{{ $profile->first_name ?? 'User' }}</span>!
            </h1>
            <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                {{ $user->role->role_name ?? 'Dashboard' }}
            </span>
        </div>
    </div>

    {{-- MIDDLE: Search & Sort --}}
    <div class="flex items-center gap-4 flex-shrink-0">
        <div class="relative w-72">
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search tags, orgs, categories..." 
                class="pl-4 pr-10 py-2 rounded-full border border-gray-200 text-sm w-full focus:outline-none focus:border-gray-400 focus:ring-0 transition placeholder-gray-400 shadow-sm"
            >
            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        <div class="relative w-40">
            <select 
                wire:model.live="sort" 
                class="appearance-none bg-white pl-4 pr-10 py-2 rounded-full border border-gray-200 text-sm w-full focus:outline-none focus:border-gray-400 shadow-sm cursor-pointer"
            >
                <option value="latest">Newest First</option>
                <option value="oldest">Oldest First</option>
                <option value="a-z">Title (A-Z)</option>
                <option value="z-a">Title (Z-A)</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- RIGHT: Date/Time, Notifications, Profile --}}
    <div class="flex items-center gap-3 flex-shrink-0 justify-start pr-7">

    <!-- Clock -->
    <div x-data="clock()" 
         x-init="init()" 
         class="hidden md:flex items-center gap-2 bg-white px-3 py-1.5 rounded-full shadow-sm border border-gray-100 select-none transition-all hover:shadow-md hover:border-orange-100">
        
        <div class="p-1.5 bg-orange-50 rounded-full text-orange-500">
            <i data-lucide="calendar-clock" class="w-4 h-4"></i>
        </div>

        <div class="flex flex-col -mt-0.5">
            <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest" x-text="dateStr">Loading...</span>
            <span class="text-[12px] font-bold text-gray-800 leading-none font-mono" x-text="timeStr">--:-- --</span>
        </div>
    </div>

    <!-- Notifications -->
    <livewire:partials.nav-notif />

    <!-- Profile -->
    <a href="{{ route('learner.profile') }}">
        <button class="h-10 w-10 rounded-full bg-white flex items-center justify-center shadow hover:bg-gray-100" aria-label="Profile">
            <i data-lucide="user" class="w-5 h-5 text-gray-600"></i>
        </button>
    </a>

</div>

</nav>


<script>
    function clock() {
        return {
            dateStr: '',
            timeStr: '',
            init() {
                this.update();
                setInterval(() => this.update(), 1000);
            },
            update() {
                const now = new Date();
                this.dateStr = now.toLocaleDateString('en-US', { 
                    weekday: 'short', 
                    month: 'short', 
                    day: 'numeric' 
                }).toUpperCase();
                
                this.timeStr = now.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit' 
                });
            }
        }
    }
</script>