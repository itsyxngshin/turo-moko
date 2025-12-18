<div> <div class="flex flex-col space-y-4 mb-6">
        
        <div class="flex items-start justify-between w-full space-x-4">
            <div x-data="{ open: false }" class="relative flex-1" @click.away="open = false">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="searchName"
                    @focus="open = true"
                    placeholder="Search student to add to list..."
                    class="border p-2 rounded-md w-full md:w-1/2 focus:outline-none focus:ring focus:border-blue-300"
                >

                @if(!empty($searchResults) && count($searchResults) > 0)
                <ul
                    x-show="open"
                    class="absolute z-20 bg-white border border-gray-300 rounded-md w-full md:w-1/2 mt-1 max-h-60 overflow-y-auto shadow-lg"
                >
                    @foreach($searchResults as $user)
                        <li class="flex justify-between items-center p-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0">
                            <span class="text-sm">{{ $user->profile->first_name }} {{ $user->profile->last_name }} <span class="text-gray-500 text-xs">({{ $user->username }})</span></span>
                            <button
                                wire:click="stageUser({{ $user->id }})"
                                class="bg-gray-800 text-white text-xs px-2 py-1 rounded hover:bg-gray-600 transition"
                            >
                                Select
                            </button>
                        </li>
                    @endforeach
                </ul>
                @elseif(strlen($searchName) >= 2)
                 <ul x-show="open" class="absolute z-20 bg-white border border-gray-300 rounded-md w-full md:w-1/2 mt-1 shadow-lg">
                    <li class="p-2 text-gray-500 text-sm">No results found or already selected.</li>
                 </ul>
                @endif
            </div>

            <div class="flex flex-col gap-1 w-full md:w-auto">
                <div class="flex items-center gap-2">
                    <input 
                        id="enrollLink" type="text" 
                        value="{{ route('course.join', $course->course_code) }}" 
                        readonly
                        class="border rounded-l-lg px-3 py-2 w-full md:w-48 text-sm text-gray-700 bg-gray-50"
                    >
                    <button 
                        onclick="copyEnrollmentLink()"
                        class="px-3 py-2 bg-black text-white text-sm rounded-r-lg hover:bg-gray-800 transition"
                    >
                        Copy
                    </button>
                </div>
            </div>
        </div>

        @if(count($stagedUsers) > 0)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-bold text-blue-800 text-sm">Selected Students ({{ count($stagedUsers) }})</h3>
                    <button 
                        wire:click="enrollStaged"
                        class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 text-sm font-bold transition flex items-center gap-2"
                    >
                        <span>Enroll All Selected</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </button>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    @foreach($stagedUsers as $index => $sUser)
                        <div class="flex items-center bg-white border border-blue-200 rounded-full px-3 py-1 shadow-sm">
                            <span class="text-sm text-gray-700 mr-2">{{ $sUser['name'] }}</span>
                            <button 
                                wire:click="unstageUser({{ $index }})"
                                class="text-red-400 hover:text-red-600 focus:outline-none"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
    
    <div class="mt-6">
        <h3 class="font-bold text-lg mb-2">Enrolled Students</h3>
        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-2 border">No.</th>
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Username</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border text-center">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($this->enrollees as $enrollee)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-2 text-center border">
                            {{ $loop->iteration }}
                        </td>

                        <td class="p-2 border">
                            {{ $enrollee->user->profile->first_name }}
                            {{ $enrollee->user->profile->last_name }}
                        </td>

                        <td class="p-2 border">
                            {{ $enrollee->user->username }}
                        </td>

                        <td class="p-2 border">
                            <span class="px-2 py-1 text-xs rounded-full {{ $enrollee->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $enrollee->status }}
                            </span>
                        </td>

                        <td class="p-2 text-center border">
                            <button
                                wire:click="removeEnrollee({{ $enrollee->id }})"
                                class="text-red-500 hover:text-red-700 text-sm underline"
                                onclick="confirm('Are you sure you want to remove this student?') || event.stopImmediatePropagation()"
                            >
                                Remove
                            </button>
                        </td>
                    </tr>
                @endforeach
                
                @if($this->enrollees->isEmpty())
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">No students enrolled yet.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div
        wire:loading.flex
        wire:target="enrollStaged, removeEnrollee"
        class="fixed inset-0 z-50 bg-black bg-opacity-40 flex flex-col items-center justify-center"
    >
        <div class="loader mb-4"></div>
        <span class="text-white font-semibold italic">Processing...</span>
    </div>

    <style>
        .loader { border: 6px solid #f3f3f3; border-top: 6px solid #f97316; border-radius: 50%; width: 60px; height: 60px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
    <script>
        function copyEnrollmentLink() {
            const linkInput = document.getElementById('enrollLink');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(linkInput.value).then(() => { alert('Link copied!'); });
        }
    </script>
</div>