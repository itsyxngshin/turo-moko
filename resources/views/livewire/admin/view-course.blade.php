@extends('layouts.layout')

@section('title', 'Course Information')
@section('page-title', 'Course Information')

@section('content')
<div class="p-0">
    <!-- Header -->
    <div class="relative h-56 bg-cover bg-center rounded-lg overflow-hidden" 
        style="background-image: url('https://images.unsplash.com/photo-1608506573186-631f3ff1f6e3');">

        @if ($course->activeCoverPhoto)
            <img 
                src="{{ asset('storage/' . $course->activeCoverPhoto->path) }}" 
                alt="Course Cover" 
                class="w-full h-full object-cover"
            >
        @endif

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col px-8 text-white rounded-lg">
            <h1 class="text-3xl font-bold mt-auto mb-1">{{ $course->name ?? '--' }}</h1>
            <p class="max-w-2xl mb-2">{{ $course->background ?? '--' }}</p>

            <p class="text-sm text-gray-300 mb-8">
                {{ $course->enrollees->count() }}/{{ $course->student_limit}} 
                {{ Str::plural('Student', $course->enrollees->count()) }} Enrolled
            </p>
        </div>
    </div>
</div>

<!-- Course Intro -->
<div class="px-9 py-6 rounded-lg my-6 border shadow-sm bg-white">
    <div class="relative text-center">
        <h2 class="text-[30px] font-bold mb-2">Course Introduction</h2>
    </div>

    <!-- Modules -->
    <div class="space-y-4 mt-6">
       @foreach ($lessons as $lesson)
           <h3 class="font-bold text-lg mb-2">Lesson: {{ $lesson->title }}</h3>

           @foreach ($lesson->modules as $module)
               <div class="bg-white w-full py-4 rounded-lg shadow-sm border p-4 flex mb-2">
                   <span>Module: {{ $module->module_title }}</span>
               </div>
           @endforeach
       @endforeach

        @foreach ($assignments as $assignment)
            <div class="bg-white w-full flex rounded-lg justify-between shadow-sm border p-4 mb-6">
                <div class="flex items-center px-5 gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                        <path fill="#0094f0" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/>
                    </svg>
                    <span>{{ $assignment->title ?? '' }}: {{ $assignment->module->module_title ?? '' }}</span>
                </div>
                <div class="flex items-center px-5 gap-2">
                    <span class="text-sm text-gray-500">Due {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d') }}</span>
                </div>
            </div>
        @endforeach

        @foreach ($evaluations as $evaluation)
            <div class="bg-white w-full flex py-10 rounded-lg justify-between shadow-sm border p-4 mb-6">
                <div class="flex items-center px-5 gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22 16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4c0-1.11.89-2 2-2h12a2 2 0 0 1 2 2z"/>
                    </svg>
                    <span>{{ $evaluation->title }}</span>
                </div>
                <div class="flex items-center px-5 gap-2">
                    <span class="text-sm text-gray-500">
                        Due {{ \Carbon\Carbon::parse($evaluation->due_date)->format('M d') }}
                    </span>
                </div>
            </div>
        @endforeach

        <!-- Announcements -->
        <!-- Announcements -->
@foreach($announcements as $announcement)
<div x-data="{ open: false }" x-cloak  x-init="open = false" class="mb-4">

    <!-- Clickable announcement -->
    <div class="bg-white w-full flex items-start p-4 rounded-lg shadow hover:bg-gray-50 cursor-pointer"
         @click="open = true">
        <div class="m-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 ml-5 mr-10 text-blue-500 mb-2" viewBox="0 0 48 48" fill="currentColor">
                <path fill-rule="evenodd" d="M33 18.535c1.163.348 2 .465 2 .465v-3h2a5 5 0 0 0 0-10h-5.764A5.236 5.236 0 0 0 26 11.236c0 4.518 4.348 6.506 7 7.299M40 11a3 3 0 0 1-3 3h-4v2.435a13 13 0 0 1-1.603-.667C29.414 14.774 28 13.36 28 11.236A3.236 3.236 0 0 1 31.236 8H37a3 3 0 0 1 3 3"/>
            </svg>
        </div>

        <div class="flex-1 text-left">
            <h3 class="font-semibold">{{ $announcement->title }}</h3>
            <span class="text-sm text-gray-400">{{ $announcement->created_at->diffForHumans() }}</span>
        </div>

        <div class="m-auto">
            <span class="text-sm text-gray-400">{{ $announcement->created_at->format('F j, Y') }}</span>
        </div>
    </div>

    <!-- Modal -->
    <div x-show="open" 
         x-transition
         @click.outside="open = false"
         x-cloak
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg w-11/12 md:w-2/3 max-h-[90vh] overflow-auto p-6 relative">
            <!-- User Info & Close -->
            <div class="flex items-center border-b pb-2 relative">
                <img 
                    src="{{ $announcement->user && $announcement->user->profile_picture 
                            ? asset('storage/' . $announcement->user->profile_picture) 
                            : asset('implementor/course/thumbnail.png') }}" 
                    alt="User Profile" class="w-10 h-10 rounded-full mt-1"
                />
                <h3 class="my-auto ml-2">{{ $announcement->user->name ?? '--' }}</h3>

                <button @click="open = false" class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 text-xl">&times;</button>
            </div>

            <!-- Content -->
            <h2 class="text-xl font-bold mt-4 mb-4">{{ $announcement->title }}</h2>
            @php
                $formatted = preg_replace(
                    '/(https?:\/\/[^\s]+)/',
                    '<a href="$1" class="text-blue-500 underline" target="_blank" rel="noopener noreferrer">$1</a>',
                    e($announcement->content)
                );
            @endphp
            <div class="prose">{!! nl2br($formatted) !!}</div>

            <!-- Attachments -->
            @if($announcement->attachments->count() > 0)
            <div class="flex flex-wrap gap-3 mt-4">
                @foreach($announcement->attachments as $attachment)
                    @php
                        $ext = strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION));
                        $fileUrl = asset('storage/' . $attachment->file_path);
                    @endphp
                    <a href="{{ $fileUrl }}" target="_blank" class="flex flex-col items-center justify-center w-28 border rounded-md p-2 hover:bg-gray-100">
                        @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                            <img src="{{ $fileUrl }}" class="w-24 h-24 object-cover rounded-md border mb-1">
                        @elseif($ext === 'pdf')
                            <canvas class="w-24 h-24 mb-1 pdf-thumbnail" data-pdf="{{ $fileUrl }}"></canvas>
                        @elseif(in_array($ext, ['mp4','webm','mov','avi']))
                            <video class="w-24 h-24 object-cover rounded-md border mb-1" muted>
                                <source src="{{ $fileUrl }}" type="video/{{ $ext }}">
                            </video>
                        @else
                            <div class="flex flex-col items-center justify-center w-24 h-24 border rounded-md p-2 mb-1 text-center">
                                <span class="text-4xl">📄</span>
                                <p class="text-xs mt-1">{{ strtoupper($ext) }}</p>
                            </div>
                        @endif
                        <p class="text-xs text-center truncate w-full">
                            {{ $attachment->original_name ?? basename($attachment->file_path) }}
                        </p>
                    </a>
                @endforeach
            </div>
            @endif
            <span class="text-sm text-gray-400 mt-2 block">{{ $announcement->created_at->diffForHumans() }}</span>
        </div>
    </div>
</div>
@endforeach

<!-- PDF.js Script -->
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
