<div x-data="{ open: @entangle('open') }">

    <!-- Trigger Button -->
    <button @click="open = true"
            class="px-3 py-1 border border-gray-500 text-gray-500 rounded">
        View
    </button>

    <!-- Modal -->
    <div x-show="open" x-cloak
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white w-full max-w-3xl rounded-lg p-6 relative shadow-lg">

            <!-- Close Button -->
            <button @click="open = false"
                    class="absolute top-4 right-4 text-gray-600 hover:text-black text-2xl">&times;</button>

            @if($module)
                <h2 class="text-xl font-bold mb-4">
                    Module {{ $module->module_number }}: {{ $module->module_title }}
                </h2>

                @if($lesson)
                    <p class="text-gray-600 mb-4">
                        {{ $lesson->content ?? 'No content available' }}
                    </p>

                    <!-- Attachment -->
                    @if($lesson->attachments)
                        @php
                            $attachment = $lesson->attachments;
                            $ext = strtolower(pathinfo($attachment, PATHINFO_EXTENSION));
                            $fileUrl = asset('storage/' . $attachment);

                            $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                            $isPDF = $ext === 'pdf';
                            $isVideo = in_array($ext, ['mp4','webm','mov','avi']);
                            $isWord = in_array($ext, ['doc','docx']);
                            $isExcel = in_array($ext, ['xls','xlsx']);
                        @endphp

                        <a href="{{ $fileUrl }}" target="_blank"
                           class="flex flex-col items-center justify-center w-40 h-full border rounded-md p-2 hover:bg-gray-100">

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
                                {{ $lesson->attachments_original_name ?? '-' }}
                            </p>
                        </a>
                    @endif

                @else
                    <p class="text-gray-400 mt-4">No lesson available for this module.</p>
                @endif

                <span class="text-sm text-gray-400 mt-2 block">
                    {{ $module->created_at->diffForHumans() }}
                </span>
            @endif

        </div>
    </div>
</div>

<!-- PDF.js rendering -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.177/pdf.min.js"></script>
<script>
    document.querySelectorAll('.pdf-thumbnail').forEach(canvas => {
        const url = canvas.dataset.pdf;
        pdfjsLib.getDocument(url).promise.then(pdf => {
            pdf.getPage(1).then(page => {
                const viewport = page.getViewport({ scale: 0.5 });
                const context = canvas.getContext('2d');
                canvas.width = viewport.width;
                canvas.height = viewport.height;
                page.render({ canvasContext: context, viewport: viewport });
            });
        });
    });
</script>
