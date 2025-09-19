@extends('layouts.layout')

@section('title', 'Add Link')
@section('page-title', 'Add a Link')

@section('content')
<div class="p-0 ml-[20px]">
  <div class="bg-white rounded-2xl shadow-md p-8">

    <!-- Course Title -->
    <h2 class="text-2xl font-bold mb-2">Course name</h2>

    <!-- Subheading -->
    <h3 class="text-lg font-semibold mb-6">Add a link</h3>

    <!-- Form -->
    <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
      @csrf

      <!-- General Section -->
      <div>
        <details open class="border rounded-lg">
          <summary class="cursor-pointer px-4 py-2 font-medium text-gray-800 bg-gray-100 rounded-t-lg">
            General
          </summary>
          <div class="p-4 space-y-4">
            <!-- Title -->
            <div>
              <label class="block text-sm font-medium mb-1">Title</label>
              <input type="text" placeholder="Topic"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
            </div>

            <!-- Link -->
            <div>
              <label class="block text-sm font-medium mb-1">Link</label>
              <input type="url" placeholder="Link"
                class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-black" />
            </div>

            <!-- File Upload -->
            <div>
              <label class="block text-sm font-medium mb-1">Additional Files</label>
              <div
                class="flex flex-col items-center justify-center border-2 border-dashed rounded-lg p-6 text-gray-500 hover:border-black cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mb-2" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16V4m0 0L3 8m4-4l4 4M17 8v12m0 0l4-4m-4 4l-4-4" />
                </svg>
                <span>Upload file</span>
                <input type="file" class="hidden" />
              </div>
            </div>
          </div>
        </details>
      </div>

      <!-- Buttons -->
      <div class="flex justify-end gap-4">
        <button type="button"
          class="px-6 py-2 bg-gray-200 text-black rounded-full hover:bg-gray-300">Discard</button>
        <button type="submit"
          class="px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800">Save and display</button>
      </div>
    </form>

  </div>
</div>
@endsection
