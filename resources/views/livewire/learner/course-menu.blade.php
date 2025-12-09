<div class="flex h-screen bg-[#F8F9FB] font-sans">
    <!-- Course Menu Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        
        <header class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <h1 class="text-xl font-bold text-gray-800 self-start md:self-center">Courses</h1>

    <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
        
        <div class="relative group w-full md:w-auto">
            <input 
                wire:model.live.debounce.300ms="search" 
                type="text" 
                placeholder="Search tags, orgs, categories..." 
                class="pl-4 pr-10 py-2 rounded-full border border-gray-200 text-sm w-full md:w-72 focus:outline-none focus:border-gray-400 focus:ring-0 transition placeholder-gray-400 shadow-sm"
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

        <button class="hidden md:block w-9 h-9 rounded-full bg-gray-200 overflow-hidden border border-gray-200 shrink-0">
            <img src="https://ui-avatars.com/api/?name=User&background=random" alt="Profile" />
        </button>
    </div>
</header>

        <h2 class="text-base font-bold text-gray-800 mb-5">Course Suggestions</h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @foreach($courses as $course)
            
            @php
                // Get the organization color or default to 'gray' if none exists
                $orgColor = $course->organization->color ?? 'gray';
            @endphp

            <div class="relative bg-white rounded-[20px] p-3 shadow-sm border flex gap-4 hover:shadow-md transition duration-200 group overflow-hidden
                {{ $course->organization_id ? 'border-l-[6px] border-y-gray-100 border-r-gray-100' : 'border-gray-100' }}"
                
               
                >

                <div class="@if($course->organization_id) border-l-{{$orgColor}}-500 @endif hidden"></div>

                <div class="w-[140px] shrink-0 h-32 bg-gray-200 rounded-xl overflow-hidden relative">
                    @if($course->coverPhoto)
                        <img src="{{ asset('storage/' . $course->coverPhoto->path) }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('images/turo_moko_logo.png') }}" class="w-full h-full object-cover opacity-80">
                    @endif
                </div>

                <div class="flex-1 flex flex-col relative py-1">
                    
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex gap-2 items-center">
                            @if($course->category)
                                <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[9px] font-bold uppercase tracking-wider border border-gray-200">
                                    {{ $course->category->category_name }}
                                </span>
                            @endif
                        </div>
                        <button class="text-gray-300 hover:text-gray-600"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg></button>
                    </div>

                    @if($course->organization)
                    <div class="flex items-center gap-1.5 mb-1 text-{{ $orgColor }}-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <span class="text-[10px] font-bold uppercase tracking-wide truncate max-w-[150px]">
                            {{ $course->organization->name }}
                        </span>
                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.747a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    </div>
                    @endif

                    <h3 class="font-bold text-gray-900 text-sm mb-1 leading-snug truncate">
                        {{ $course->course_title }}
                    </h3>

                    <div class="relative mb-2 group-hover:absolute group-hover:bg-white group-hover:shadow-lg group-hover:z-20 group-hover:p-2 group-hover:-m-2 group-hover:rounded-lg transition-all duration-300">
                        <p class="text-[11px] text-gray-500 line-clamp-2 group-hover:line-clamp-none transition-all duration-300">
                            {{ $course->background }}
                        </p>
                    </div>

                    @if($course->tags->count() > 0)
                    <div class="flex flex-wrap gap-1 mb-2">
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

                    <div class="mt-auto flex justify-end">
                        <button class="bg-black text-white text-[10px] font-bold py-1.5 px-6 rounded-full hover:bg-gray-800 transition transform hover:scale-105 active:scale-95 shadow-sm">
                            Start
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $courses->links() }}
        </div>

    </main>
</div>