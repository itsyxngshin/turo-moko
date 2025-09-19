<div x-data="{ open: false }">
    <!-- Button to Open Modal -->
    <button @click="open = true" class="px-6 py-2 bg-black text-white rounded-full">
        Add Activity
    </button>

    <!-- Modal -->
    <div x-show="open" 
         x-transition 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-[500px] p-6 relative">
            
            <!-- Close Button -->
            <button @click="open = false" 
                    class="absolute top-3 right-3 text-gray-500 hover:text-black text-xl">
                &times;
            </button>
            
            <!-- Title -->
            <h2 class="text-lg font-bold mb-4">Add an activity or resource</h2>

            <!-- Grid Options -->
            <div class="grid grid-cols-3 gap-6">
                <div class="flex flex-col items-center cursor-pointer hover:scale-105 transition">
                    <img src="/icons/forum.png" alt="Forum" class="w-12 h-12 mb-2">
                    <span class="text-sm font-medium">Forum</span>
                </div>

                <div class="flex flex-col items-center cursor-pointer hover:scale-105 transition">
                    <img src="/icons/assignment.png" alt="Assignment" class="w-12 h-12 mb-2">
                    <span class="text-sm font-medium">Assignment</span>
                </div>

                <div class="flex flex-col items-center cursor-pointer hover:scale-105 transition">
                    <img src="/icons/quiz.png" alt="Quiz" class="w-12 h-12 mb-2">
                    <span class="text-sm font-medium">Quiz/Exam</span>
                </div>

                <div class="flex flex-col items-center cursor-pointer hover:scale-105 transition">
                    <img src="/icons/evaluation.png" alt="Evaluation" class="w-12 h-12 mb-2">
                    <span class="text-sm font-medium">Evaluation</span>
                </div>

                <div class="flex flex-col items-center cursor-pointer hover:scale-105 transition">
                    <img src="/icons/file.png" alt="File" class="w-12 h-12 mb-2">
                    <span class="text-sm font-medium">File</span>
                </div>

                <div class="flex flex-col items-center cursor-pointer hover:scale-105 transition">
                    <img src="/icons/url.png" alt="URL" class="w-12 h-12 mb-2">
                    <span class="text-sm font-medium">URL</span>
                </div>
            </div>
        </div>
    </div>
</div>
