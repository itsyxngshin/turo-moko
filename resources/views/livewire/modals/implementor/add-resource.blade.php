<div x-data="{ open: false }">
    <!-- Button to open modal -->
    <button 
        @click="open = true"
        class="text-black text-2xl px-4 py-2 rounded-lg z-[100]"
    >
        +
    </button>

    <!-- Modal Background -->
    <div 
        x-show="open" 
        x-transition 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-[9999]"
        x-cloak
    >
        <!-- Modal Content -->
<div class="bg-white rounded-xl shadow-lg w-full max-w-4xl py-6">
            <!-- Header -->
            <div class="flex justify-between items-center border-b px-6 pb-3">
                <h2 class="text-lg font-semibold text-gray-800">
                    Add an activity or resource
                </h2>
                <button 
                    @click="open = false"
                    class="text-gray-500 hover:text-red-500 text-xl">&times;
                </button>
            </div>


            <!-- Options Row -->
<div class="flex justify-center items-stretch gap-4 mt-6 pb-6 overflow-x-auto ">
    
   
        @livewire('modals.implementor.add-announcement', [], key('add-announcement'))
    

    <!-- Assignment -->
    <a href="{{ route('implementor.implementors.add-assignment', ['courseId' => $courseId]) }}"
       class="flex-none w-40 h-40 flex flex-col justify-center items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition">
        <div class="p-3 rounded-lg mb-2 text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><g fill="none"><path fill="url(#SVGfM1amdCi)" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/><path fill="url(#SVGlArzleJX)" fill-opacity="0.7" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/><path fill="url(#SVGdHSCH0qn)" fill-opacity="0.4" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/><path fill="url(#SVGcndDMdAK)" d="M8 4.25a2.25 2.25 0 0 0 2.25 2.25h3.5a2.25 2.25 0 0 0 0-4.5h-3.5A2.25 2.25 0 0 0 8 4.25"/><path fill="url(#SVGSSOsNb1g)" fill-opacity="0.9" d="M17.03 11.03a.75.75 0 1 0-1.06-1.06L11 14.94l-1.97-1.97a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 0 0 1.06 0z"/><defs><linearGradient id="SVGfM1amdCi" x1="4" x2="18.146" y1="5.8" y2="23.483" gradientUnits="userSpaceOnUse"><stop stop-color="#36dff1"/><stop offset="1" stop-color="#0094f0"/></linearGradient><linearGradient id="SVGcndDMdAK" x1="12" x2="12" y1="2" y2="6.5" gradientUnits="userSpaceOnUse"><stop stop-color="#ffe06b"/><stop offset="1" stop-color="#fab500"/></linearGradient><linearGradient id="SVGSSOsNb1g" x1="18" x2="10.265" y1="18.5" y2="7.732" gradientUnits="userSpaceOnUse"><stop stop-color="#9deaff"/><stop offset="1" stop-color="#fff"/></linearGradient><radialGradient id="SVGlArzleJX" cx="0" cy="0" r="1" gradientTransform="matrix(0 6.16892 -6.75 0 12 3)" gradientUnits="userSpaceOnUse"><stop stop-color="#0a1852"/><stop offset=".9" stop-color="#0a1852" stop-opacity="0"/></radialGradient><radialGradient id="SVGdHSCH0qn" cx="0" cy="0" r="1" gradientTransform="matrix(0 2.79325 -4.95298 0 12 4.618)" gradientUnits="userSpaceOnUse"><stop stop-color="#0a1852"/><stop offset="1" stop-color="#0a1852" stop-opacity="0"/></radialGradient></defs></g></svg>

        </div>
        <span class="text-sm font-medium text-gray-700">Assignment</span>
    </a>

    <!-- Quiz/Exam -->
    <div class="flex-none w-40 h-40 flex flex-col justify-center items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition">
        <div class="p-3 rounded-lg mb-2 text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 48 48"><g fill="#F44336"><path d="M20 18.6L17.75 24h4.5z"/><path fill-rule="evenodd" d="M38 15L28 4H14a4 4 0 0 0-4 4v32a4 4 0 0 0 4 4h20a4 4 0 0 0 4-4zm-18 0a1 1 0 0 1 .923.615l5 12a1 1 0 0 1-1.846.77L23.083 26h-6.166l-.994 2.385a1 1 0 0 1-1.846-.77l5-12A1 1 0 0 1 20 15m-5 17a1 1 0 1 0 0 2h18a1 1 0 1 0 0-2zm-1 5a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H15a1 1 0 0 1-1-1m17-15a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2zM28 7l7 8h-6a1 1 0 0 1-1-1z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M25.923 27.614v.001q.043.104.062.209a3 3 0 0 1-1.2-.8q-.086.018-.17.053a1 1 0 0 0-.538 1.306L23.083 26h-6.166l-.994 2.384a1 1 0 0 1-1.846-.769l.993-2.384l4.007-9.616a1 1 0 0 1 1.846 0l.923 2.214v.002l2.25 5.4v.001zm-.077-5.384l-3.077-7.384a3 3 0 0 0-5.538 0l-5 12A3 3 0 0 0 12 27.93V8a2 2 0 0 1 2-2h12.268c-.172.298-.268.64-.268 1v7a3 3 0 0 0 3 3h6a2 2 0 0 0 1-.268V25a3 3 0 0 0-3-3a3 3 0 1 0-6 0a3 3 0 0 0-1.154.23m.77 1.847A1 1 0 0 0 27 26h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2a1 1 0 1 0-2 0v2h-2a1 1 0 0 0-.385.077m.884 5.582q.12.18.264.341h-.527q.144-.162.263-.341m-5.27-.505c.134.319.315.602.533.846zm.533.846h-5.526c.218-.244.4-.527.532-.846L18.25 28h3.5l.48 1.154M13.34 30.5A3 3 0 0 1 12 28.067V33c0-1.043.533-1.962 1.34-2.499m-.576 4.5a3 3 0 0 1-.764-2V37c0-.768.29-1.468.764-1.999m19.472-5c.475-.53.764-1.232.764-2a3 3 0 0 0 3-3v8a3 3 0 0 0-3-3zm-2.407 6H33a3 3 0 0 0 3-3v7a2 2 0 0 1-2 2H14a2 2 0 0 1-2-2v-2.999A3 3 0 0 0 15 40h12a3 3 0 0 0 2.83-4m-2.826-2H33a1 1 0 1 0 0-2H15a1 1 0 1 0 0 2h12.003M38 15v25a4 4 0 0 1-4 4H14a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h14zM28 7l7 8h-6a1 1 0 0 1-1-1zm-5.75 17h-4.5L20 18.6zm1.827 4.385v-.002zM15 36a1 1 0 1 0 0 2h12a1 1 0 1 0 0-2z" clip-rule="evenodd"/></g></svg>
        </div>
        <span class="text-sm font-medium text-gray-700">Quiz/Exam</span>
    </div>

    <!-- Evaluation -->
    <div class="flex-none w-40 h-40 flex flex-col justify-center items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition">
        <div class="p-3 rounded-lg mb-2 text-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="currentColor" d="M22 16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4c0-1.11.89-2 2-2h12a2 2 0 0 1 2 2zm-6 4v2H4a2 2 0 0 1-2-2V7h2v13zm-3-6l7-7l-1.41-1.41L13 11.17L9.91 8.09L8.5 9.5z"/></svg>
        </div>
        <span class="text-sm font-medium text-gray-700">Evaluation</span>
    </div>

    <!-- Add Module -->
@livewire('modals.implementor.add-module', ['courseId' => $courseId], key('add-module-'.$courseId))


</div>


           

        </div>
    </div>
</div>
