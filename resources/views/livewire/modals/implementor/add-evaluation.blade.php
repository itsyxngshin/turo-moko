<div class="flex-none">
    <button
        wire:click="save"
        wire:loading.attr="disabled"
        class="w-40 h-40 flex flex-col justify-center items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition disabled:opacity-60 disabled:cursor-not-allowed"
    >
        <div class="p-3 rounded-lg mb-2 text-indigo-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="currentColor" d="M22 16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4c0-1.11.89-2 2-2h12a2 2 0 0 1 2 2zm-6 4v2H4a2 2 0 0 1-2-2V7h2v13zm-3-6l7-7l-1.41-1.41L13 11.17L9.91 8.09L8.5 9.5z"/></svg>
        </div>
        <span class="text-sm font-medium text-gray-700">
            <span wire:loading.remove>Evaluation</span>
            <span wire:loading>Adding...</span>
        </span>
    </button>
</div>

