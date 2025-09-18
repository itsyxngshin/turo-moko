@extends('layouts.layout')

@section('title', 'Course Information')
@section('page-title', 'Course Information')

@section('content')
<div class="p-0 ml-[20px]">
   


    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-6 mb-8">
      <div class="bg-white rounded-lg p-6 shadow-sm border flex items-center gap-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"><path fill="#656769" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="m23.55 17.33l-15-7.64a.5.5 0 0 1 0-.89l15-7.69a1 1 0 0 1 .92 0l15 7.69a.5.5 0 0 1 0 .89l-15 7.64a1 1 0 0 1-.92 0" stroke-width="1"/><path fill="none" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M8.31 9.24v7.95" stroke-width="1"/><path fill="#ffe500" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M9.27 17.18a1 1 0 0 0-1-1.07a1 1 0 0 0-1 1.07l-.19 3.69h2.38Z" stroke-width="1"/><path fill="#a86c4d" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M24 14.81c-5 0-9.65 1-10.88 2.38v14.19a1.24 1.24 0 0 0 .77 1.14h0a27.1 27.1 0 0 0 20.22 0h0a1.24 1.24 0 0 0 .77-1.14V17.19C33.65 15.86 29 14.81 24 14.81" stroke-width="1"/><path fill="#656769" d="M31.54 34.09L24 31.22l-7.54 2.87a6.76 6.76 0 0 0-4.35 6.31V45h23.78v-4.6a6.76 6.76 0 0 0-4.35-6.31"/><path fill="#525252" d="M31.54 34.09L24 31.22l-7.54 2.87a6.76 6.76 0 0 0-4.35 6.31v3a6.76 6.76 0 0 1 4.35-6.31L24 34.21l7.54 2.87a6.76 6.76 0 0 1 4.35 6.31v-3a6.76 6.76 0 0 0-4.35-6.3"/><path fill="none" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M31.54 34.09L24 31.22l-7.54 2.87a6.76 6.76 0 0 0-4.35 6.31V45h23.78v-4.6a6.76 6.76 0 0 0-4.35-6.31" stroke-width="1"/><path fill="#ffcebf" d="M24 35.11s-2.54-1.11-2.54-2.54v-2.85A2.54 2.54 0 0 1 24 27.17a2.54 2.54 0 0 1 2.54 2.55v2.85c0 1.43-2.54 2.54-2.54 2.54"/><path fill="#ffb59e" d="M24 27.17a2.54 2.54 0 0 0-2.54 2.54v.6a2.54 2.54 0 0 0 5.08 0v-.59A2.54 2.54 0 0 0 24 27.17"/><path fill="none" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M24 35.11s-2.54-1.11-2.54-2.54v-2.85A2.54 2.54 0 0 1 24 27.17h0a2.54 2.54 0 0 1 2.54 2.55v2.85c0 1.43-2.54 2.54-2.54 2.54m-10.55 1.24L17.06 45m17.49-8.65L30.94 45m-10.4-4.52l.73 4.52m6.19-4.52L26.73 45" stroke-width="1"/><path fill="#45413c" d="M9 45.5a15 1.5 0 1 0 30 0a15 1.5 0 1 0-30 0" opacity="0.15"/><path fill="#ffcebf" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M33.07 19.47a8.77 8.77 0 0 1-5.68-5L26.77 13a15.92 15.92 0 0 1-9.95 6.18l-1.88.34a1.81 1.81 0 1 0 0 3.62h.11a9 9 0 0 0 17.9 0h.12a1.81 1.81 0 1 0 0-3.62Z" stroke-width="1"/><path fill="#45413c" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M18.57 20.91a.77.77 0 1 0 .77-.77a.76.76 0 0 0-.77.77m10.86 0a.77.77 0 1 1-.77-.77a.76.76 0 0 1 .77.77" stroke-width="1"/><path fill="#ff6242" d="M21.16 25.6a.44.44 0 0 0-.33.16a.42.42 0 0 0-.1.35a3.32 3.32 0 0 0 6.54 0a.42.42 0 0 0-.1-.35a.42.42 0 0 0-.33-.16Z"/><path fill="#ffa694" d="M24 27a4 4 0 0 0-2.52.77a3.36 3.36 0 0 0 5 0A4 4 0 0 0 24 27"/><path fill="none" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M21.16 25.6a.44.44 0 0 0-.33.16a.42.42 0 0 0-.1.35a3.32 3.32 0 0 0 6.54 0a.42.42 0 0 0-.1-.35a.42.42 0 0 0-.33-.16Z" stroke-width="1"/><path fill="#ffb59e" d="M17.06 24.19a1 .6 0 1 0 2 0a1 .6 0 1 0-2 0m11.88 0a1 .6 0 1 0 2 0a1 .6 0 1 0-2 0"/><path fill="#656769" d="M33.85 9c-.27-1.29-4.12-2.2-9.85-2.2s-9.58.91-9.85 2.2a75 75 0 0 0-1 8.16c1.2-1.3 5.85-2.35 10.85-2.35s9.65 1 10.88 2.38A75 75 0 0 0 33.85 9"/><path fill="#525252" d="M24 9.52a30.7 30.7 0 0 1 10.2 1.61L33.85 9c-.27-1.29-4.12-2.2-9.85-2.2s-9.58.91-9.85 2.2c0 0-.16.88-.35 2.1A30.7 30.7 0 0 1 24 9.52"/><path fill="none" stroke="#45413c" stroke-linecap="round" stroke-linejoin="round" d="M33.85 9c-.27-1.29-4.12-2.2-9.85-2.2s-9.58.91-9.85 2.2a75 75 0 0 0-1 8.16c1.2-1.3 5.85-2.35 10.85-2.35s9.65 1 10.88 2.38A75 75 0 0 0 33.85 9" stroke-width="1"/></svg>
        <div>
          <p class="text-sm text-gray-500">Enrollees</p>
          <h2 class="text-lg font-bold">1,234</h2>
        </div>
      </div>
      <div class="bg-white rounded-lg p-6 shadow-sm border flex items-center gap-4">
       <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"><path stroke="black" fill="#B1CC33" d="m23.15 5.4l-2.8-2.8a.5.5 0 0 0-.7 0L7.85 14.4a.5.5 0 0 1-.7 0l-2.8-2.8a.5.5 0 0 0-.7 0l-2.8 2.8a.5.5 0 0 0 0 .7l6.3 6.3a.5.5 0 0 0 .7 0l15.3-15.3a.5.5 0 0 0 0-.7"/></svg>
        <div>
          <p class="text-sm text-gray-500">Submissions</p>
          <h2 class="text-lg font-bold">15</h2>
        </div>
      </div>
      <div class="bg-white rounded-lg p-6 shadow-sm border flex items-center gap-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 128 128"><path fill="#01579b" d="M118.03 102.32L72.29 123c-2.82 1.33-5.76 1.2-8.46-.36L6.09 93.32c-1.65-1.06-2.14-2.61-2.04-3.69s.35-2.25 3.25-3.09l4.28-1.58l57.92 31.57l41.16-16.82z"/><path fill="#f5f5f5" d="M71.74 119.69a7.95 7.95 0 0 1-7.26-.26L8.11 91.03c-.8-.44-1.04-1.45-.56-2.23c1.24-2.05 3.52-8.53-.24-13.91l63.66 30.65z"/><path fill="#94c6d6" d="m115.59 99.98l-43.85 19.71c-1.45.63-4.34 1.75-7.67-.49c2.63.19 4.48-.9 5.43-2.67c.93-1.72.65-4.54-.48-6.13c-.69-.96-2.54-2.49-3.35-3.35L113.1 88.5c4.2-1.73 8.14.86 8.77 4.01c.7 3.56-3.84 6.47-6.28 7.47"/><path fill="#01579b" d="m117.78 86.96l-45.27 20.2c-2.85 1.13-6.04.98-8.77-.4L5.9 77.38c-.56-.28-1.39-1.05-1.72-2.1c-.54-1.75.14-3.95 2.19-4.65l62.68 31.95l42.92-18.37z"/><path fill="#0091ea" d="m121.19 89.89l-4.93-1.79l-10.16.59l-33.58 14.99c-2.85 1.13-6.04.98-8.77-.4L5.9 73.91c-1.49-.76-1.17-2.97.47-3.28l41.69-18.65c1.19-.22 2.41-.09 3.52.38l59.49 28.36s9.45 6.47 10.12 9.17"/><path fill="#616161" d="M105.53 88.98s6.26-2.45 11.18-2.23s6.63 3.67 6.63 3.67c-.93-4.23-5.3-6.39-5.3-6.39l-65-32.73c-.45-.19-2.11-.58-4.66.47c-2.06.85-8.79 4-8.79 4z"/><path fill="#424242" d="M123.62 91.22c-.47-1.87-1.63-3.87-3.77-4.84c-2.82-1.27-6.84-.94-9.41.4l-4.91 2.18v3.46l6.21-2.76c6.04-2.69 8.72 1.34 8.95 2.29c.96 3.87-.9 6.11-6.39 8.63l-8.92 4.02v3.48l10.26-4.57c4.54-1.82 9.72-5.24 7.98-12.29"/><path fill="#01579b" d="M33.01 90.31L15.74 66.44l2.71-1.21l19.43 26.7zm22.15 11l-3.08-2.44l53.45-10.91v1.75l-7.49 2.84z"/><path fill="#9ccc65" d="M14.8 46.18L82.31 34.9l29.49 32.47c1.49 1.57.68 4.17-1.44 4.6l-69.7 14.3z"/><path fill="#689f38" d="M110.36 69.17L41.14 83.19l-.22 3.3l69.44-14.24c1.96-.41 2.78-2.65 1.71-4.23c-.38.56-.96 1-1.71 1.15m3.73 15.13c.73 1.16.07 2.69-1.27 2.96L49.1 100.18c-3.83.79-7.59-1.72-7.93-5.62c-.29-3.3 1.94-6.29 5.19-6.97l61.28-13.76z"/><path fill="#616161" d="M55.59 80.1L30.21 43.78l-14.48 3.83c-3.35 3.33-2.1 8.8-2.1 8.8S35.8 91.99 39.3 96.54s8.61 3.84 8.61 3.84l8.63-1.74l-.9-16.1z"/><path fill="#424242" d="M55.59 80.34L43.4 82.86c-3.33.75-3.93 3.88-3.93 3.88L10.04 44.57s-4.19 5.07-1.41 9.38L39.3 96.54c3.35 4.77 8.61 3.88 8.61 3.88l8.63-1.74l-.89-15.78z"/><path fill="#b9e4ea" d="M110.25 83c.31.68-.09 1.47-.82 1.62L48.5 97.12c-3.83.79-6.54-1.75-6.4-5.21c.18-4.37 2.63-6.22 5.87-6.89l61.23-12.51s-2.08 2.34-.49 6.72c.54 1.51 1.12 2.85 1.54 3.77"/><path fill="none" stroke="#424242" stroke-miterlimit="10" stroke-width="2.071" d="M45.21 83.7L19.1 46.76"/><path fill="#424242" d="M47.26 67.95L13.68 51.03l-1.36 2.68l38.8 19.77z"/><path fill="#689f38" d="m108.79 64.03l-2.46-2.7L68.5 78.69L47.26 68.18l3.62 5.18l14.07 7.19l10.48-1.61z"/><path fill="#c62828" d="M118.02 57.35L72.29 78.03c-2.82 1.33-5.76 1.2-8.46-.36L6.09 48.35c-1.65-1.06-2.14-2.61-2.04-3.69s.35-2.25 3.25-3.09l2.71-1l59.32 29.11l48.17-19.93z"/><path fill="#f5f5f5" d="M71.73 74.72a7.95 7.95 0 0 1-7.26-.26L8.1 46.06c-.8-.44-1.04-1.45-.56-2.23c1.24-2.05 3.52-8.53-.24-13.91l62.24 31.66z"/><path fill="#94c6d6" d="M115.58 55.01L71.73 74.72c-1.45.63-4.34 1.75-7.67-.49c2.63.19 4.48-.9 5.43-2.67c.93-1.72.65-4.54-.48-6.13c-.69-.96-2.54-2.49-3.35-3.35l47.43-18.55c4.2-1.73 8.14.86 8.77 4.01c.7 3.56-3.84 6.47-6.28 7.47"/><path fill="#c62828" d="m117.78 41.99l-45.27 20.2c-2.85 1.13-6.04.98-8.77-.4L5.89 32.41c-.6-.3-1.5-1.07-1.79-2.16c-.43-1.62.13-3.75 2.26-4.59l53.01-11.23z"/><path fill="#f44336" d="m121.18 44.92l-4.93-1.79l-10.16.59l-33.58 14.99c-2.85 1.13-6.04.98-8.77-.4L5.89 28.93c-1.49-.76-.96-2.77.47-3.28l41.7-18.64c1.19-.22 2.41-.09 3.52.38l59.49 28.36s9.44 6.46 10.11 9.17"/><path fill="#616161" d="M105.53 44s5.21-1.83 10.13-1.61s7.69 3.05 7.69 3.05c-1.01-4.52-5.3-6.39-5.3-6.39l-65-32.73c-.45-.19-2.11-.58-4.66.47c-2.06.85-8.79 4-8.79 4z"/><path fill="#424242" d="M111.48 41.86L44.97 8.31l2.2-.99l67.64 33.9z"/><path fill="#424242" d="M123.61 46.25c-.47-1.87-1.26-3.68-3.49-4.62c-2.85-1.2-5.45-1.45-9.69.18l-4.91 2.18v3.46l6.21-2.76c3.15-1.48 7.79-1.16 8.95 2.29c1.27 3.78-.9 6.11-6.39 8.63l-8.92 4.02v3.48l10.26-4.57c4.55-1.82 9.73-5.24 7.98-12.29"/></svg>
        <div>
          <p class="text-sm text-gray-500">Evaluations</p>
          <h2 class="text-lg font-bold">5</h2>
        </div>
      </div>
    </div>

    <!-- Featured Course -->
    <div class="relative mb-8">
      <img src="https://images.unsplash.com/photo-1608506573186-631f3ff1f6e3" class="rounded-lg w-full h-56 object-cover">
      <div class="absolute top-4 left-4 bg-black bg-opacity-50 text-white px-3 py-1 rounded">Subject here</div>
      <div class="absolute bottom-4 left-4 text-white">
        <h2 class="text-xl font-semibold">Course Name</h2>
        <div class="flex items-center gap-2 mt-2">
          <div class="w-8 h-8 bg-white text-black rounded-full flex items-center justify-center">
            <i class="fas fa-play"></i>
          </div>
          <span>Continue course</span>
        </div>
      </div>
    </div>

    <!-- Your Courses -->
    <div class="bg-white rounded-lg p-6 border">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-semibold">Your courses</h3>
        <button class="text-sm font-medium text-gray-600 hover:underline">View all</button>
      </div>
      <div class="grid grid-cols-2 gap-6">
        <div class="border rounded-lg p-4 flex flex-col">
          <img src="https://images.unsplash.com/photo-1584697964403-b72e3a9d3d6f" class="rounded-lg mb-4 h-36 object-cover">
          <p class="text-xs text-gray-400 mb-1">1st Sem SY 2024-2025</p>
          <h4 class="text-base font-semibold">Course Name</h4>
          <p class="text-sm text-gray-600 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
          <button class="self-end px-4 py-1 text-sm border rounded-full bg-black text-white">View</button>
        </div>

        <div class="border rounded-lg p-4 flex flex-col">
          <img src="https://images.unsplash.com/photo-1549921296-3a4d57c5f7d5" class="rounded-lg mb-4 h-36 object-cover">
          <p class="text-xs text-gray-400 mb-1">1st Sem SY 2024-2025</p>
          <h4 class="text-base font-semibold">Course Name</h4>
          <p class="text-sm text-gray-600 mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.</p>
          <button class="self-end px-4 py-1 text-sm border rounded-full bg-black text-white">View</button>
        </div>
      </div>
    
</div>
@endsection
