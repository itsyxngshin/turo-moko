@section('title', 'Verify your TURO-MOKO Account')

<div class="flex min-h-screen flex-col justify-center bg-gray-50 py-12 sm:px-6 lg:px-8">
    
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        {{-- Logo with a slight drop shadow --}}
        <img class="mx-auto h-20 w-auto object-contain drop-shadow-sm transition-transform hover:scale-105 duration-300" 
             src="{{ asset('/images/turo_moko_logo.png') }}" 
             alt="TURO-MOKO"> 
        
        <h2 class="mt-6 text-3xl font-extrabold tracking-tight text-gray-900">
            Verify your Email
        </h2>
        
        {{-- Email Pill Design --}}
        <div class="mt-3 flex justify-center">
            <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-orange-50 border border-orange-100">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-orange-500 mr-2">
                    <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                    <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                </svg>
                <span class="text-sm font-semibold text-orange-700 tracking-wide">
                    {{ auth()->user()->email }}
                </span>
            </div>
        </div>
        <p class="mt-2 text-sm text-gray-500">
            Enter the 6-digit code we sent to your inbox.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl shadow-gray-200/50 sm:rounded-2xl sm:px-10 border border-gray-100">
            
            <form wire:submit.prevent="verify">
                <div class="flex justify-center gap-2 mb-8">
                    @foreach($code as $index => $digit)
                        <input type="text" 
                            wire:model="code.{{ $index }}"
                            maxlength="1"
                            inputmode="numeric"
                            class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-200 rounded-xl 
                                   focus:border-orange-500 focus:ring-4 focus:ring-orange-500/20 focus:outline-none 
                                   transition-all duration-200 caret-transparent text-gray-800 shadow-sm"
                            x-data
                            {{-- Auto-focus logic --}}
                            x-on:input="$el.value.length === 1 ? $el.nextElementSibling?.focus() : null"
                            x-on:keydown.backspace="$el.value.length === 0 ? $el.previousElementSibling?.focus() : null"
                            x-on:paste.prevent="$el.value = $event.clipboardData.getData('text').slice(0, 1)"
                        />
                    @endforeach
                </div>

                @error('code.*') 
                    <div class="flex items-center justify-center gap-2 text-red-500 mb-6 bg-red-50 py-2 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-xs font-semibold">Please enter all 6 digits.</span>
                    </div>
                @enderror

                <button type="submit"
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg 
                           text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-orange-600 
                           hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500
                           transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    
                    <span wire:loading.remove wire:target="verify">Verify Account</span>
                    
                    <span wire:loading wire:target="verify" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Verifying...
                    </span>
                </button>
            </form>

            {{-- IMPROVED RESEND SECTION --}}
            <div class="mt-8 text-center space-y-3">
                
                {{-- 1. Normal State --}}
                <div wire:loading.remove wire:target="resend">
                    <p class="text-sm text-gray-500">Didn't receive the code?</p>
                    <button wire:click="resend" type="button" class="mt-1 font-semibold text-orange-600 hover:text-orange-500 transition-colors">
                        Resend Code
                    </button>
                </div>

                {{-- 2. Loading State (While sending) --}}
                <div wire:loading wire:target="resend" class="text-sm text-orange-500 font-medium flex justify-center items-center gap-2">
                    <svg class="animate-spin h-4 w-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending new code...
                </div>

                {{-- 3. Success Message (Controlled by Flash Session) --}}
                @if (session()->has('resend_success'))
                    <div class="animate-fade-in-up mt-2 p-2 bg-green-50 rounded-lg border border-green-100 inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-green-600">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium text-green-700">New code sent! Check your inbox.</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Footer/Logout --}}
        <div class="mt-8 text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Wrong email address?</p>
            <form method="POST" action="{{ route('auth.logout') }}" class="inline-block mt-2">
                @csrf
                <button type="submit" class="text-sm font-medium text-gray-500 hover:text-gray-800 transition-colors flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    Log Out & Return
                </button>
            </form>
        </div>
    </div>
</div>