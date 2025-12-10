<div class="flex h-screen bg-gray-50 font-sans overflow-hidden">
    <main class="flex-1 overflow-y-auto relative">
        
        <div class="relative bg-gradient-to-br from-orange-600 via-amber-600 to-yellow-600 h-64 rounded-b-[3rem] shadow-lg flex-shrink-0">
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-20 pointer-events-none">
                <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full bg-white blur-[80px]"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full bg-yellow-400 blur-[100px]"></div>
            </div>

            <div class="absolute top-6 left-6 z-10">
                <a href="{{ url()->previous() }}" class="flex items-center gap-2 text-white/80 hover:text-white transition text-sm font-bold bg-white/10 px-4 py-2 rounded-full backdrop-blur-sm hover:bg-white/20">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Back
                </a>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-12 -mt-32 relative z-10">
            
            <section class="bg-white rounded-3xl p-6 md:p-10 shadow-xl border border-gray-100 mb-10 relative overflow-visible">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6 md:gap-8">
                    
                    <div class="w-36 h-36 md:w-40 md:h-40 rounded-full bg-white p-1.5 shadow-2xl -mt-20 md:-mt-24 flex-shrink-0">
                        <div class="w-full h-full rounded-full overflow-hidden border-4 border-white bg-gray-100 relative">
                            @if($user->profile->photo)
                                <img src="{{ asset('storage/' . $user->profile->photo->photos) }}" class="w-full h-full object-cover">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->profile->first_name . ' ' . $user->profile->last_name) }}&background=ffedd5&color=c2410c&size=128" class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex-1 text-center md:text-left mb-2">
                        <div class="flex flex-col md:flex-row md:items-center gap-2 mb-1 justify-center md:justify-start">
                            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 tracking-tight">
                                {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                            </h2>
                            <span class="px-3 py-1 rounded-full bg-orange-50 text-orange-600 text-xs font-bold uppercase tracking-wide border border-orange-100 self-center">
                                {{ optional($user->role)->role_name ?? 'User' }}
                            </span>
                        </div>

                        <p class="text-gray-500 font-medium text-lg mb-3">
                            @if(optional($user->role)->role_name === 'learner')
                                Digital Learner
                            @elseif(optional($user->role)->role_name === 'admin')
                                TURO-MOKO's Resident Helper
                            @else
                                {{ $active_engagement->title ?? 'Digital Educator' }}
                            @endif
                        </p>
                        
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                {{ $user->email }}
                            </span>
                            
                            @if($user->profile->portfolioSet && $user->profile->portfolioSet->work_portfolio)
                                <a href="{{ $user->profile->portfolioSet->work_portfolio }}" target="_blank" class="group flex items-center gap-1 text-orange-600 hover:text-orange-700 font-semibold transition">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                    View Portfolio
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 w-full">
                
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-900 text-lg mb-6 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </div>
                            Experience
                        </h3>
                        
                        <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                        @forelse($user->profile->portfolioSets as $set)
                            @if($set->workPortfolio) 
                            <div class="relative flex items-start group">
                                <div class="absolute left-0 top-1 h-10 w-10 flex items-center justify-center">
                                    <div class="h-3 w-3 rounded-full bg-orange-100 border-2 border-orange-500 ring-4 ring-white"></div>
                                </div>
                                <div class="ml-12 w-full">
                                    <h4 class="font-bold text-gray-800 text-sm group-hover:text-orange-600 transition">{{ $set->workPortfolio->designation }}</h4>
                                    <div class="flex flex-wrap items-center gap-2 text-xs text-gray-400 mt-1 mb-2">
                                        <span class="font-medium text-gray-500">{{ $set->workPortfolio->workplace ?? 'Unspecified' }}</span>
                                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                        <span>{{ $set->workPortfolio->duration ?? 'N/A' }}</span>
                                    </div>
                                    <p class="text-sm text-gray-500 leading-relaxed bg-gray-50 p-3 rounded-xl border border-gray-100">
                                        {{ $set->workPortfolio->description }}
                                    </p>
                                </div>
                            </div>
                            @endif
                        @empty
                            <p class="text-gray-400 text-sm italic pl-12">No work history listed.</p>
                        @endforelse
                        </div>
                    </div>

                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <h3 class="font-bold text-gray-900 text-lg mb-6 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                            </div>
                            Engagements
                        </h3>
                        <div class="flex flex-col gap-3">
                            @forelse($user->engagements as $engagement)
                                <div class="p-4 rounded-2xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 hover:shadow-md hover:border-orange-100 transition duration-300">
                                    <h5 class="font-bold text-gray-800 text-sm mb-1">{{ $engagement->title }}</h5>
                                    <p class="text-xs text-gray-500 line-clamp-2">{{ $engagement->description }}</p>
                                </div>
                            @empty
                                <p class="text-gray-400 text-sm italic">No engagements listed.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                @if(optional($user->role)->role_name !== 'admin')
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-gray-100 min-h-full">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <span class="w-2 h-8 bg-orange-600 rounded-full"></span>
                                    @if(optional($user->role)->role_name === 'learner')
                                        Enrolled Courses
                                    @else
                                        Featured Courses
                                    @endif
                                </h3>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $courses->total() }} Available</span>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5"> 
                            @forelse($courses as $course)
                                <div class="group relative bg-white rounded-2xl p-3 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                                    <div class="w-full h-40 bg-gray-200 rounded-xl overflow-hidden relative mb-3">
                                        @if($course->coverPhoto)
                                        <img src="{{ asset('storage/' . $course->coverPhoto->url) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        @else
                                        <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                        @endif
                                        
                                        @if($course->category)
                                            <div class="absolute top-3 left-3 px-2 py-1 bg-white/90 backdrop-blur-md rounded-lg text-[10px] font-bold uppercase tracking-wide text-gray-800 shadow-sm">
                                                {{ $course->category->category_name }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex-1 flex flex-col px-1">
                                        <h3 class="font-bold text-gray-900 text-lg leading-tight mb-2 group-hover:text-orange-600 transition">{{ $course->course_title }}</h3>
                                        <p class="text-xs text-gray-500 line-clamp-2 mb-4 flex-1">{{ $course->background }}</p>
                                        
                                        <div class="mt-auto pt-3 border-t border-gray-50 flex items-center justify-between">
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                                {{ $course->start_date ? \Carbon\Carbon::parse($course->start_date)->format('M Y') : 'Self-Paced' }}
                                            </span>
                                            
                                            {{-- BUTTON LOGIC: Hide "View Details" if the PROFILE OWNER is a Learner --}}
                                            @if(optional($user->role)->role_name !== 'learner')
                                                <a href="#" class="bg-black text-white text-xs font-bold py-2 px-4 rounded-full group-hover:bg-orange-600 transition shadow-lg shadow-gray-200 group-hover:shadow-orange-200">
                                                    View Details
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 text-center py-12 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                    <p class="text-gray-500 font-medium">No active courses found.</p>
                                </div>
                            @endforelse
                            </div>
                            
                            <div class="mt-8">
                                {{ $courses->links() }}
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </main>
</div>