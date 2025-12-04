@extends('layouts.layout')

@section('title', 'Course Information')
@section('page-title', 'Course Information')

@section('content')
        <div class="p-0">
        <!-- Header -->
                <div class="relative h-56 bg-cover bg-center rounded-lg overflow-hidden" 
            style="background-image: url('https://images.unsplash.com/photo-1608506573186-631f3ff1f6e3');">

            <!-- Course Cover -->
            @if($course->activeCoverPhoto)
                 <img 
                    src="{{ $course->activeCoverPhoto 
                            ? asset('storage/' . $course->activeCoverPhoto->path) 
                            : asset('implementor/thumbnail.jpg') }}" 
                    alt="Course Cover"
                    class="w-full h-full object-cover">

                @else
                    <img src="{{ asset('storage/implementor/course/thumbnail.jpg') }}"
                        alt="Default Cover"
                        class="w-full h-full object-cover">
                @endif

            <!-- Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col px-8 text-white rounded-lg">
                <h1 class="text-3xl font-bold mt-auto mb-1">{{ $course->name ?? '--' }}</h1>
                <p class="max-w-2xl mb-2">{{ $course->background ?? '--' }}</p>
                 <!-- Student count -->
                <p class="text-sm text-gray-300 mb-8">
                    {{ $course->enrollees->count() }}/{{ $course->student_limit}} {{ Str::plural('Student', $course->enrollees->count()) }} Enrolled
                </p>
                {{-- Implementor editing controls intentionally omitted for learners --}}
            </div>
        </div>
            
        </div>

        <!-- Course Intro -->
        <div class="px-9 py-6 rounded-lg my-6 border shadow-sm bg-white">
            <div class="relative">
                <!-- Centered Title + Description -->
                <div class="text-center">
                    <h2 class="text-[30px] font-bold mb-2">Course Introduction</h2>
                    
                </div>
            </div>
            

            <!-- Module -->
            <div class="space-y-4">
                @foreach ($modules as $module)
