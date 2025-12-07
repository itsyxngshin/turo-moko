<div class="relative">
            <input :type="show ? 'text' : 'password'"
                  id="password"
                  wire:model="password"
                  placeholder="Enter your password here" 
                  class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400">

            <!-- Toggle button -->
            <button type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                
                <!-- Eye (when hidden) -->
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>

                <!-- Eye-off (when visible) -->
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                          0.65-2.072 2.005-3.823 3.8-4.95M9.88 9.88A3 3 0 1114.12 14.12
                          M6.1 6.1L17.9 17.9" />
                </svg>
            </button>
</div>