@extends('layouts.layout')

@section('title', 'Create Assessment')

@section('content')
<style>
    [x-cloak] { display: none !important; }
</style>

<main x-data="assessmentBuilder">
    <form
        action=""
        class="bg-white rounded-3xl border border-gray-200 shadow-sm py-10 px-10 ml-4 mt-2 flex flex-col gap-8"
    >

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Assessment Details</h2>
        </div>

        <div class="flex flex-col">
            <label class="font-semibold text-sm mb-1">Course Name</label>
            <select name="course" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                <option value="">Select course</option>
                <option value="course1">Course 1</option>
                <option value="course2">Course 2</option>
            </select>
        </div>

        <div class="flex gap-10 w-full">
            <div class="flex flex-col w-full">
                <label class="font-semibold text-sm mb-1">Type</label>
                <select name="type" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
                    <option value="">Select type</option>
                    <option value="quiz">Quiz</option>
                    <option value="exam">Exam</option>
                </select>
            </div>

            <div class="flex flex-col w-full">
                <label class="font-semibold text-sm mb-1">Deadline</label>
                <input type="date" name="deadline" class="block border rounded-lg p-3 cursor-pointer hover:bg-gray-50">
            </div>
        </div>

        <div class="flex flex-col">
            <label class="font-semibold text-sm mb-1">Description</label>
            <textarea name="description" rows="3" class="border rounded px-2 py-1"></textarea>
        </div>

        <hr class="border-t-2 border-gray-300 w-full mb-4">

        <input
            type="text"
            value="Assessment Form Title"
            class="text-3xl font-bold text-center"
        />

        <div class="flex items-start gap-8">
            <div class="flex gap-2 w-15 flex-shrink-0">
                <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
                <button type="button" @click="addItemModalOpen = true"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
                <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
            </div>
            <textarea name="" id="" class="w-full resize-none overflow-hidden leading-relaxed" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">Instructions here instructions here instructions here instructions here instructions here instructions here instructions here instructions here instructions here instructions here.</textarea>
        </div>

        <div class="flex items-start gap-8">
            <div class="flex gap-2 w-15 flex-shrink-0">
                <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
                <button type="button" @click="addItemModalOpen = true"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
                <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
            </div>
            <input class="text-xl font-semibold" type="text" value="Question one?" />
        </div>

        <div class="flex items-start gap-8">
            <div class="flex gap-2 w-15 flex-shrink-0">
                <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
                <button type="button" @click="addItemModalOpen = true"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
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
                <button type="button" @click="addItemModalOpen = true"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
                <button type="button"><i data-lucide="grip-vertical" class="w-6 h-6 text-gray-600"></i></button>
            </div>
            <input class="text-xl font-semibold" type="text" value="Question two?" />
        </div>

        <div class="flex items-start gap-8">
            <div class="flex gap-2 w-15 flex-shrink-0">
                <button type="button"><i data-lucide="trash" class="w-6 h-6 text-gray-600"></i></button>
                <button type="button" @click="addItemModalOpen = true"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
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
                <button type="button" @click="addItemModalOpen = true"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
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
                <button type="button" @click="addItemModalOpen = true"><i data-lucide="plus" class="w-6 h-6 text-gray-600"></i></button>
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
                value="preview"
                class="px-4 py-2 bg-gray-600 text-white rounded-lg"
            >
                Preview
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
    <div x-show="addItemModalOpen" x-transition x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96 relative">
            <div class="flex justify-between mb-4">
                <h2 class="text-xl font-bold">Add anything</h2>
                <button type="button" @click="addItemModalOpen = false"><i data-lucide="x" class="w-6 h-6 text-gray-600"></i></button>
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
    </div>

    <script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("assessmentBuilder", () => ({
            addItemModalOpen: false,
        }));
    });
    </script>
</main>
@endsection