<div x-data="{ open: false }" x-cloak x-transition class="mb-4">

    <!-- Clickable Module Card -->
    <button 
        class="bg-white w-full flex items-start p-4 rounded-lg shadow hover:bg-gray-50 cursor-pointer"
        @click="open = true"
    >
        <div class="m-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" class="ml-5 mr-10 mb-2" viewBox="0 0 24 24" fill="#ef5350">
                <path d="M13 9h5.5L13 3.5zM6 2h8l6 6v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2m4.93 10.44c.41.9.93 1.64 1.53 2.15l.41.32c-.87.16-2.07.44-3.34.93l-.11.04l.5-1.04c.45-.87.78-1.66 1.01-2.4m6.48 3.81c.18-.18.27-.41.28-.66c.03-.2-.02-.39-.12-.55c-.29-.47-1.04-.69-2.28-.69l-1.29.07l-.87-.58c-.63-.52-1.2-1.43-1.6-2.56l.04-.14c.33-1.33.64-2.94-.02-3.6a.85.85 0 0 0-.61-.24h-.24c-.37 0-.7.39-.79.77c-.37 1.33-.15 2.06.22 3.27v.01c-.25.88-.57 1.9-1.08 2.93l-.96 1.8l-.89.49c-1.2.75-1.77 1.59-1.88 2.12c-.04.19-.02.36.05.54l.03.05l.48.31l.44.11c.81 0 1.73-.95 2.97-3.07l.18-.07c1.03-.33 2.31-.56 4.03-.75c1.03.51 2.24.74 3 .74c.44 0 .74-.11.91-.3m-.41-.71l.09.11c-.01.1-.04.11-.09.13h-.04l-.19.02c-.46 0-1.17-.19-1.9-.51c.09-.1.13-.1.23-.1c1.4 0 1.8.25 1.9.35M7.83 17c-.65 1.19-1.24 1.85-1.69 2c.05-.38.5-1.04 1.21-1.69zm3.02-6.91c-.23-.9-.24-1.63-.07-2.05l.07-.12l.15.05c.17.24.19.56.09 1.1l-.03.16l-.16.82z"/>
            </svg>
        </div>
        <div class="flex-1 text-left">
            <h3 class="font-semibold">Module  {{$module->module_number}}: {{ $module->module_title }}</h3>
            <span class="text-sm text-gray-400">{{ $module->created_at->diffForHumans() }}</span>
        </div>
        <div class="m-auto">
            <span class="text-sm text-gray-400">{{ $module->created_at->format('F j, Y') }}</span>
        </div>
        
    </button>

    <!-- Module Modal -->
    <div 
    
        x-show="open" 
        x-transition
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
    >
        <div class="bg-white rounded-lg w-11/12 md:w-2/3 max-h-[90vh] overflow-auto p-6 relative">

            <!-- Modal Header -->
            <div class="flex justify-between items-center border-b pb-2">
                <h2 class="text-xl font-bold">Module {{$module->module_number}}: {{ $module->module_title }}</h2>
            
          <button @click="open = false" class="absolute top-6 right-7 text-gray-600 hover:text-gray-800 text-xl">&times;</button>
               
                
               
                
            </div>

            <!-- Modal Content -->
            <div class="mt-0 space-y-4">
                <!-- Lesson -->
                @if($module->lessons) <!-- single lesson object -->
                    <div class="p-3 mt-0">
                        <!-- Lesson Content -->
                        <p class="text-md text-gray-600 mb-4">
                            {{ $module->lessons->content ?? 'No content available' }}
                        </p>

                        <!-- Attachment -->
                        @if($module->lessons->attachments) <!-- directly access string -->
                            @php
                                $attachment = $module->lessons->attachments;
                                $ext = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                                $fileUrl = asset('storage/' . $attachment);
                                $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                $isPDF = $ext === 'pdf';
                                $isVideo = in_array($ext, ['mp4','webm','mov','avi']);
                                $isWord = in_array($ext, ['doc','docx']);
                                $isExcel = in_array($ext, ['xls','xlsx']);
                            @endphp

                            <a href="{{ $fileUrl }}" target="_blank" class="flex flex-col items-center justify-center w-40 h-full border rounded-md p-2 hover:bg-gray-100">
                                @if($isImage)
                                    <img src="{{ $fileUrl }}" class="w-24 h-24 object-cover rounded-md border mb-1">
                                @elseif($isPDF)
                                    <canvas class="w-24 h-24 mb-1 pdf-thumbnail" data-pdf="{{ $fileUrl }}"></canvas>
                                @elseif($isVideo)
                                    <video class="w-24 h-24 object-cover rounded-md border mb-1" muted>
                                        <source src="{{ $fileUrl }}" type="video/{{ $ext }}">
                                    </video>
                                @elseif($isWord)
                                    <div class="flex flex-col items-center justify-center w-24 h-24 border rounded-md p-2 mb-1 text-center">
                                        <span class="text-4xl">📄</span>
                                        <p class="text-xs mt-1">Word</p>
                                    </div>
                                @elseif($isExcel)
                                    <div class="flex flex-col items-center justify-center w-24 h-24 border rounded-md p-2 mb-1 text-center">
                                        <span class="text-4xl">📊</span>
                                        <p class="text-xs mt-1">Excel</p>
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center w-24 h-24 border rounded-md p-2 mb-1 text-center">
                                        <span class="text-4xl">📄</span>
                                        <p class="text-xs mt-1">File</p>
                                    </div>
                                @endif

<p class="text-xs text-center truncate w-full">
    {{ $module->lessons->attachments_original_name ?? '-' }}
