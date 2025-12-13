<div x-data="{ view: 'card' }"> {{-- single root element --}}
    <div class="max-w-[1600px] mx-auto px-6">

        {{-- Header --}}
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-semibold text-gray-900">Your Courses</h2>

            <div class="flex items-center gap-3">
                <select
                    x-model="view"
                    class="border rounded-full px-4 py-2 text-sm bg-white shadow-sm cursor-pointer"
                >
                    <option value="card">Card View</option>
                    <option value="list">List View</option>
                </select>

                <livewire:modals.implementor.create-course />
            </div>
        </div>

        {{-- ================= CARD VIEW ================= --}}
        <div
            x-show="view === 'card'"
            x-cloak
            class="grid grid-cols-1 lg:grid-cols-2 gap-6"
        >
            @forelse($courses as $course)
                <div class="relative bg-white rounded-2xl p-4 shadow-sm border flex gap-5 hover:shadow-md transition">

                    {{-- Thumbnail --}}
                    <div class="w-[160px] h-36 shrink-0 bg-gray-100 rounded-xl overflow-hidden border border-gray-100">
                        <img
                            src="{{ $course->activeCoverPhoto
                                ? asset('storage/' . $course->activeCoverPhoto->path)
                                : asset('storage/implementor/course/thumbnail.jpg') }}"
                            class="w-full h-full object-cover"
                        >
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 flex flex-col">
                        <div class="flex justify-between mb-1">
                            @if($course->category)
                                <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[9px] font-bold uppercase tracking-wider border border-gray-200">
                                    {{ $course->category->category_name }}
                                </span>
                            @endif
                            <p class="text-[10px] text-gray-400 font-medium">
                                {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') }} - 
                                {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') }}
                            </p>
                        </div>

                        <h3 class="font-bold text-gray-900 text-sm mb-1 truncate">
                            {{ $course->course_title }}
                        </h3>

                        <p class="text-[11px] text-gray-500 line-clamp-2 mb-2" title="{{ $course->background }}">
                            {{ $course->background }}
                        </p>

                        @if($course->organization)
                            <p class="text-[10px] font-bold uppercase tracking-wide text-{{ $course->organization->color ?? 'gray' }}-600 mb-1">
                                {{ $course->organization->name }}
                            </p>
                        @endif

                        <div class="flex items-center gap-1.5 mb-2 text-[11px] text-gray-500">
                            <span>{{ $course->implementer->profile->first_name ?? '--' }} {{ $course->implementer->profile->last_name ?? '' }}</span>
                        </div>

                        @if($course->tags->count() > 0)
                            <div class="flex flex-wrap gap-1 mb-2 text-[9px]">
                                @foreach($course->tags as $tag)
                                    <span class="px-1.5 py-0.5 rounded bg-gray-50 border border-gray-100 text-gray-500">
                                        #{{ $tag->tag }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        <div class="mt-auto flex justify-end">
                            <a
                                href="{{ route('implementor.course-information', $course) }}"
                                class="bg-black text-white text-[10px] font-bold py-1.5 px-6 rounded-full hover:bg-gray-800 transition transform hover:scale-105 active:scale-95 shadow-sm"
                            >
                                View
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-14 text-gray-400 bg-white rounded-2xl border border-dashed">
                    No courses found.
                </div>
            @endforelse
        </div>

        {{-- ================= LIST VIEW ================= --}}
        <div
            x-show="view === 'list'"
            x-cloak
            class="space-y-3"
        >
            @forelse($courses as $course)
                <div class="bg-white border rounded-2xl p-4 flex flex-col hover:shadow-sm transition">

                    <div class="flex gap-4">
                        {{-- Thumbnail --}}
                        <div class="w-[140px] h-32 shrink-0 bg-gray-100 rounded-xl overflow-hidden border border-gray-100">
                            <img
                                src="{{ $course->activeCoverPhoto
                                    ? asset('storage/' . $course->activeCoverPhoto->path)
                                    : asset('storage/implementor/course/thumbnail.jpg') }}"
                                class="w-full h-full object-cover"
                            >
                        </div>

                        <div class="flex-1 flex flex-col">
                            {{-- Category & Dates --}}
                            <div class="flex justify-between mb-1">
                                @if($course->category)
                                    <span class="px-2 py-0.5 rounded-md bg-gray-100 text-gray-600 text-[9px] font-bold uppercase tracking-wider border border-gray-200">
                                        {{ $course->category->category_name }}
                                    </span>
                                @endif
                                <p class="text-[10px] text-gray-400 font-medium">
                                    {{ \Carbon\Carbon::parse($course->start_date)->format('M Y') }} - 
                                    {{ \Carbon\Carbon::parse($course->end_date)->format('M Y') }}
                                </p>
                            </div>

                            {{-- Title --}}
                            <h3 class="font-bold text-gray-900 text-sm mb-1 truncate">
                                {{ $course->course_title }}
                            </h3>

                            {{-- Background --}}
                            <p class="text-[11px] text-gray-500 line-clamp-2 mb-2" title="{{ $course->background }}">
                                {{ $course->background }}
                            </p>

                            {{-- Organization --}}
                            @if($course->organization)
                                <p class="text-[10px] font-bold uppercase tracking-wide text-{{ $course->organization->color ?? 'gray' }}-600 mb-1">
                                    {{ $course->organization->name }}
                                </p>
                            @endif

                            {{-- Instructor --}}
                            <div class="flex items-center gap-1.5 mb-2 text-[11px] text-gray-500">
                                <span>{{ $course->implementer->profile->first_name ?? '--' }} {{ $course->implementer->profile->last_name ?? '' }}</span>
                            </div>

                            {{-- Tags --}}
                            @if($course->tags->count() > 0)
                                <div class="flex flex-wrap gap-1 mb-2 text-[9px]">
                                    @foreach($course->tags as $tag)
                                        <span class="px-1.5 py-0.5 rounded bg-gray-50 border border-gray-100 text-gray-500">
                                            #{{ $tag->tag }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-auto flex justify-end">
                                <a
                                    href="{{ route('implementor.course-information', $course) }}"
                                    class="bg-black text-white text-[10px] font-bold py-1.5 px-6 rounded-full hover:bg-gray-800 transition transform hover:scale-105 active:scale-95 shadow-sm"
                                >
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-400 bg-white rounded-2xl border border-dashed">
                    No courses found.
                </div>
            @endforelse
        </div>

    </div>
</div>
