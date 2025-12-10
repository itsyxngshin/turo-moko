<div class="flex h-screen bg-gray-50 font-sans">
    <main class="flex-1 px-5 py-3 overflow-y-auto">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Profile</h1>
        </header>
        <section class="bg-white rounded-3xl p-8 mb-8 shadow-sm border border-gray-100 relative">
    
            <div class="absolute top-6 right-6" x-data="{ open: false }">
                
                <button @click="open = !open" class="p-2 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </button>

                <div x-show="open" 
                    @click.away="open = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-20 overflow-hidden"
                    style="display: none;"> <button wire:click="openEditModal" @click="open = false" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-blue-600 flex items-center gap-2 transition">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        Edit Profile
                    </button>
                    
                    </div>
            </div>

            <div class="flex items-center gap-8">
                <div class="w-32 h-32 rounded-full overflow-hidden bg-blue-100 border-4 border-white shadow-md flex-shrink-0">
                    @if($user->profile->photo_id == null)
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->profile->first_name . ' ' . $user->profile->last_name) }}&background=bfdbfe&color=1e3a8a&size=128" alt="Teacher" class="w-full h-full object-cover">
                    
                    @else
                        <img src="{{ asset('storage/' . $user->profile->photo->photos) }}" class="w-full h-full object-cover">
                    @endif
                </div>
                
                <div class="flex flex-col gap-1">
                    <h2 class="text-3xl font-bold text-gray-800">
                        {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                    </h2>
                    <p class="text-gray-500 font-medium">
                        {{ $user->role->role_name ?? 'Implementor' }}
                    </p>
                    <p class="text-gray-400 text-sm mt-1">{{ $user->username }}</p>
                    <p class="text-gray-400 text-sm mt-1">{{ $user->email }}</p>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10 w-full">
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Work Experience
                </h3>
                
                <div class="space-y-6">
                @if($user->profile->portfolioSets->count() > 0)
                    
                    @foreach($user->profile->portfolioSets as $set)
                        {{-- Only display if the link is valid --}}
                        @if($set->workPortfolio) 
                        <div class="flex gap-4 relative">
                            @if(!$loop->last)
                                <div class="absolute left-[19px] top-8 bottom-[-24px] w-0.5 bg-gray-100"></div>
                            @endif
                            
                            <div class="w-10 h-10 rounded-full bg-{{ $set->workPortfolio->status == 'Active' ? 'green' : 'gray' }}-50 border border-{{ $set->workPortfolio->status == 'Active' ? 'green' : 'gray' }}-100 flex items-center justify-center shrink-0 z-10">
                                <span class="text-xs font-bold text-{{ $set->workPortfolio->status == 'Active' ? 'green' : 'gray' }}-600">
                                    {{ substr($set->workPortfolio->designation, 0, 1) }}
                                    
                                </span>
                            </div>

                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">{{ $set->workPortfolio->designation }}</h4>
                                <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                                    <span>{{ $set->workPortfolio->workplace ?? 'Unspecified' }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                    <span>{{ $set->workPortfolio->duration ?? 'Duration not set' }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                    <span class="{{ $set->workPortfolio->status == 'Active' ? 'text-green-600 bg-green-50 px-2 py-0.5 rounded-full' : '' }}">
                                        {{ $set->workPortfolio->status }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 leading-relaxed">{{ $set->workPortfolio->description }}</p>
                            </div>
                        </div>
                        @endif
                    @endforeach

                @else
                    <p class="text-gray-400 text-sm italic">No work history added.</p>
                @endif
            </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                    Engagements
                </h3>

                <div class="flex flex-col gap-3">
                    @forelse($user->engagements as $engagement)
                        <div class="group p-3 rounded-xl bg-gray-50 hover:bg-purple-50 border border-gray-100 transition duration-200 cursor-default">
                            <h5 class="font-bold text-gray-700 text-sm group-hover:text-purple-700 mb-1">
                                {{ $engagement->title }}
                            </h5>
                            <p class="text-xs text-gray-500 group-hover:text-purple-600 line-clamp-2">
                                {{ $engagement->description }}
                            </p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm italic">No public engagements.</p>
                    @endforelse
                </div>
                
                @if($user->profile->portfolioSet && $user->profile->portfolioSet->work_portfolio)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="font-bold text-gray-800 text-sm mb-2">Portfolio Link</h4>
                        <a href="{{ $user->profile->portfolioSet->work_portfolio }}" target="_blank" class="flex items-center gap-2 text-blue-600 text-sm hover:underline truncate">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            {{ $user->profile->portfolioSet->work_portfolio }}
                        </a>
                    </div>
                @endif
            </div>

        </div>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-500">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Courses</h3>
                    <p class="text-gray-500">{{ $courses->total() }}</p>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                 <div class="w-12 h-12 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Total students</h3>
                    <p class="text-gray-500">--</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                 <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-lg">Evaluations</h3>
                    <p class="text-gray-500">--</p>
                </div>
            </div>
        </section>

        <section>
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-xl font-bold text-gray-800 self-start md:self-center">Your Courses</h3>

                <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                    <div class="relative group w-full md:w-auto">
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="Search your courses..." 
                            class="pl-4 pr-10 py-2 rounded-full border border-gray-200 text-sm w-full md:w-64 focus:outline-none focus:border-gray-400 shadow-sm"
                        >
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>

                    <div class="relative w-full md:w-auto">
                        <select 
                            wire:model.live="sort" 
                            class="appearance-none bg-white pl-4 pr-10 py-2 rounded-full border border-gray-200 text-sm w-full md:w-40 focus:outline-none focus:border-gray-400 shadow-sm cursor-pointer"
                        >
                            <option value="latest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="a-z">Title (A-Z)</option>
                            <option value="z-a">Title (Z-A)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-4"> @forelse($courses as $course)
                
                @php
                    $orgColor = $course->organization->color ?? 'gray';
                @endphp

                <div class="relative bg-white rounded-[20px] p-3 shadow-sm border flex gap-4 hover:shadow-md transition duration-200 group overflow-hidden
                    {{ $course->organization_id ? 'border-l-[6px] border-y-gray-100 border-r-gray-100' : 'border-gray-100' }}"
                    style="{{ $course->organization_id ? "border-left-color: var(--color-$orgColor-500, $orgColor)" : '' }}">
                    
                    <div class="w-[140px] shrink-0 h-32 bg-gray-200 rounded-xl overflow-hidden relative">
                         @if($course->coverPhoto && $course->coverPhoto->url)
                            <img src="{{ asset('storage/' . $course->coverPhoto->url) }}" class="w-full h-full object-cover">
                         @else
                            <img src="https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&q=80&w=300&h=300" class="w-full h-full object-cover opacity-80">
                         @endif
                    </div>

                    <div class="flex-1 flex flex-col relative py-1">
                        
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex gap-2 items-center">
                                @if($course->category)
                                    <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[9px] font-bold uppercase tracking-wider border border-gray-200">
                                        {{ $course->category->category_name }}
                                    </span>
                                @endif
                                
                                <span class="text-[10px] text-gray-400">
                                    {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('M Y') : 'TBA' }}
                                </span>
                            </div>
                            <button class="text-gray-300 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                            </button>
                        </div>

                        @if($course->organization)
                        <div class="flex items-center gap-1.5 mb-1 text-{{ $orgColor }}-600" style="color: var(--color-{{$orgColor}}-600, {{$orgColor}})">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-[10px] font-bold uppercase tracking-wide truncate max-w-[200px]">
                                {{ $course->organization->name }}
                            </span>
                        </div>
                        @endif

                        <h3 class="font-bold text-gray-900 text-base mb-1 leading-snug">
                            {{ $course->course_title }}
                        </h3>

                        <div class="relative mb-2 group-hover:absolute group-hover:bg-white group-hover:shadow-lg group-hover:z-20 group-hover:p-2 group-hover:-m-2 group-hover:rounded-lg transition-all duration-300 w-3/4">
                            <p class="text-xs text-gray-500 line-clamp-1 group-hover:line-clamp-none transition-all duration-300">
                                {{ $course->background ?? 'No description.' }}
                            </p>
                        </div>

                        @if($course->tags->count() > 0)
                        <div class="flex flex-wrap gap-1 mt-auto">
                            @foreach($course->tags->take(3) as $tag)
                                <span class="text-[9px] px-1.5 py-0.5 rounded bg-gray-50 border border-gray-100 text-gray-500">
                                    #{{ $tag->tag }}
                                </span>
                            @endforeach
                            @if($course->tags->count() > 3)
                                <span class="text-[9px] text-gray-400 self-center">+{{ $course->tags->count() - 3 }}</span>
                            @endif
                        </div>
                        @endif

                        <div class="absolute bottom-1 right-0">
                             <button class="bg-black text-white text-[10px] font-bold py-1.5 px-5 rounded-full hover:bg-gray-800 transition shadow-sm">
                                Manage
                            </button>
                        </div>

                    </div>
                </div>
                @empty
                    <div class="text-center py-10 text-gray-400 bg-white rounded-3xl border border-gray-100">
                        <p class="text-sm">No courses found matching your criteria.</p>
                        <button wire:click="$set('search', '')" class="text-xs text-blue-500 font-bold mt-2 hover:underline">Clear Search</button>
                    </div>
                @endforelse
            </div>
            <div class="mt-8">
                {{ $courses->links() }}
            </div>
        </section>
    </main>

    @if($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 overflow-y-auto" 
        x-data @keydown.escape.window="$wire.set('showEditModal', false)">
        
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative animate-in fade-in zoom-in duration-200 flex flex-col">
            
            <div class="sticky top-0 bg-white px-8 py-5 border-b border-gray-100 flex justify-between items-center z-10">
                <h2 class="text-xl font-bold text-gray-800">Edit Profile</h2>
                <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-600 transition p-1 hover:bg-gray-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-8 space-y-8 overflow-y-auto">
                
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Basic Information
                    </h3>
                    <div class="flex flex-col items-center gap-4 mb-6">
                        <div class="relative group cursor-pointer" onclick="document.getElementById('photoInput').click()">
                            
                            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-md bg-gray-100">
                                @if ($new_photo)
                                    <img src="{{ $new_photo->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif ($user->profile->photo_id)
                                    <img src="{{ asset('storage/' . $user->profile->photo->photos) }}" class="w-full h-full object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($first_name . ' ' . $last_name) }}&background=bfdbfe&color=1e3a8a&size=128" class="w-full h-full object-cover">
                                @endif
                            </div>

                            <div class="absolute inset-0 bg-black/30 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                                <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </div>
                        </div>
                        
                        <p class="text-xs text-gray-400">Click to change photo</p>

                        <input type="file" id="photoInput" wire:model="new_photo" class="hidden" accept="image/png, image/jpeg, image/jpg">
                        
                        <div wire:loading wire:target="new_photo" class="text-xs text-blue-500 font-bold">
                            Uploading...
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">First Name</label>
                            <input wire:model="first_name" type="text" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50/50 px-4">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Middle Name</label>
                            <input wire:model="middle_name" type="text" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50/50 px-4">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Last Name</label>
                            <input wire:model="last_name" type="text" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50/50 px-4">
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-600 mb-1">Username</label>
                            <input wire:model="username" type="text" class="w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 text-sm bg-gray-50/50 px-4">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 mb-1">Email Address <span class="text-[10px] font-normal">(Contact admin to change)</span></label>
                            <input wire:model="email" type="email" disabled class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed text-sm px-4">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100 border-dashed">

                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            Work Experience
                        </h3>
                        <button wire:click="addWorkExperience" class="text-xs font-bold text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                            Add Position
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($work_experiences as $index => $work)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 relative group hover:border-blue-200 transition duration-200">
                            
                            <button wire:click="removeWorkExperience({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 p-1 bg-white rounded-md shadow-sm opacity-0 group-hover:opacity-100 transition z-10">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Designation / Role</label>
                                    <input wire:model="work_experiences.{{ $index }}.designation" type="text" placeholder="e.g. Senior Instructor" class="w-full rounded-lg px-4 border-gray-200 text-sm py-1.5 focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Workplace</label>
                                    <input wire:model="work_experiences.{{ $index }}.workplace" type="text" placeholder="e.g. UNICEF" class="w-full rounded-lg px-4 border-gray-200 text-sm py-1.5 focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Duration</label>
                                    <input wire:model="work_experiences.{{ $index }}.duration" type="text" placeholder="e.g. 2020 - Present" class="w-full rounded-lg px-4 border-gray-200 text-sm py-1.5 focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Status</label>
                                    <div class="relative">
                                        <select wire:model="work_experiences.{{ $index }}.status" class="w-full rounded-lg px-4 border-gray-200 text-sm py-1.5 bg-white  focus:border-blue-500 focus:ring-blue-500 appearance-none pl-3 pr-8">
                                            <option value="Active">Active</option>
                                            <option value="Former">Former</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Description</label>
                                    <textarea wire:model="work_experiences.{{ $index }}.description" rows="2" placeholder="Briefly describe responsibilities..." class="w-full rounded-lg px-4 border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if(empty($work_experiences))
                            <div class="text-center py-8 text-gray-400 text-sm bg-gray-50 rounded-xl border border-dashed border-gray-200 flex flex-col items-center justify-center gap-2">
                                <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                <p>No work experience listed yet.</p>
                                <button wire:click="addWorkExperience" class="text-xs font-bold text-blue-500 hover:underline">Add one now</button>
                            </div>
                        @endif
                    </div>
                </div>

                <hr class="border-gray-100 border-dashed">

                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                            Engagements
                        </h3>
                        <button wire:click="addEngagement" class="text-xs font-bold text-purple-600 hover:bg-purple-50 px-3 py-1.5 rounded-lg transition flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                            Add Engagement
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($engagements_list as $index => $engagement)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 relative group hover:border-purple-200 transition duration-200">
                            
                            <button wire:click="removeEngagement({{ $index }})" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 p-1 bg-white rounded-md shadow-sm opacity-0 group-hover:opacity-100 transition z-10">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>

                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Title</label>
                                    <input wire:model="engagements_list.{{ $index }}.title" type="text" placeholder="e.g. Speaker at Tech Summit" class="w-full rounded-lg px-4 border-gray-200 text-sm py-1.5 focus:border-purple-500 focus:ring-purple-500">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Description</label>
                                    <textarea wire:model="engagements_list.{{ $index }}.description" rows="2" placeholder="Brief description of the activity..." class="w-full rounded-lg px-4 border-gray-200 text-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        
                        @if(empty($engagements_list))
                            <div class="text-center py-6 text-gray-400 text-sm bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                No engagements listed yet.
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="sticky bottom-0 bg-white px-8 py-5 border-t border-gray-100 flex justify-end gap-3 z-10 rounded-b-3xl">
                <button wire:click="$set('showEditModal', false)" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition">
                    Cancel
                </button>
                <button wire:click="saveProfile" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-black hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                    Save Changes
                </button>
            </div>

        </div>
    </div>
    @endif
</div>