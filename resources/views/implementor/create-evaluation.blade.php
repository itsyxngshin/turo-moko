@extends('layouts.layout')

@section('title', 'Create Evaluation')

@section('content')
<main>
  <form
    action=""
    class="bg-white rounded-3xl border border-gray-200 shadow-sm py-10 px-10 ml-4 mt-2 flex flex-col gap-8"
  >
    <input
      type="text"
      value="Evaluation Form Title"
      class="text-3xl font-bold text-center"
    />

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <textarea name="" id="" class="w-full resize-none overflow-hidden leading-relaxed" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">Instructions here instructions here instructions here instructions here instructions here instructions here instructions here instructions here instructions here instructions here.</textarea>

    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <input class="text-xl font-semibold type="text" value="Question one?" />
    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <input
        type="text"
        value="Answer"
        class="px-4 py-2 rounded-xl border bg-white shadow-sm w-96"
      />
    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <input class="text-xl font-semibold" type="text" value="Question two?" />
    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <div class="space-y-3">
        <label
          class="flex items-center gap-2 mb-2 block border rounded-lg p-3 cursor-pointer hover:bg-gray-50"
        >
          <input type="checkbox" name="" value="" />
          <span>Option 1</span>
        </label>
        <label
          class="flex items-center gap-2 mb-2 block border rounded-lg p-3 cursor-pointer hover:bg-gray-50"
        >
          <input type="checkbox" name="" value="" />
          <span>Option 2</span>
        </label>
        <label
          class="flex items-center gap-2 mb-2 block border rounded-lg p-3 cursor-pointer hover:bg-gray-50"
        >
          <input type="checkbox" name="" value="" />
          <span>Option 3</span>
        </label>
        <label
          class="flex items-center gap-2 mb-2 block border rounded-lg p-3 cursor-pointer hover:bg-gray-50"
        >
          <input type="checkbox" name="" value="" />
          <span>Option 4</span>
        </label>
      </div>
    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <div class="flex gap-2">
        @for ($i = 1; $i <= 5; $i++)
            <label class="cursor-pointer">
            <input type="radio" name="star_rating" value="{{ $i }}" class="hidden peer" />
            <i data-lucide="star" class="w-8 h-8 text-gray-600 peer-checked:text-black"></i>
            </label>
        @endfor
      </div>
    </div>
    
    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <div class="flex gap-4">
        @for ($i = 1; $i <= 10; $i++)
            <label class="w-10 h-10 flex items-center justify-center border-2 border-gray-600 rounded-lg cursor-pointer hover:bg-gray-100">
                <input type="radio" name="scale_1_10" value="{{ $i }}" class="hidden peer" />
                <span class="peer-checked:font-bold">
                    {{ $i }}
                </span>
            </label>
        @endfor
      </div>
    </div>

    <div class="flex gap-4 mt-6 justify-center">
      <button
        type="submit"
        name="action"
        value="save"
        class="px-4 py-2 bg-white border border-gray-300 rounded-lg"
      >
        Save
      </button>
      <button
        type="submit"
        name="action"
        value="publish"
        class="px-4 py-2 bg-black text-white rounded-lg"
      >
        Publish
      </button>
    </div>
  </form>
</main>
@endsection
