@extends('layouts.layout')

@section('title', 'Create Assessment')

@section('content')
<main>
<!-- Prefill Assessment Modal -->
<div id="prefillModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-xl font-bold">Assessment Details</h2>
      <button id="closePrefillModal"><i data-lucide="x" class="w-6 h-6 text-gray-600"></i></button>
    </div>

    <hr class="border-t-2 border-gray-300 w-full mb-4">

    <!-- Form -->
    <form id="prefillForm" class="flex flex-col gap-4">
      
      <!-- Course Name -->
      <div class="flex flex-col">
        <label class="font-semibold text-sm mb-1">Course Name</label>
        <select name="course" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
          <option value="">Select course</option>
          <option value="course1">Course 1</option>
          <option value="course2">Course 2</option>
        </select>
      </div>

      <!-- Type -->
      <div class="flex flex-col">
        <label class="font-semibold text-sm mb-1">Type</label>
        <select name="type" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
          <option value="">Select type</option>
          <option value="quiz">Quiz</option>
          <option value="exam">Exam</option>
        </select>
      </div>

      <!-- Deadline -->
      <div class="flex flex-col">
        <label class="font-semibold text-sm mb-1">Deadline</label>
        <input type="date" name="deadline" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
      </div>

      <!-- Description -->
      <div class="flex flex-col">
        <label class="font-semibold text-sm mb-1">Description</label>
        <textarea name="description" rows="3" class="border rounded px-2 py-1"></textarea>
      </div>

      <!-- Actions -->
      <div class="flex justify-center">
      <button
        type="submit"
        name="action"
        value="save"
        class="px-4 py-2 bg-black text-white rounded-lg"
      >
        Save
      </button>
      </div>
    </form>

  </div>
</div>


  <form
    action=""
    class="bg-white rounded-3xl border border-gray-200 shadow-sm py-10 px-10 ml-4 mt-2 flex flex-col gap-8"
  >
    <input
      type="text"
      value="Assessment Form Title"
      class="text-3xl font-bold text-center"
    />

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button" class="addItemBtn"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <textarea name="" id="" class="w-full resize-none overflow-hidden leading-relaxed" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">Instructions here instructions here instructions here instructions here instructions here instructions here instructions here instructions here instructions here instructions here.</textarea>

    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button" class="addItemBtn"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <input class="text-xl font-semibold" type="text" value="Question one?" />
    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button" class="addItemBtn"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
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
        <button type="button" class="addItemBtn"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <input class="text-xl font-semibold" type="text" value="Question two?" />
    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button" class="addItemBtn"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <div class="space-y-3">
        <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
          <input
            type="radio"
            name="multiple-choice"
            value="option1"
            class="hidden peer"
          />
          <span class="peer-checked:font-semibold peer-checked:text-blue-600">
            <b>A.</b> Option
          </span>
        </label>

        <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
          <input
            type="radio"
            name="multiple-choice"
            value="option2"
            class="hidden peer"
          />
          <span class="peer-checked:font-semibold peer-checked:text-blue-600">
            <b>B.</b> Option
          </span>
        </label>

        <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
          <input
            type="radio"
            name="multiple-choice"
            value="option3"
            class="hidden peer"
          />
          <span class="peer-checked:font-semibold peer-checked:text-blue-600">
            <b>C.</b> Option
          </span>
        </label>
      </div>
    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button" class="addItemBtn"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
      </div>
      <div class="space-y-3">
        <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
          <input
            type="radio"
            name="multiple-choice"
            value="option1"
            class="hidden peer"
          />
          <span class="peer-checked:font-semibold peer-checked:text-blue-600">
            <b>TRUE:</b> True statement
          </span>
        </label>

        <label class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
          <input
            type="radio"
            name="multiple-choice"
            value="option2"
            class="hidden peer"
          />
          <span class="peer-checked:font-semibold peer-checked:text-blue-600">
            <b>FALSE:</b> False statement
          </span>
        </label>
      </div>
    </div>

    <div class="flex items-start gap-8">
      <div class="flex gap-2 w-15 flex-shrink-0">
        <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
        <button type="button" class="addItemBtn"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
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

    <!-- Add Item Modal -->
  <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
      <div class="flex justify-between mb-4">
        <h2 class="text-xl font-bold">Add anything</h2>
        <button id="closeModal"><i data-lucide="x" class="w-6 h-6 text-gray-600"></i></button>
      </div>

      <hr class="border-t-2 border-gray-300 w-full mb-4">

      <div class="grid grid-cols-4 gap-4">
        <button class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
          <i data-lucide="type" class="w-6 h-6 mb-2"></i>
          <span class="text-xs text-center px-1">Text</span>
        </button>
        <button class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
          <i data-lucide="heading" class="w-6 h-6 mb-2"></i>
          <span class="text-xs text-center">Heading</span>
        </button>
        <button class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
          <i data-lucide="edit" class="w-6 h-6 mb-2"></i>
          <span class="text-xs text-center">Short Answer</span>
          <button class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
            <i data-lucide="file-text" class="w-6 h-6 mb-2"></i>
            <span class="text-xs text-center px-1">Long Answer</span>
          </button>
          <button class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
            <i data-lucide="check-square" class="w-6 h-6 mb-2"></i>
            <span class="text-xs text-center">True/False</span>
          </button>
          <button class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
            <i data-lucide="list" class="w-6 h-6 mb-2"></i>
            <span class="text-xs text-center">Multiple Choice</span>
          </button>
          <button class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
            <i data-lucide="chevrons-down" class="w-6 h-6 mb-2"></i>
            <span class="text-xs text-center">Dropdown</span>
          </button>
          <button class="border rounded-lg flex flex-col items-center justify-center hover:bg-gray-50 h-20 w-20 px-1">
            <i data-lucide="square" class="w-6 h-6 mb-1"></i>
            <span class="text-xs text-center">Checkboxes</span>
          </button>
        </div>
      </div>
    </div> 
