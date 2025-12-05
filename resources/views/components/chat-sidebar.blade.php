<aside class="h-[750px] w-[70px] bg-white rounded-3xl border border-gray-200 shadow-sm flex flex-col items-center py-6 ml-4 mt-2">
    
    {{-- Get the current role for cleaner logic below --}}
    @php
        $role = auth()->user()->role->role_name ?? 'learner'; 
    @endphp

    <div class="flex flex-col items-center gap-8">
        {{-- ========================================== --}}
        {{-- ADMIN LINKS                                --}}
        {{-- ========================================== --}}
        @if($role === 'admin')
            <a href="{{ route('admin.hub') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('admin.hub') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Dashboard">
                <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
            </a>

            <a href="{{ route('admin.enrollees') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Learner Roster Management">
                <i data-lucide="users" class="w-6 h-6"></i>
            </a>

            <a href="{{ route('admin.implementors') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Implementors">
                <i data-lucide="bell-electric" class="w-6 h-6"></i>
            </a>

            <a href="{{ route('admin.courses') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Courses">
                <i data-lucide="notebook-tabs" class="w-6 h-6"></i>
            </a>

            <a href="{{ route('admin.course-moderation') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('admin.users') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Learner Roster Management">
                <i data-lucide="bookmark-checked" class="w-6 h-6"></i>
            </a>

            <a href="{{ route('admin.settings') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Settings">
                <i data-lucide="settings" class="w-6 h-6"></i>
            </a>

        {{-- ========================================== --}}
        {{-- IMPLEMENTOR LINKS                          --}}
        {{-- ========================================== --}}
        @elseif($role === 'implementor' || $role === 'implementer') {{-- Handle both spellings just in case --}}
            <a href="{{ route('implementor.dashboard') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('implementer.hub') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Home">
                <i data-lucide="home" class="w-6 h-6"></i>
            </a>

            <a href="{{ route('implementor.all-courses') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('implementer.courses') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="My Classes">
                <i data-lucide="presentation" class="w-6 h-6"></i>
            </a>

            <a href="#" class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600" aria-label="Chat">
                <i data-lucide="message-circle" class="w-6 h-6"></i>        
            </a>

            <a href="{{ route('implementor.settings') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('implementer.settings') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Settings">
                <i data-lucide="settings" class="w-6 h-6"></i>
            </a>

        {{-- ========================================== --}}
        {{-- LEARNER LINKS (Default)                    --}}
        {{-- ========================================== --}}
        @else
            <a href="{{ route('learner.hub') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('learner.hub') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Home">
                <i data-lucide="home" class="w-6 h-6"></i>
            </a>

            <a href="{{ route('learner.classes') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('learner.classes') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Courses">
                <i data-lucide="book" class="w-6 h-6"></i>
            </a>

            <a href="#" class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600" aria-label="Chat">
                <i data-lucide="message-circle" class="w-6 h-6"></i>        
            </a>

            <a href="{{ route('learner.settings') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('learner.settings') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Settings">
                <i data-lucide="settings" class="w-6 h-6"></i>
            </a>
        @endif
        <a href="{{ route('auth.chat') }}" 
               class="h-10 w-10 flex items-center justify-center rounded-lg transition-colors {{ request()->routeIs('auth.chats') ? 'bg-orange-100 text-orange-600' : 'hover:bg-gray-100 text-gray-600' }}" 
               aria-label="Chat">
                <i data-lucide="message-square-dot" class="w-6 h-6"></i>
        </a>

    </div>

    <div class="mt-auto">
        {{-- IMPORTANT: Logout must be a POST form, not just a link/button, for security --}}
        <form method="POST" action="{{ route('auth.logout') }}">
            @csrf
            <button type="submit" 
                    class="h-10 w-10 flex items-center justify-center rounded-lg hover:bg-red-50 hover:text-red-600 text-gray-600 transition-colors" 
                    aria-label="Logout"
                    title="Logout">
                <i data-lucide="log-out" class="w-6 h-6"></i>
            </button>
        </form>
    </div>
</aside>