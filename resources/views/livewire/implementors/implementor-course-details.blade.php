@extends('layouts.layout')

@section('title', 'Course Information')
@section('page-title', 'Course Information')

@section('content')
<div class="p-0 ml-[20px]">
   <!-- Header -->
        <div class="relative h-56 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1608506573186-631f3ff1f6e3');">
            
            <img src="{{ asset('storage/' . $course->activeCoverPhoto->path) }}" alt="Teacher Avatar" class="w-full h-full rounded-lg object-cover">
            <div class="absolute inset-0 bg-black rounded-lg bg-opacity-50 flex flex-col px-8 text-white">
            <h1 class="text-3xl font-bold mt-auto mb-1">{{ $course->name ?? '--' }}</h1>
            <p class="max-w-2xl mb-4">
                {{ $course->background ?? '--'}}           </p>

            <!-- Bottom-right container -->
            <div class="flex justify-end gap-x-2 mt-auto mb-3">
                 @livewire('modals.implementor.edit-course', ['courseId' => $course->id], key($course->id))
            </div>
        </div>

            
        </div>

        <!-- Course Intro -->
        <div class="px-9 py-6 rounded-lg my-6 border shadow-sm bg-white">
            <div class="relative">
                <!-- + Button pinned top right -->
                <div class="absolute top-0 right-0 flex items-center gap-2">
                    <livewire:modals.implementor.add-resource />
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        width="24" height="24" viewBox="0 0 24 24" 
                        class="cursor-pointer hover:scale-110 transition">
                        <path fill="currentColor" d="M7 12a2 2 0 1 1-4 0a2 2 0 0 1 4 0m7 0a2 2 0 1 1-4 0a2 2 0 0 1 4 0m7 0a2 2 0 1 1-4 0a2 2 0 0 1 4 0"/>
                    </svg>
                </div>


                <!-- Centered Title + Description -->
                <div class="text-center">
                    <h2 class="text-[30px] font-bold mb-2">Course Introduction</h2>
                    
                </div>
            </div>

            <!-- Module -->
            <div class="space-y-4">
                @foreach ($modules as $module)
                    <button class="bg-white w-full py-10 rounded-lg shadow-sm justify-between border p-4 flex">
                        <div class="flex items-center px-5 gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24">
                                <path fill="#ef5350" d="M13 9h5.5L13 3.5zM6 2h8l6 6v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2m4.93 10.44c.41.9.93 1.64 1.53 2.15l.41.32c-.87.16-2.07.44-3.34.93l-.11.04l.5-1.04c.45-.87.78-1.66 1.01-2.4m6.48 3.81c.18-.18.27-.41.28-.66c.03-.2-.02-.39-.12-.55c-.29-.47-1.04-.69-2.28-.69l-1.29.07l-.87-.58c-.63-.52-1.2-1.43-1.6-2.56l.04-.14c.33-1.33.64-2.94-.02-3.6a.85.85 0 0 0-.61-.24h-.24c-.37 0-.7.39-.79.77c-.37 1.33-.15 2.06.22 3.27v.01c-.25.88-.57 1.9-1.08 2.93l-.96 1.8l-.89.49c-1.2.75-1.77 1.59-1.88 2.12c-.04.19-.02.36.05.54l.03.05l.48.31l.44.11c.81 0 1.73-.95 2.97-3.07l.18-.07c1.03-.33 2.31-.56 4.03-.75c1.03.51 2.24.74 3 .74c.44 0 .74-.11.91-.3m-.41-.71l.09.11c-.01.1-.04.11-.09.13h-.04l-.19.02c-.46 0-1.17-.19-1.9-.51c.09-.1.13-.1.23-.1c1.4 0 1.8.25 1.9.35M7.83 17c-.65 1.19-1.24 1.85-1.69 2c.05-.38.5-1.04 1.21-1.69zm3.02-6.91c-.23-.9-.24-1.63-.07-2.05l.07-.12l.15.05c.17.24.19.56.09 1.1l-.03.16l-.16.82z"/>
                            </svg>
                            <span>Module: {{ $module->module_title }}</span>
                        </div>
                        <div class="flex items-center px-5 gap-2">
                            <span class="text-sm text-gray-500"></span>
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                    </button>
                @endforeach


                @foreach ($assignments as $assignment)
                    <button class="bg-white w-full flex rounded-lg justify-between shadow-sm border p-4 mb-6">
                        <div class="flex items-center px-5 gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"> <g> <path fill="url(#grad1-unique)" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/> <path fill="url(#grad2-unique)" fill-opacity="0.7" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/> <path fill="url(#grad3-unique)" fill-opacity="0.4" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/> <path fill="url(#grad4-unique)" d="M8 4.25a2.25 2.25 0 0 0 2.25 2.25h3.5a2.25 2.25 0 0 0 0-4.5h-3.5A2.25 2.25 0 0 0 8 4.25"/> <path fill="url(#grad5-unique)" fill-opacity="0.9" d="M17.03 11.03a.75.75 0 1 0-1.06-1.06L11 14.94l-1.97-1.97a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 0 0 1.06 0z"/> <defs> <linearGradient id="grad1-unique" x1="4" x2="18.146" y1="5.8" y2="23.483" gradientUnits="userSpaceOnUse"> <stop stop-color="#36dff1"/> <stop offset="1" stop-color="#0094f0"/> </linearGradient> <linearGradient id="grad4-unique" x1="12" x2="12" y1="2" y2="6.5" gradientUnits="userSpaceOnUse"> <stop stop-color="#ffe06b"/> <stop offset="1" stop-color="#fab500"/> </linearGradient> <linearGradient id="grad5-unique" x1="18" x2="10.265" y1="18.5" y2="7.732" gradientUnits="userSpaceOnUse"> <stop stop-color="#9deaff"/> <stop offset="1" stop-color="#fff"/> </linearGradient> <radialGradient id="grad2-unique" cx="0" cy="0" r="1" gradientTransform="matrix(0 6.16892 -6.75 0 12 3)" gradientUnits="userSpaceOnUse"> <stop stop-color="#0a1852"/> <stop offset=".9" stop-color="#0a1852" stop-opacity="0"/> </radialGradient> <radialGradient id="grad3-unique" cx="0" cy="0" r="1" gradientTransform="matrix(0 2.79325 -4.95298 0 12 4.618)" gradientUnits="userSpaceOnUse"> <stop stop-color="#0a1852"/> <stop offset="1" stop-color="#0a1852" stop-opacity="0"/> </radialGradient> </defs> </g> </svg>
                            <span>{{ $assignment->title ?? ' '}}: {{ $module->module_title ?? ' '}}</span>
                        </div>
                        <div class="flex items-center px-5 gap-2">
                            <span class="text-sm text-gray-500">Due {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d') }}</span>
                            <i class="fas fa-check-circle {{ $assignment->completed ? 'text-green-500' : 'text-gray-300' }}"></i>
                        </div>
                    </button>
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
                    <div class="bg-white p-4 rounded-lg shadow mb-4">
                        <h3 class="font-semibold">{{ $announcement->title }}</h3>
                        <p class="text-gray-600">{{ $announcement->description }}</p>
                        <span class="text-sm text-gray-400">{{ $announcement->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach

            </div>
        </div>
    
  
</div>
@endsection
