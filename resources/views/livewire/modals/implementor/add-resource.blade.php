<div x-data="{ open: @entangle('showModal') }">
    <!-- Button to open modal -->
    <button 
        @click="open = true"
        class="text-black text-2xl px-4 py-2 rounded-lg">
        +
    </button>

    <!-- Modal Background -->
    <div 
        x-show="open" 
        x-transition 
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="display: none;"
    >
        <!-- Modal Content -->
        <div class="bg-white rounded-xl shadow-lg w-full max-w-xl py-6">
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

            <!-- Options Grid -->
            <div class="grid grid-cols-3 gap-x-10 gap-y-6 mt-6 pb-6 px-10 text-center">
                
                <!-- Forum -->
                <div class="flex flex-col border border-gray-200 items-center p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition">
                    <div class="p-3 rounded-lg mb-2 text-blue-500">
                        <!-- Chat Bubble Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 48 48"><path fill="currentColor" fill-rule="evenodd" d="M33 18.535c1.163.348 2 .465 2 .465v-3h2a5 5 0 0 0 0-10h-5.764A5.236 5.236 0 0 0 26 11.236c0 4.518 4.348 6.506 7 7.299M40 11a3 3 0 0 1-3 3h-4v2.435a13 13 0 0 1-1.603-.667C29.414 14.774 28 13.36 28 11.236A3.236 3.236 0 0 1 31.236 8H37a3 3 0 0 1 3 3m-25.183 6.993A4.998 4.998 0 0 1 14.998 8h3.169A4.833 4.833 0 0 1 23 12.833c0 4.042-3.63 5.89-6 6.667c-1.148.376-2 .5-2 .5v-2zM17 16.071l-2.11-.076A2.998 2.998 0 0 1 14.997 10h3.169A2.833 2.833 0 0 1 21 12.833c0 1.915-1.217 3.17-2.924 4.06c-.36.188-.725.348-1.076.484zM28 24c0 2.21-1.79 4-4 4s-4-1.79-4-4s1.79-4 4-4s4 1.79 4 4m-2 0a1.999 1.999 0 1 1-4 0a1.999 1.999 0 1 1 4 0m-7 2c0 2.21-1.79 4-4 4s-4-1.79-4-4s1.79-4 4-4s4 1.79 4 4m-2 0a1.999 1.999 0 1 1-4 0a1.999 1.999 0 1 1 4 0M6 36.546C6 33.522 11.996 32 15 32c.585 0 1.284.058 2.03.173C18.371 31.19 20.827 30 24 30s5.629 1.19 6.971 2.173A13.6 13.6 0 0 1 33 32c3.004 0 9 1.523 9 4.545V42H6zm15.652-.523c.348.324.348.493.348.522V40H8v-3.455c0-.03 0-.198.348-.522c.363-.339.962-.7 1.776-1.03C11.756 34.333 13.75 34 15 34s3.244.333 4.876.993c.814.33 1.413.691 1.776 1.03m6.49-3.167A10.4 10.4 0 0 0 24 32c-1.656 0-3.064.386-4.141.856C22.074 33.6 24 34.832 24 36.546c0-1.714 1.926-2.945 4.142-3.69M40 36.546c0-.03 0-.199-.348-.523c-.363-.339-.962-.7-1.776-1.03C36.244 34.333 34.25 34 33 34s-3.244.333-4.876.993c-.814.33-1.413.691-1.776 1.03c-.348.324-.348.493-.348.522V40h14zM33 30c2.21 0 4-1.79 4-4s-1.79-4-4-4s-4 1.79-4 4s1.79 4 4 4m0-2a1.999 1.999 0 1 0 0-4a1.999 1.999 0 1 0 0 4" clip-rule="evenodd"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Forum</span>
                </div>

                <!-- Assignment -->
                <div class="flex flex-col items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition">
                    <div class="p-3 rounded-lg mb-2 text-green-500">
                        <!-- Clipboard Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><g fill="none"><path fill="url(#SVGfM1amdCi)" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/><path fill="url(#SVGlArzleJX)" fill-opacity="0.7" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/><path fill="url(#SVGdHSCH0qn)" fill-opacity="0.4" d="M4 6.25A2.25 2.25 0 0 1 6.25 4h11.5A2.25 2.25 0 0 1 20 6.25v13.5A2.25 2.25 0 0 1 17.75 22H6.25A2.25 2.25 0 0 1 4 19.75z"/><path fill="url(#SVGcndDMdAK)" d="M8 4.25a2.25 2.25 0 0 0 2.25 2.25h3.5a2.25 2.25 0 0 0 0-4.5h-3.5A2.25 2.25 0 0 0 8 4.25"/><path fill="url(#SVGSSOsNb1g)" fill-opacity="0.9" d="M17.03 11.03a.75.75 0 1 0-1.06-1.06L11 14.94l-1.97-1.97a.75.75 0 0 0-1.06 1.06l2.5 2.5a.75.75 0 0 0 1.06 0z"/><defs><linearGradient id="SVGfM1amdCi" x1="4" x2="18.146" y1="5.8" y2="23.483" gradientUnits="userSpaceOnUse"><stop stop-color="#36dff1"/><stop offset="1" stop-color="#0094f0"/></linearGradient><linearGradient id="SVGcndDMdAK" x1="12" x2="12" y1="2" y2="6.5" gradientUnits="userSpaceOnUse"><stop stop-color="#ffe06b"/><stop offset="1" stop-color="#fab500"/></linearGradient><linearGradient id="SVGSSOsNb1g" x1="18" x2="10.265" y1="18.5" y2="7.732" gradientUnits="userSpaceOnUse"><stop stop-color="#9deaff"/><stop offset="1" stop-color="#fff"/></linearGradient><radialGradient id="SVGlArzleJX" cx="0" cy="0" r="1" gradientTransform="matrix(0 6.16892 -6.75 0 12 3)" gradientUnits="userSpaceOnUse"><stop stop-color="#0a1852"/><stop offset=".9" stop-color="#0a1852" stop-opacity="0"/></radialGradient><radialGradient id="SVGdHSCH0qn" cx="0" cy="0" r="1" gradientTransform="matrix(0 2.79325 -4.95298 0 12 4.618)" gradientUnits="userSpaceOnUse"><stop stop-color="#0a1852"/><stop offset="1" stop-color="#0a1852" stop-opacity="0"/></radialGradient></defs></g></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Assignment</span>
                </div>

                <!-- Quiz/Exam -->
                <div class="flex flex-col items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition">
                    <div class="p-3 rounded-lg mb-2 text-red-500">
                        <!-- Document Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 48 48"><g fill="#F44336"><path d="M20 18.6L17.75 24h4.5z"/><path fill-rule="evenodd" d="M38 15L28 4H14a4 4 0 0 0-4 4v32a4 4 0 0 0 4 4h20a4 4 0 0 0 4-4zm-18 0a1 1 0 0 1 .923.615l5 12a1 1 0 0 1-1.846.77L23.083 26h-6.166l-.994 2.385a1 1 0 0 1-1.846-.77l5-12A1 1 0 0 1 20 15m-5 17a1 1 0 1 0 0 2h18a1 1 0 1 0 0-2zm-1 5a1 1 0 0 1 1-1h12a1 1 0 1 1 0 2H15a1 1 0 0 1-1-1m17-15a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2zM28 7l7 8h-6a1 1 0 0 1-1-1z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M25.923 27.614v.001q.043.104.062.209a3 3 0 0 1-1.2-.8q-.086.018-.17.053a1 1 0 0 0-.538 1.306L23.083 26h-6.166l-.994 2.384a1 1 0 0 1-1.846-.769l.993-2.384l4.007-9.616a1 1 0 0 1 1.846 0l.923 2.214v.002l2.25 5.4v.001zm-.077-5.384l-3.077-7.384a3 3 0 0 0-5.538 0l-5 12A3 3 0 0 0 12 27.93V8a2 2 0 0 1 2-2h12.268c-.172.298-.268.64-.268 1v7a3 3 0 0 0 3 3h6a2 2 0 0 0 1-.268V25a3 3 0 0 0-3-3a3 3 0 1 0-6 0a3 3 0 0 0-1.154.23m.77 1.847A1 1 0 0 0 27 26h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2a1 1 0 1 0-2 0v2h-2a1 1 0 0 0-.385.077m.884 5.582q.12.18.264.341h-.527q.144-.162.263-.341m-5.27-.505c.134.319.315.602.533.846zm.533.846h-5.526c.218-.244.4-.527.532-.846L18.25 28h3.5l.48 1.154M13.34 30.5A3 3 0 0 1 12 28.067V33c0-1.043.533-1.962 1.34-2.499m-.576 4.5a3 3 0 0 1-.764-2V37c0-.768.29-1.468.764-1.999m19.472-5c.475-.53.764-1.232.764-2a3 3 0 0 0 3-3v8a3 3 0 0 0-3-3zm-2.407 6H33a3 3 0 0 0 3-3v7a2 2 0 0 1-2 2H14a2 2 0 0 1-2-2v-2.999A3 3 0 0 0 15 40h12a3 3 0 0 0 2.83-4m-2.826-2H33a1 1 0 1 0 0-2H15a1 1 0 1 0 0 2h12.003M38 15v25a4 4 0 0 1-4 4H14a4 4 0 0 1-4-4V8a4 4 0 0 1 4-4h14zM28 7l7 8h-6a1 1 0 0 1-1-1zm-5.75 17h-4.5L20 18.6zm1.827 4.385v-.002zM15 36a1 1 0 1 0 0 2h12a1 1 0 1 0 0-2z" clip-rule="evenodd"/></g></svg>
