@php
    $user = Auth::user();
    $profile = $user->profile;
    $role = $user->role->role_name ?? null;
    $initials = strtoupper(substr($profile->first_name ?? 'U', 0, 1) . substr($profile->last_name ?? '', 0, 1));
@endphp

<nav class="flex flex-col md:flex-row md:items-center justify-between w-full px-4 py-4 md:py-2 gap-2 pt-14 md:pt-2">

    {{-- TOP ROW: Profile + Greeting (left) & Notifications (right) --}}
    <div class="flex items-center justify-between w-full">

        {{-- LEFT SIDE --}}
        <div class="flex items-center gap-3 transition-all rounded-xl p-2 cursor-default flex-shrink-0">
            @if($role === 'learner')
                <a href="{{ route('learner.profile') }}">   
                    <div class="h-10 w-10 rounded-full p-[2px] bg-white flex items-center justify-center shadow hover:bg-gray-100 flex-shrink-0">
                        @if ($profile && $profile->photo)
                            <img src="{{ asset('storage/' . $profile->photo->photos) }}"
                                alt="{{ $profile->first_name }}"
                                class="h-full w-full rounded-full object-cover border-2 border-white">
                        @else
                            <div class="h-full w-full rounded-full bg-orange-100 flex items-center justify-center text-orange-500 font-bold text-sm border-2 border-white">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>
                </a>
            {{-- IMPLEMENTOR LINKS --}}
            @elseif($role === 'implementor' || $role === 'implementer')
                <a href="{{ route('implementor.profile') }}">
                    <div class="h-10 w-10 rounded-full p-[2px] bg-white flex items-center justify-center shadow hover:bg-gray-100 flex-shrink-0">
                        @if ($profile && $profile->photo)
                            <img src="{{ asset('storage/' . $profile->photo->photos) }}"
                                alt="{{ $profile->first_name }}"
                                class="h-full w-full rounded-full object-cover border-2 border-white">
                        @else
                            <div class="h-full w-full rounded-full bg-orange-100 flex items-center justify-center text-orange-500 font-bold text-sm border-2 border-white">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>
                </a>  
            @endif

            {{-- Greeting --}}  
            <div class="flex flex-col justify-center leading-tight">
                <h1 class="text-lg font-bold text-gray-800">
                    Hello,
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-orange-600">
                        {{ $profile->first_name ?? 'User' }}
                    </span>!
                </h1>

                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">
                    {{ $user->role->role_name ?? 'Dashboard' }}
                </span>
            </div>
        </div>

        {{-- RIGHT SIDE — Notifications --}}
        <div class="flex items-center gap-3">
            <livewire:partials.nav-notif class="flex-shrink-0" />
        </div>
    </div>

    {{-- MOBILE SEARCH --}}
    <div class="w-full mt-3 md:hidden">
        <div class="relative w-full">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search..."
                class="pl-4 pr-10 py-2 rounded-full border border-gray-200 text-sm w-full
                       focus:outline-none focus:border-gray-400 focus:ring-0 transition placeholder-gray-400">

            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    {{-- DESKTOP SEARCH --}}
    <div class="hidden md:flex items-center gap-4 mt-2 md:mt-0">
        <div class="relative w-72">
            <input
                wire:model.live.debounce.300ms="search"
                type="text"
                placeholder="Search tags, orgs, categories..."
                class="pl-4 pr-10 py-2 rounded-full border border-gray-200 text-sm w-full
                       focus:outline-none focus:border-gray-400 focus:ring-0 transition placeholder-gray-400">

            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
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
