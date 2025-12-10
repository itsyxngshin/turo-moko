<div class="flex h-screen bg-gray-50 font-sans">
    <main class="flex-1 px-5 py-8 overflow-y-auto"> <div class="mb-6">
            <a href="{{ url()->previous() }}" class="flex items-center gap-2 text-gray-500 hover:text-black transition text-sm font-bold">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Back to Courses
            </a>
        </div>

        <section class="bg-white rounded-3xl p-8 mb-8 shadow-sm border border-gray-100">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="w-32 h-32 rounded-full overflow-hidden bg-blue-100 border-4 border-white shadow-md flex-shrink-0">
                    @if($user->profile->photo)
                        <img src="{{ asset('storage/' . $user->profile->photo->photos) }}" class="w-full h-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->profile->first_name . ' ' . $user->profile->last_name) }}&background=bfdbfe&color=1e3a8a&size=128" class="w-full h-full object-cover">
                    @endif
                </div>
                
                <div class="flex flex-col gap-1 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-gray-800">
                        {{ $user->profile->first_name }} {{ $user->profile->last_name }}
                    </h2>
                    <p class="text-gray-500 font-medium">
                        {{ $user->role->role_name ?? 'Implementor' }}
                    </p>
                    <p class="text-gray-400 text-sm mt-1">{{ $user->email }}</p>
                    
                    @if($user->profile->portfolioSet && $user->profile->portfolioSet->work_portfolio)
                        <a href="{{ $user->profile->portfolioSet->work_portfolio }}" target="_blank" class="mt-2 text-blue-600 hover:underline text-sm flex items-center justify-center md:justify-start gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            View Portfolio
                        </a>
                    @endif
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
                @forelse($user->profile->portfolioSets as $set)
                    @if($set->workPortfolio) 
                    <div class="flex gap-4 relative">
                        <div class="w-10 h-10 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0 z-10">
                            <span class="text-xs font-bold text-gray-600">{{ substr($set->workPortfolio->designation, 0, 1) }}</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 text-sm">{{ $set->workPortfolio->designation }}</h4>
                            <div class="flex items-center gap-2 text-xs text-gray-400 mb-1">
                                <span>{{ $set->workPortfolio->workplace ?? 'Unspecified' }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span>{{ $set->workPortfolio->duration ?? 'N/A' }}</span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $set->workPortfolio->description }}</p>
                        </div>
                    </div>
                    @endif
                @empty
                    <p class="text-gray-400 text-sm italic">No work history to show.</p>
                @endforelse
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                    Engagements
                </h3>
                <div class="flex flex-col gap-3">
                    @forelse($user->engagements as $engagement)
                        <div class="p-3 rounded-xl bg-gray-50 border border-gray-100">
                            <h5 class="font-bold text-gray-700 text-sm mb-1">{{ $engagement->title }}</h5>
                            <p class="text-xs text-gray-500">{{ $engagement->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm italic">No engagements to show.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <section>
            <h3 class="text-xl font-bold text-gray-800 mb-6">Courses by {{ $user->profile->first_name }}</h3>
            
            <div class="flex flex-col gap-4"> 
            @forelse($courses as $course)
                 {{-- Reuse your existing course card HTML here --}}
                 {{-- REMOVE THE "MANAGE" BUTTON from the card for this view --}}
                 {{-- Instead, add a "View Course" button --}}
                 
                <div class="relative bg-white rounded-[20px] p-3 shadow-sm border flex gap-4 hover:shadow-md transition duration-200">
                    <div class="w-[140px] shrink-0 h-32 bg-gray-200 rounded-xl overflow-hidden">
                        @if($course->coverPhoto)
                           <img src="{{ asset('storage/' . $course->coverPhoto->url) }}" class="w-full h-full object-cover">
                        @else
                           <div class="w-full h-full bg-gray-300"></div>
                        @endif
                    </div>
                    <div class="flex-1 py-1">
                        <h3 class="font-bold text-gray-900">{{ $course->course_title }}</h3>
                        <p class="text-xs text-gray-500 line-clamp-2 mt-1">{{ $course->background }}</p>
                        <div class="mt-4">
                            <a href="#" class="bg-blue-600 text-white text-[10px] font-bold py-1.5 px-5 rounded-full hover:bg-blue-700 transition">
                                View Course
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400">No active courses found.</p>
            @endforelse
            </div>
            
            <div class="mt-8">{{ $courses->links() }}</div>
        </section>

    </main>
</div>