</div>
<span class="text-sm font-medium text-gray-700">Quiz/Exam</span>
                </div>

                <!-- Evaluation -->
                <div class="flex flex-col items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition">
                    <div class="p-3 rounded-lg mb-2 text-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="currentColor" d="M22 16a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V4c0-1.11.89-2 2-2h12a2 2 0 0 1 2 2zm-6 4v2H4a2 2 0 0 1-2-2V7h2v13zm-3-6l7-7l-1.41-1.41L13 11.17L9.91 8.09L8.5 9.5z"/></svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Evaluation</span>
                </div>

                <!-- File -->
                <div class="flex flex-col items-center border border-gray-200 p-4 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition">
                    <div class="p-3 rounded-lg mb-2 text-yellow-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="#ef5350" d="M13 9h5.5L13 3.5zM6 2h8l6 6v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2m4.93 10.44c.41.9.93 1.64 1.53 2.15l.41.32c-.87.16-2.07.44-3.34.93l-.11.04l.5-1.04c.45-.87.78-1.66 1.01-2.4m6.48 3.81c.18-.18.27-.41.28-.66c.03-.2-.02-.39-.12-.55c-.29-.47-1.04-.69-2.28-.69l-1.29.07l-.87-.58c-.63-.52-1.2-1.43-1.6-2.56l.04-.14c.33-1.33.64-2.94-.02-3.6a.85.85 0 0 0-.61-.24h-.24c-.37 0-.7.39-.79.77c-.37 1.33-.15 2.06.22 3.27v.01c-.25.88-.57 1.9-1.08 2.93l-.96 1.8l-.89.49c-1.2.75-1.77 1.59-1.88 2.12c-.04.19-.02.36.05.54l.03.05l.48.31l.44.11c.81 0 1.73-.95 2.97-3.07l.18-.07c1.03-.33 2.31-.56 4.03-.75c1.03.51 2.24.74 3 .74c.44 0 .74-.11.91-.3m-.41-.71l.09.11c-.01.1-.04.11-.09.13h-.04l-.19.02c-.46 0-1.17-.19-1.9-.51c.09-.1.13-.1.23-.1c1.4 0 1.8.25 1.9.35M7.83 17c-.65 1.19-1.24 1.85-1.69 2c.05-.38.5-1.04 1.21-1.69zm3.02-6.91c-.23-.9-.24-1.63-.07-2.05l.07-.12l.15.05c.17.24.19.56.09 1.1l-.03.16l-.16.82z"/></svg>

                    </div>
                    <span class="text-sm font-medium text-gray-700">File</span>
                </div>

                <!-- URL -->
                <div class="flex flex-col items-center p-4 border border-gray-200 rounded-lg shadow-md cursor-pointer hover:shadow-lg hover:scale-105 transition">
                    <div class="p-3 rounded-lg mb-2 text-blue-400">
                        <!-- Link Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 32 32"><path fill="#42a5f5" d="M10 14h12v4H10z"/><path fill="#42a5f5" d="M12 22H9.562A5.57 5.57 0 0 1 4 16.438v-.876A5.57 5.57 0 0 1 9.562 10H12V6H9.562A9.56 9.56 0 0 0 0 15.562v.876A9.56 9.56 0 0 0 9.562 26H12ZM22.438 6H20v4h2.438A5.57 5.57 0 0 1 28 15.562v.876A5.57 5.57 0 0 1 22.438 22H20v4h2.438A9.56 9.56 0 0 0 32 16.438v-.876A9.56 9.56 0 0 0 22.438 6"/></svg>

                    </div>
                    <span class="text-sm font-medium text-gray-700">URL</span>
                </div>

            </div>
        </div>
    </div>
</div>
