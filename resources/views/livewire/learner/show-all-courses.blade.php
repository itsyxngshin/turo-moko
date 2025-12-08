@extends('layouts.layout')

@section('title', 'Suggested Courses')
@section('page-title', 'All Courses')

@section('content')
<div class="container mx-auto px-3 py-0">
    <div class="px-4 py-4 flex items-center gap-4">
        <button 
            onclick="window.location='{{ route('learner.hub') }}'" 
            class="flex items-center gap-2 text-black hover:text-black font-semibold group"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span class="hidden group-hover:inline translate-x-0 transition-all duration-300">Back</span>
        </button>
        <h2 class="text-2xl font-bold m-0">All Suggested Courses</h2>
    </div>

    @if($suggestedCourses->isEmpty())
        <p class="text-gray-500">No courses available at the moment.</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($suggestedCourses as $course)
            <div class="bg-gray-50 rounded-2xl shadow flex overflow-hidden h-56">
                <div class="w-1/2 h-full">
                    <img src="{{ $course->activeCoverPhoto ? asset('storage/' . $course->activeCoverPhoto->path) :  asset('storage/implementor/course/thumbnail.jpg')  }}"
                         alt="{{ $course->course_title }}" class="w-full h-full object-cover">
                </div>
                <div class="p-4 flex flex-col justify-between w-1/2 h-full">
                    <div>
                        <p class="text-xs text-gray-400">1st Sem SY 2024-2025</p>
                        <p class="text-sm text-gray-500">{{ $course->subject ?? '' }}</p>
                        <h3 class="text-lg font-bold">{{ $course->course_title }}</h3>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-3">{{ $course->background ?? '' }}</p>
                    </div>

                    <div class="flex justify-end mt-2" x-data="{ open: false }">
                        <button @click="open = true" 
                                class="bg-black text-white px-4 py-1 rounded-full hover:bg-gray-800">
                            Join Class
                        </button>

                        <!-- Modal -->
                        <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                            <div @click.away="open = false" class="bg-white rounded-xl p-6 w-96">
                                <h3 class="text-lg font-semibold mb-4">Confirm Enrollment</h3>
                                <p class="text-gray-600 mb-6">
                                    Are you sure you want to enroll in <strong>{{ $course->course_title }}</strong>?
                                </p>
                                <div class="flex justify-end gap-3">
                                    <button @click="open = false" class="px-4 py-1 rounded-full border hover:bg-gray-100">Cancel</button>
                                    
                                    <form method="POST" action="{{ route('learner.course.enroll', $course) }}">
                                        @csrf
                                        <button type="submit" class="px-4 py-1 rounded-full bg-black text-white hover:bg-gray-800">
                                            Confirm
                                        </button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('swal'))
<script>
    const swalData = @json(session('swal'));
    Swal.fire({
        icon: swalData.icon,
        title: swalData.title,
        text: swalData.text,
        confirmButtonColor: '#111827'
    });
</script>
@endif

@endsection


