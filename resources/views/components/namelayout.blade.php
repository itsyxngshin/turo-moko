@php
    $user = Auth::user();
    $profile = $user->profile;
    // Fallback initials
    $initials = strtoupper(substr($profile->first_name ?? 'U', 0, 1) . substr($profile->last_name ?? '', 0, 1));
@endphp

<nav class="flex items-center justify-between w-full px-6 py-2">
    
    {{-- LEFT: User Profile (Name & Role) --}}
    <div class="flex items-center gap-4 transition-all hover:bg-orange-50/50 rounded-xl p-2 cursor-default">
        <!-- Profile Picture with Orange Gradient Ring -->
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
            <!-- Online Status Dot -->
            <span class="absolute bottom-0.5 right-0.5 h-3 w-3 bg-green-500 border-2 border-white rounded-full"></span>
        </div>

        <!-- Text Content -->
        <div class="flex flex-col justify-center">
            <h1 class="text-lg font-bold text-gray-800 leading-tight">
                Hello, <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-600">{{ $profile->first_name ?? 'User' }}</span>!
            </h1>
            <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                {{ $user->role->role_name ?? 'Dashboard' }}
            </span>
        </div>
    </div>

    {{-- RIGHT: Date/Time & Actions --}}
    <div class="flex items-center gap-4">
        
        {{-- Date & Time Widget --}}
        <div x-data="clock()" 
             x-init="init()"
             class="hidden md:flex items-center gap-3 bg-white px-5 py-2.5 rounded-full shadow-sm border border-gray-100 select-none transition-all hover:shadow-md hover:border-orange-100">
            
            <div class="p-2 bg-orange-50 rounded-full text-orange-500">
                <i data-lucide="calendar-clock" class="w-5 h-5"></i>
            </div>

            <div class="flex flex-col">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="dateStr">Loading...</span>
                <span class="text-sm font-bold text-gray-800 leading-none font-mono" x-text="timeStr">--:-- --</span>
            </div>
        </div>

        {{-- Notification Bell --}}
        <livewire:partials.navbar-notifications />
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