</main>
@endsection

@section('scripts')
<script>
/* ==============================
   Add Anything Modal
   ============================== */
const addModal = document.getElementById('modal');
const openAddBtns = document.querySelectorAll('.addItemBtn');
const closeAdd = document.getElementById('closeModal');
const itemForm = document.getElementById('itemForm');
const itemName = document.getElementById('itemName');
const itemDesc = document.getElementById('itemDesc');

if (openAddBtns && addModal) {
    // Open Add modal for any plus button
    openAddBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            addModal.classList.remove('hidden');
            if (itemName) itemName.focus();
        });
    });
}

if (closeAdd && addModal) {
    closeAdd.addEventListener('click', () => {
        addModal.classList.add('hidden');
        if (itemForm) itemForm.reset();
    });
}

// Handle Add modal form submission safely
if (itemForm) {
    itemForm.addEventListener('submit', e => {
        e.preventDefault();
        if (itemName) console.log('Item name:', itemName.value);
        if (itemDesc) console.log('Item description:', itemDesc.value);
        itemForm.reset();
        if (addModal) addModal.classList.add('hidden');
    });
}


/* ==============================
   Pre-fill Assessment Modal
   ============================== */
const prefillModal = document.getElementById('prefillModal');
const closePrefill = document.getElementById('closePrefillModal');
const prefillForm = document.getElementById('prefillForm');

if (prefillModal) {
    // Show pre-fill modal on page load
    window.addEventListener('DOMContentLoaded', () => {
        prefillModal.classList.remove('hidden');
    });
}

if (closePrefill && prefillModal) {
    // Close pre-fill modal on X button click
    closePrefill.addEventListener('click', () => {
        prefillModal.classList.add('hidden');
    });
}

// Handle pre-fill form submission safely
if (prefillForm && prefillModal) {
    prefillForm.addEventListener('submit', e => {
        e.preventDefault();

        // Populate main form automatically
        const mainForm = document.querySelector('form');
        if (mainForm) {
            const titleInput = mainForm.querySelector('input[type="text"]');
            const descTextarea = mainForm.querySelector('textarea');
            
            if (titleInput && prefillForm.querySelector('input[name="title"]')) {
                titleInput.value = prefillForm.querySelector('input[name="title"]')?.value || '';
            }
            
            if (descTextarea && prefillForm.querySelector('textarea[name="description"]')) {
                descTextarea.value = prefillForm.querySelector('textarea[name="description"]').value;
            }
        }

        prefillModal.classList.add('hidden');
    });
}
</script>
@endsection