</p>
                            </a>
                    
                        @endif
                    </div>
                @else
                    <p class="text-gray-400 mt-4">No lesson available for this module.</p>
                @endif

                <span class="text-sm text-gray-400">{{ $module->created_at->diffForHumans() }}</span>
                </div>

                        </div>
                    </div>

                </div>
                @endforeach

                {{-- Assignments Section --}}
                @foreach ($assignments as $assignment)
                <a href="#" 
                   class="bg-white w-full flex py-10 rounded-lg justify-between shadow-sm border p-4 mb-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center px-5 gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                            <g fill="none">
                                <path fill="url(#assignment-gradient1-learner)" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/>
                                <path fill="url(#assignment-gradient2-learner)" d="M8 4.25a2.25 2.25 0 0 0 2.25 2.25h3.5a2.25 2.25 0 0 0 0-4.5h-3.5A2.25 2.25 0 0 0 8 4.25"/>
                                <path fill="url(#assignment-gradient3-learner)" fill-opacity="0.9" d="M17.03 11.03a.75.75 0 1 0-1.06-1.06L11 14.94l-1.97-1.97a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 0 0 1.06 0z"/>
                                <defs>
                                    <linearGradient id="assignment-gradient1-learner" x1="4" x2="18.146" y1="5.8" y2="23.483" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#36dff1"/>
                                        <stop offset="1" stop-color="#0094f0"/>
                                    </linearGradient>
                                    <linearGradient id="assignment-gradient2-learner" x1="12" x2="12" y1="2" y2="6.5" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#ffe06b"/>
                                        <stop offset="1" stop-color="#fab500"/>
                                    </linearGradient>
                                    <linearGradient id="assignment-gradient3-learner" x1="18" x2="10.265" y1="18.5" y2="7.732" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#9deaff"/>
                                        <stop offset="1" stop-color="#fff"/>
                                    </linearGradient>
                                </defs>
                            </g>
                        </svg>
                        <div>
                            <span class="font-medium">{{ $assignment->title }}</span>
                            <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $assignment->status === 'Open' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $assignment->status }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center px-5 gap-4">
                        @if($assignment->end_date)
                        <span class="text-sm text-gray-500">
                            Due {{ \Carbon\Carbon::parse($assignment->end_date)->format('M d') }}
                        </span>
                        @endif
                    </div>
                </a>
                @endforeach

                {{-- Assessments/Quizzes Section --}}
                @foreach ($quiz as $assessment)
                <a href="{{ route('learner.assessment.show', $assessment) }}" 
                   class="bg-white w-full flex py-10 rounded-lg justify-between shadow-sm border p-4 mb-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center px-5 gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 48 48">
                            <g fill="#F44336">
                                <path d="M20 18.6L17.75 24h4.5z"/>
                                <path fill-rule="evenodd" d="M38 15L28 4H14a4 4 0 0 0-4 4v32a4 4 0 0 0 4 4h20a4 4 0 0 0 4-4zm-18 0a1 1 0 0 1 .923.615l5 12a1 1 0 0 1-1.846.77L23.083 26h-6.166l-.994 2.385a1 1 0 0 1-1.846-.77l5-12A1 1 0 0 1 20 15m-5 17a1 1 0 1 0 0 2h18a1 1 0 1 0 0-2zm-1 5a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H15a1 1 0 0 1-1-1m17-15a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2zM28 7l7 8h-6a1 1 0 0 1-1-1z" clip-rule="evenodd"/>
                            </g>
                        </svg>
                        <div>
                            <span class="font-medium">{{ $assessment->quiz_title }}</span>
                            @if(($assessment->learner_status ?? 'Available') === 'Completed')
                                <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                    Completed
                                </span>
                            @else
                                <span class="ml-2 px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    Available
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center px-5 gap-4">
                        <span class="text-sm text-gray-500">
                            {{ $assessment->results_count ?? 0 }} {{ Str::plural('submission', $assessment->results_count ?? 0) }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Due {{ \Carbon\Carbon::parse($assessment->end_date)->format('M d') }}
                        </span>
                    </div>
                </a>
                @endforeach
               
                @foreach ($evaluations as $evaluation)
                <button class="bg-white w-full flex py-10 rounded-lg justify-between shadow-sm border p-4 mb-6">
                    <div class="flex items-center px-5 gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22 16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4c0-1.11.89-2 2-2h12a2 2 0 0 1 2 2zm-6 4v2H4a2 2 0 0 1-2-2V7h2v13zm-3-6l7-7l-1.41-1.41L13 11.17L9.91 8.09L8.5 9.5z"/>
                        </svg>
                        <span>{{ $evaluation->title }}</span>
                    </div>
                    <div class="flex items-center px-5 gap-2">
                        <span class="text-sm text-gray-500">
                            Due {{ \Carbon\Carbon::parse($evaluation->due_date)->format('M d') }}
                        </span>
                        <i class="fas fa-check-circle 
                        {{ $evaluation->status === 'completed' ? 'text-green-500' : ($evaluation->status === 'missing' ? 'text-red-500' : 'text-gray-300') }}">
                        </i>
                    </div>
                </button>
                @endforeach


                @foreach($announcements as $announcement)
                <div x-data="{ open:false }"
                    x-transition
                    x-cloak
                    class="mb-4">
                    <!-- Clickable announcement -->
                    <button 
                        class="bg-white w-full flex items-start p-4 rounded-lg shadow hover:bg-gray-50 cursor-pointer"
                        @click="open = true"
                    >
                        <div class=" m-auto">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 ml-5 mr-10 text-blue-500 mb-2" viewBox="0 0 48 48" fill="currentColor"> <path fill-rule="evenodd" d="M33 18.535c1.163.348 2 .465 2 .465v-3h2a5 5 0 0 0 0-10h-5.764A5.236 5.236 0 0 0 26 11.236c0 4.518 4.348 6.506 7 7.299M40 11a3 3 0 0 1-3 3h-4v2.435a13 13 0 0 1-1.603-.667C29.414 14.774 28 13.36 28 11.236A3.236 3.236 0 0 1 31.236 8H37a3 3 0 0 1 3 3m-25.183 6.993A4.998 4.998 0 0 1 14.998 8h3.169A4.833 4.833 0 0 1 23 12.833c0 4.042-3.63 5.89-6 6.667c-1.148.376-2 .5-2 .5v-2zM17 16.071l-2.11-.076A2.998 2.998 0 0 1 14.997 10h3.169A2.833 2.833 0 0 1 21 12.833c0 1.915-1.217 3.17-2.924 4.06c-.36.188-.725.348-1.076.484zM28 24c0 2.21-1.79 4-4 4s-4-1.79-4-4s1.79-4 4-4s4 1.79 4 4m-2 0a1.999 1.999 0 1 1-4 0a1.999 1.999 0 1 1 4 0m-7 2c0 2.21-1.79 4-4 4s-4-1.79-4-4s1.79-4 4-4s4 1.79 4 4m-2 0a1.999 1.999 0 1 1-4 0a1.999 1.999 0 1 1 4 0M6 36.546C6 33.522 11.996 32 15 32c.585 0 1.284.058 2.03.173C18.371 31.19 20.827 30 24 30s5.629 1.19 6.971 2.173A13.6 13.6 0 0 1 33 32c3.004 0 9 1.523 9 4.545V42H6zm15.652-.523c.348.324.348.493.348.522V40H8v-3.455c0-.03 0-.198.348-.522c.363-.339.962-.7 1.776-1.03C11.756 34.333 13.75 34 15 34s3.244.333 4.876.993c.814.33 1.413.691 1.776 1.03m6.49-3.167A10.4 10.4 0 0 0 24 32c-1.656 0-3.064.386-4.141.856C22.074 33.6 24 34.832 24 36.546c0-1.714 1.926-2.945 4.142-3.69M40 36.546c0-.03 0-.199-.348-.523c-.363-.339-.962-.7-1.776-1.03C36.244 34.333 34.25 34 33 34s-3.244.333-4.876.993c-.814.33-1.413.691-1.776 1.03c-.348.324-.348.493-.348.522V40h14zM33 30c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m0-2a1.999 1.999 0 1 0 0-4a1.999 1.999 0 1 0 0 4" clip-rule="evenodd"/> </svg>

                        </div>
                        
                        <div class="flex-1 text-left">
                            <h3 class="font-semibold">{{ $announcement->title }}</h3>
                            <span class="text-sm text-gray-400">{{ $announcement->created_at->diffForHumans() }}</span>
                            
                        </div>
                        <div class="m-auto">
                             <span class="text-sm text-gray-400">{{ $announcement->created_at->format('F j, Y') }}</span>
                        </div>
                </button>

                    <!-- Modal -->
                    <div 
                        x-show="open" 
                        x-transition
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
                    >
                        <div class="bg-white rounded-lg w-11/12 md:w-2/3 max-h-[90vh] overflow-auto p-6 relative">
                            
                        
                            <div class="flex border-b pb-2">
                              <img 
                                    src="{{ $announcement->user?->photo?->photos
                                            ? asset('storage/' . $announcement->user->photo->photos)
                                            : asset('implementor/course/thumbnail.png') }}"
                                    class="w-10 h-10 rounded-full mt-1"
                                />


                                 <!--User Name-->
                                <h3 class="my-auto ml-2 ">{{ $announcement->user->profile->first_name ?? '--' }}</h3>
                                <!-- Close Button -->
                                <button @click="open = false" class="absolute top-8 right-7 text-gray-600 hover:text-gray-800 text-xl">&times;</button>
                        
                            </div>





                        <h2 class="text-xl font-bold mt-4 mb-4">{{ $announcement->title }}</h2>
                        @php
                            $formatted = preg_replace(
                                '/(https?:\/\/[^\s]+)/',
                                '<a href="$1" class="text-blue-500 underline" target="_blank" rel="noopener noreferrer">$1</a>',
                                e($announcement->content)
                            );
                        @endphp

                        <div class="prose">
                            {!! nl2br($formatted) !!}
                        </div>

                        <!-- Attachments -->
                        @if($announcement->attachments->count() > 0)
                        <div class="flex flex-wrap gap-3">
                            @foreach($announcement->attachments as $attachment)
                                @php
                                    $ext = strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                    $isPDF   = $ext === 'pdf';
                                    $isVideo = in_array($ext, ['mp4','webm','mov','avi']);
                                    $isWord  = in_array($ext, ['doc','docx']);
                                    $isExcel = in_array($ext, ['xls','xlsx']);
                                    $fileUrl = asset('storage/' . $attachment->file_path);
                                @endphp

                                <a href="{{ $fileUrl }}" target="_blank" class="flex flex-col items-center justify-center w-28 border rounded-md p-2 hover:bg-gray-100">
                                    @if($isImage)
                                        <img src="{{ $fileUrl }}" 
                                            alt="Attachment" 
                                            class="w-24 h-24 object-cover rounded-md border mb-1">
                                    @elseif($isPDF)
                                        <canvas class="w-24 h-24 mb-1 pdf-thumbnail" data-pdf="{{ $fileUrl }}"></canvas>
                                    @elseif($isVideo)
                                        <video class="w-24 h-24 object-cover rounded-md border mb-1" muted>
                                            <source src="{{ $fileUrl }}" type="video/{{ $ext }}">
                                        </video>
                                    @elseif($isWord)
                                        <div class="flex flex-col items-center justify-center w-24 h-24 border rounded-md p-2 mb-1 text-center">
                                            <span class="text-4xl">📄</span>
                                            <p class="text-xs mt-1">Word</p>
                                        </div>
                                    @elseif($isExcel)
                                        <div class="flex flex-col items-center justify-center w-24 h-24 border rounded-md p-2 mb-1 text-center">
                                            <span class="text-4xl">📊</span>
                                            <p class="text-xs mt-1">Excel</p>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center justify-center w-24 h-24 border rounded-md p-2 mb-1 text-center">
                                            <span class="text-4xl">📄</span>
                                            <p class="text-xs mt-1">File</p>
                                        </div>
                                    @endif

                                    <p class="text-xs text-center truncate w-full">
                                        {{ $attachment->original_name ?? basename($attachment->file_path) }}
                                    </p>
                                </a>
                            @endforeach

                        
                        </div>
                        <span class="text-sm text-gray-400">{{ $announcement->created_at->diffForHumans() }}</span>
                        @endif
            
            
                    </div>
                        
                        
        
        
    </div>
</div>
@endforeach


                    



            </div>
        </div>
    
  
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.7.107/pdf.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const canvases = document.querySelectorAll(".pdf-thumbnail");
    canvases.forEach(canvas => {
        const url = canvas.dataset.pdf;
        const ctx = canvas.getContext("2d");

        pdfjsLib.getDocument(url).promise.then(pdfDoc => {
            pdfDoc.getPage(1).then(page => {
                const viewport = page.getViewport({ scale: 0.25 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                page.render({ canvasContext: ctx, viewport: viewport });
            });
        }).catch(err => console.error("PDF render error:", err));
    });
});
</script>

@endsection
