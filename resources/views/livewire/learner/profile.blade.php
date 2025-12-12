<div class="flex h-screen bg-gray-50 font-sans">
    <main class="flex-1 px-4 md:px-5 py-3 overflow-y-auto">

        <header class="flex justify-between items-center mb-6 md:mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Profile</h1>
        </header>

        <section class="bg-white rounded-3xl p-6 md:p-8 mb-8 shadow-sm border border-gray-100 relative">
            
            <div class="absolute top-4 right-4 md:top-6 md:right-6" x-data="{ open: false }">
                <button @click="open = !open" class="p-2 rounded-full text-gray-400 hover:text-gray-600 hover:bg-gray-50 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                </button>

                <div x-show="open" 
                    @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-20 overflow-hidden"
                    style="display: none;">
                    <button wire:click="openEditModal" @click="open = false" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                        Edit Profile
                    </button>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center gap-6 md:gap-8">
                <div class="w-24 h-24 md:w-32 md:h-32 rounded-full overflow-hidden bg-blue-100 border-4 border-white shadow-md flex-shrink-0">
                    @if($user->profile->photo_id)
                        <img src="{{ asset('storage/' . $user->profile->photo->photos) }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->profile->first_name . ' ' . $user->profile->last_name) }}&background=bfdbfe&color=1e3a8a&size=128" class="w-full h-full object-cover">
                    @endif
                </div>
                
                <div class="flex flex-col gap-1 text-center md:text-left">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">
                        {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                    </h2>
                    <p class="text-gray-500 font-medium">{{ $user->role->role_name ?? 'Learner' }}</p>
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
                        @if($set->workPortfolio) 
                        <div class="flex gap-4 relative">
                            @if(!$loop->last)
                                <div class="absolute left-[19px] top-8 bottom-[-24px] w-0.5 bg-gray-100"></div>
                            @endif
                            
                            <div class="w-10 h-10 rounded-full bg-green-50 border border-green-100 flex items-center justify-center shrink-0 z-10">
                                <span class="text-xs font-bold text-green-600">
                                    {{ substr($set->workPortfolio->designation, 0, 1) }}
                                </span>
                            </div>

                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">{{ $set->workPortfolio->designation }}</h4>
                                <div class="flex flex-wrap items-center gap-2 text-xs text-gray-400 mb-1">
                                    <span>{{ $set->workPortfolio->workplace ?? 'Unspecified' }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300 hidden md:inline"></span>
                                    <span>{{ $set->workPortfolio->duration ?? 'Duration not set' }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300 hidden md:inline"></span>
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
                            <h5 class="font-bold text-gray-700 text-sm group-hover:text-purple-700 mb-1">{{ $engagement->title }}</h5>
                            <p class="text-xs text-gray-500 group-hover:text-purple-600 line-clamp-2">{{ $engagement->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm italic">No public engagements.</p>
                    @endforelse
                </div>
                
                @if($user->profile->portfolioSet && $user->profile->portfolioSet->work_portfolio)
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="font-bold text-gray-800 text-sm mb-2">Portfolio Link</h4>
                        <a href="{{ $user->profile->portfolioSet->work_portfolio }}" target="_blank" class="flex items-center gap-2 text-blue-600 text-sm hover:underline truncate">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            <span class="truncate">{{ $user->profile->portfolioSet->work_portfolio }}</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <section class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 mb-10">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-2 h-8 bg-black rounded-full"></span>
                    Courses you're taking
                </h3>
                <span class="text-gray-500 text-sm font-medium">{{ $activeCoursesCount }} Active</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                @forelse ($activeCourses as $course)
                    <div class="bg-white rounded-2xl shadow-md flex flex-col sm:flex-row overflow-hidden border border-gray-100 h-auto sm:h-52 hover:shadow-lg transition">
                        
                        <div class="w-full sm:w-1/2 h-48 sm:h-full relative bg-gray-200">
                            @if($course->activeCoverPhoto)
                                <img src="{{ asset('storage/' . $course->activeCoverPhoto->path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                            @endif
                            <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-3 sm:hidden">
                                <p class="text-white text-xs font-bold truncate">{{ $course->category->category_name ?? 'General' }}</p>
                            </div>
                        </div>

                        <div class="p-4 sm:p-5 flex flex-col justify-between w-full sm:w-1/2 h-full">
                            <div>
                                <div class="flex justify-between items-start mb-1">
                                    <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-500 text-[10px] font-bold uppercase tracking-wider hidden sm:inline-block">
                                        {{ $course->category->category_name ?? 'General' }}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-bold text-gray-900 leading-tight line-clamp-2" title="{{ $course->course_title }}">
                                    {{ $course->course_title ?? 'Untitled Course' }}
                                </h3>
                                
                                <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                                    <span>Instructor:</span>
                                    <span class="font-bold text-gray-700 truncate max-w-[100px]">
                                        {{ $course->implementer->profile->first_name ?? 'Unknown' }}
                                    </span>
                                </p>
                                
                                <div class="flex items-center gap-2 mt-1 text-[10px] text-gray-400">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    @if($course->pivot && $course->pivot->enrollment_date)
                                        Enrolled: {{ \Carbon\Carbon::parse($course->pivot->enrollment_date)->format('M d, Y') }}
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex justify-end mt-3">
                                <a href="{{ route('learner.course.show', $course) }}"
                                   class="bg-black text-white px-5 py-2 rounded-full hover:bg-gray-800 text-xs font-bold shadow-md transition transform hover:-translate-y-0.5">
                                    View Course
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 text-center text-gray-400 py-12 border-2 border-dashed border-gray-100 rounded-2xl bg-gray-50">
                        <p class="text-lg font-semibold">No active courses yet 📚</p>
                        <p class="text-xs mt-1">Enroll in a course to see it here.</p>
                    </div>
                @endforelse
            </div>
        </section>

    </main>

    @if($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4" 
        x-data @keydown.escape.window="$wire.set('showEditModal', false)">
        
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto relative animate-in fade-in zoom-in duration-200 flex flex-col">
            
            <div class="sticky top-0 bg-white px-6 md:px-8 py-5 border-b border-gray-100 flex justify-between items-center z-10">
                <h2 class="text-xl font-bold text-gray-800">Edit Profile</h2>
                <button wire:click="$set('showEditModal', false)" class="text-gray-400 hover:text-gray-600 transition p-1 hover:bg-gray-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="p-6 md:p-8 space-y-8 overflow-y-auto">
                
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-4">Basic Information</h3>
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
                        <input type="file" id="photoInput" wire:model="new_photo" class="hidden" accept="image/png, image/jpeg, image/jpg">
                        <div wire:loading wire:target="new_photo" class="text-xs text-blue-500 font-bold">Uploading...</div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">First Name</label>
                            <input wire:model="first_name" type="text" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Middle Name</label>
                            <input wire:model="middle_name" type="text" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-600 mb-1">Last Name</label>
                            <input wire:model="last_name" type="text" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2">
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-600 mb-1">Username</label>
                            <input wire:model="username" type="text" class="w-full rounded-xl border-gray-200 text-sm px-4 py-2">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 mb-1">Email (Read Only)</label>
                            <input wire:model="email" type="email" disabled class="w-full rounded-xl border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed text-sm px-4 py-2">
                        </div>
                    </div>
                </div>

                <hr class="border-gray-100 border-dashed">

                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide">Work Experience</h3>
                        <button wire:click="addWorkExperience" class="text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition">+ Add</button>
                    </div>
                    <div class="space-y-4">
                        @foreach($work_experiences as $index => $work)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 relative">
                            <button wire:click="removeWorkExperience({{ $index }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <input wire:model="work_experiences.{{ $index }}.designation" placeholder="Role" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                                <input wire:model="work_experiences.{{ $index }}.workplace" placeholder="Company" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                                <input wire:model="work_experiences.{{ $index }}.duration" placeholder="Duration" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                                <div class="relative md:col-span-1">
                                    <select wire:model="work_experiences.{{ $index }}.status" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2 bg-white">
                                        <option value="Active">Active</option>
                                        <option value="Former">Former</option>
                                    </select>
                                </div>
                                <textarea wire:model="work_experiences.{{ $index }}.description" rows="2" placeholder="Description" class="w-full md:col-span-2 rounded-lg border-gray-200 text-sm px-3 py-2"></textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <hr class="border-gray-100 border-dashed">

                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide">Engagements</h3>
                        <button wire:click="addEngagement" class="text-xs font-bold text-purple-600 bg-purple-50 px-3 py-1.5 rounded-lg hover:bg-purple-100 transition">+ Add</button>
                    </div>
                    <div class="space-y-4">
                        @foreach($engagements_list as $index => $engagement)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 relative">
                            <button wire:click="removeEngagement({{ $index }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            <div class="grid grid-cols-1 gap-3">
                                <input wire:model="engagements_list.{{ $index }}.title" placeholder="Title" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2">
                                <textarea wire:model="engagements_list.{{ $index }}.description" rows="2" placeholder="Description" class="w-full rounded-lg border-gray-200 text-sm px-3 py-2"></textarea>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="sticky bottom-0 bg-white px-6 md:px-8 py-5 border-t border-gray-100 flex justify-end gap-3 z-10 rounded-b-3xl">
                <button wire:click="$set('showEditModal', false)" class="px-5 py-2.5 rounded-xl text-sm font-bold text-gray-500 hover:bg-gray-100 transition">Cancel</button>
                <button wire:click="saveProfile" class="px-6 py-2.5 rounded-xl text-sm font-bold text-white bg-black hover:bg-gray-800 transition shadow-lg shadow-gray-200">
                    <span wire:loading.remove wire:target="saveProfile">Save Changes</span>
                    <span wire:loading wire:target="saveProfile">Saving...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>