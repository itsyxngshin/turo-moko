<div> {{-- ✅ single root element --}}
    <div class="p-0 ml-[20px]">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold">Your Courses</h2>

            <div class="flex items-center gap-3">
                <select class="border rounded-full px-4 py-2 text-sm bg-white shadow-sm cursor-pointer">
                    <option value="card">Card View</option>
                    <option value="list">List View</option>
                </select>

                <livewire:modals.implementor.create-course />
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse($courses as $course)
                
                @php
                    // Dynamic Organization Color Logic
                    $orgColor = $course->organization->color ?? 'gray';
                @endphp

                <div class="relative bg-white rounded-[20px] p-3 shadow-sm border flex gap-4 hover:shadow-md transition duration-200 group overflow-hidden
                            {{ $course->organization_id ? 'border-l-[6px] border-y-gray-100 border-r-gray-100' : 'border-gray-100' }}">

                    {{-- Hidden helper for tailwind dynamic class generation --}}
                    <div class="@if($course->organization_id) border-l-{{$orgColor}}-500 @endif hidden"></div>

                    <div class="w-[140px] shrink-0 h-32 bg-gray-100 rounded-xl overflow-hidden relative border border-gray-100">
                        @if($course->activeCoverPhoto)
                            <img 
                                src="{{ asset('storage/' . $course->activeCoverPhoto->path) }}" 
                                alt="{{ $course->course_title }}" 
                                class="w-full h-full object-cover"
                            >
                        @else
                            @php
                                $colors = ['bg-blue-100 text-blue-600', 'bg-red-100 text-red-600', 'bg-green-100 text-green-600', 'bg-purple-100 text-purple-600', 'bg-orange-100 text-orange-600'];
                                $randomColor = $colors[$course->id % count($colors)];
                                $initials = collect(explode(' ', $course->course_title))->map(fn($w) => $w[0] ?? '')->take(2)->implode('');
                            @endphp
                            
                            <div class="w-full h-full {{ $randomColor }} flex flex-col items-center justify-center p-2 text-center">
                                <span class="text-xl font-bold tracking-widest uppercase opacity-80">{{ $initials }}</span>
                                <span class="text-[9px] mt-1 opacity-60 font-medium">No Cover</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 flex flex-col relative py-1">
                        
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex gap-2 items-center">
                                @if($course->category)
                                    <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[9px] font-bold uppercase tracking-wider border border-gray-200">
                                        {{ $course->category->category_name }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-[10px] text-gray-400 font-medium">
                                {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') }} - 
                                {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') }}
                            </p>
                        </div>

                        @if($course->organization)
                        <div class="flex items-center gap-1.5 mb-1 text-{{ $orgColor }}-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span class="text-[10px] font-bold uppercase tracking-wide truncate max-w-[150px]">
                                {{ $course->organization->name }}
                            </span>
                        </div>
                        @endif

                        <div class="flex items-center gap-1.5 mb-2 group/author">
                            {{-- Avatar (Optional: Use initials if no photo) --}}
                            <div class="w-4 h-4 rounded-full bg-gray-200 overflow-hidden shrink-0 border border-gray-100">
                                <img src="{{ $course->implementer->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->implementer->first_name).'&background=random' }}" 
                                    alt="{{ $course->implementer->first_name }}" 
                                    class="w-full h-full object-cover">
                            </div>
                            
                            {{-- Name Link --}}
                            <a href="{{ route('profile.public', ['username' => $course->implementer->username]) }}" 
                                class="text-[11px] font-medium text-gray-500 hover:text-black hover:underline transition-colors truncate" 
                                title="View Profile">
                                {{ $course->implementer->profile->first_name }} {{ $course->implementer->profile->last_name }}
                            </a>
                        </div>

                        <h3 class="font-bold text-gray-900 text-sm mb-1 leading-snug truncate">
                            {{ $course->course_title }}
                        </h3>

                        <div class="mb-3 h-[34px]"> {{-- Fixed height keeps alignment perfect --}}
                            <p class="text-[11px] text-gray-500 line-clamp-2 leading-relaxed" title="{{ $course->background }}">
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
                            <a href="{{ route('implementor.course-information', $course) }}" 
                               class="bg-black text-white text-[10px] font-bold py-1.5 px-6 rounded-full hover:bg-gray-800 transition transform hover:scale-105 active:scale-95 shadow-sm">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-12 text-gray-400 bg-white rounded-2xl border border-dashed">
                    No courses found.
                </div>
            @endforelse
        </div>

    </div>
</div>