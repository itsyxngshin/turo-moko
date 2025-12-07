

@section('title', $course->course_title)
@section('content')
<div class="p-0 relative">

    <!-- Back Button -->
    <div class="absolute top-4 left-4 z-50">
        <a href="{{ route('admin.courses') }}" 
           class="flex items-center px-3 py-2 bg-white rounded-full shadow hover:bg-gray-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back
        </a>
    </div>

    <!-- Header -->
    <div class="relative h-56 bg-cover bg-center rounded-lg overflow-hidden"
         style="background-image: url('{{ $course->activeCoverPhoto ? asset('storage/' . $course->activeCoverPhoto->path) : asset('implementor/thumbnail.jpg') }}');">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50 flex flex-col px-8 text-white rounded-lg">
            <h1 class="text-3xl font-bold mt-auto mb-1">{{ $course->course_title ?? '--' }}</h1>
            <p class="max-w-2xl mb-2">{{ $course->background ?? '--' }}</p>
            <p class="text-sm text-gray-300 mb-8">
                {{ $course->enrollees->count() }}/{{ $course->student_limit }} 
                {{ Str::plural('Student', $course->enrollees->count()) }} Enrolled
            </p>
        </div>
    </div>

    <!-- Course Introduction -->
    <div class="px-9 py-6 rounded-lg my-6 border shadow-sm bg-white">

        <div class="text-center">
            <h2 class="text-[30px] font-bold mb-2">Course Introduction</h2>
            <p>{{ $course->description ?? '--' }}</p>
        </div>

        <!-- Modules -->
        <div class="space-y-4 mt-6">
            @foreach ($modules as $module)
               <div class="bg-white w-full flex justify-between items-center p-4 rounded-lg shadow hover:bg-gray-50 cursor-pointer">
    <!-- Left side: icon + module info -->
    <div class="flex items-center space-x-4">
        <!-- SVG Icon -->
        <div class="flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="#ef5350">
                <path d="M13 9h5.5L13 3.5zM6 2h8l6 6v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2m4.93 10.44c.41.9.93 1.64 1.53 2.15l.41.32c-.87.16-2.07.44-3.34.93l-.11.04l.5-1.04c.45-.87.78-1.66 1.01-2.4m6.48 3.81c.18-.18.27-.41.28-.66c.03-.2-.02-.39-.12-.55c-.29-.47-1.04-.69-2.28-.69l-1.29.07l-.87-.58c-.63-.52-1.2-1.43-1.6-2.56l.04-.14c.33-1.33.64-2.94-.02-3.6a.85.85 0 0 0-.61-.24h-.24c-.37 0-.7.39-.79.77c-.37 1.33-.15 2.06.22 3.27v.01c-.25.88-.57 1.9-1.08 2.93l-.96 1.8l-.89.49c-1.2.75-1.77 1.59-1.88 2.12c-.04.19-.02.36.05.54l.03.05l.48.31l.44.11c.81 0 1.73-.95 2.97-3.07l.18-.07c1.03-.33 2.31-.56 4.03-.75c1.03.51 2.24.74 3 .74c.44 0 .74-.11.91-.3m-.41-.71l.09.11c-.01.1-.04.11-.09.13h-.04l-.19.02c-.46 0-1.17-.19-1.9-.51c.09-.1.13-.1.23-.1c1.4 0 1.8.25 1.9.35M7.83 17c-.65 1.19-1.24 1.85-1.69 2c.05-.38.5-1.04 1.21-1.69zm3.02-6.91c-.23-.9-.24-1.63-.07-2.05l.07-.12l.15.05c.17.24.19.56.09 1.1l-.03.16l-.16.82z"/>
            </svg>
        </div>

        <!-- Module details -->
        <div class="flex flex-col">
            <h3 class="font-semibold text-lg">
                Module {{ $module->module_number }}: {{ $module->module_title }}
            </h3>
            <span class="text-sm text-gray-500">
                {{ $module->created_at->diffForHumans() }}
            </span>
        </div>
    </div>

    <!-- Right side: date + status -->
    <div class="flex flex-col items-end space-y-1">
        <span class="text-sm text-gray-400">{{ $module->created_at->format('F j, Y') }}</span>
       @php
            $statusText = [
                'pending' => ['text' => 'Pending', 'color' => 'text-gray-500'],
                'approved' => ['text' => 'Approved', 'color' => 'text-green-500'],
                'revision_required' => ['text' => 'Revision Required', 'color' => 'text-red-500'],
            ];

            $status = $module->status ?? 'pending';
        @endphp

        <span class="text-sm {{ $statusText[$status]['color'] ?? 'text-gray-500' }}">
            {{ $statusText[$status]['text'] ?? 'Pending' }}
        </span>

    </div>
</div>


            @endforeach
        </div>

        <!-- Evaluations -->
        @if($evaluations->count())
            <div class="mt-8 space-y-4">
                <h2 class="text-xl font-bold mb-2">Evaluations</h2>
                @foreach ($evaluations as $evaluation)
                    <div class="bg-white w-full flex justify-between items-center p-4 rounded-lg shadow-sm border">
                        <span>{{ $evaluation->title }}</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-gray-500">
                                Due {{ \Carbon\Carbon::parse($evaluation->due_date)->format('M d') }}
                            </span>
                            <span class="text-sm {{ $evaluation->status === 'completed' ? 'text-green-500' : ($evaluation->status === 'missing' ? 'text-red-500' : 'text-gray-300') }}">
                                {{ ucfirst($evaluation->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Announcements -->
        @if($announcements->count())
            <div class="mt-8 space-y-4">
                <h2 class="text-xl font-bold mb-2">Announcements</h2>
                @foreach($announcements as $announcement)
                    <div class="bg-white w-full flex flex-col p-4 rounded-lg shadow border">
                        <div class="flex justify-between mb-2">
                            <h3 class="font-semibold">{{ $announcement->title }}</h3>
                            <span class="text-sm text-gray-400">{{ $announcement->created_at->format('F j, Y') }}</span>
                        </div>
                        <p class="text-gray-700 mb-2">{!! nl2br(e($announcement->content)) !!}</p>

                        @if($announcement->attachments->count())
                            <div class="flex flex-wrap gap-3 mt-2">
                                @foreach($announcement->attachments as $attachment)
                                    @php
                                        $ext = strtolower(pathinfo($attachment->file_path, PATHINFO_EXTENSION));
                                        $fileUrl = asset('storage/' . $attachment->file_path);
                                        $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                        $isPDF = $ext === 'pdf';
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank" class="flex flex-col items-center justify-center w-28 border rounded-md p-2 hover:bg-gray-100">
                                        @if($isImage)
                                            <img src="{{ $fileUrl }}" class="w-24 h-24 object-cover rounded-md border mb-1">
                                        @elseif($isPDF)
                                            📄 PDF Attachment
                                        @else
                                            📄 File
                                        @endif
                                        <span class="text-xs text-center truncate w-full">
                                            {{ $attachment->original_name ?? basename($attachment->file_path) }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
