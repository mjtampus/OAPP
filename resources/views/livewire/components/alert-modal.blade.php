<div>
    @if($showModal)
    <div 
        x-data="{ show: true }" 
        x-show="show"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <!-- Backdrop with blur effect and fade in -->
        <div 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="show = false; $wire.cancel()"
            class="fixed inset-0 bg-black bg-opacity-40 backdrop-blur-sm"
        ></div>
        
        <!-- Modal container with slide and fade -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div 
                x-show="show"
                x-transition:enter="transition ease-out duration-300 delay-100"
                x-transition:enter-start="opacity-0 transform translate-y-10"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform translate-y-10"
                @click.away="show = false; $wire.cancel()"
                class="relative bg-white dark:bg-gray-800 w-full max-w-md rounded-xl shadow-2xl overflow-hidden"
            >
                <!-- Modal header with gradient and slide in from left -->
                <div 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-400 delay-200"
                    x-transition:enter-start="opacity-0 transform -translate-x-10"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4"
                >
                    <h3 class="text-lg font-medium text-white">{{ $title ?? 'Confirmation' }}</h3>
                </div>
                
                <!-- Modal content with fade up -->
                <div 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-400 delay-300"
                    x-transition:enter-start="opacity-0 transform translate-y-5"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="px-6 py-5"
                >
                    <p class="text-gray-700 dark:text-gray-300">{{ $message }}</p>
                </div>
                
                <!-- Modal footer with fade up -->
                <div 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-400 delay-400"
                    x-transition:enter-start="opacity-0 transform translate-y-5"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700 flex justify-end gap-3"
                >
                    <button 
                        wire:click="cancel" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200 transform hover:scale-105"
                    >
                        Cancel
                    </button>
                    <button 
                        wire:click="confirm" 
                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg text-white hover:opacity-90 transition-all duration-200 shadow-md transform hover:scale-105 hover:shadow-lg"
                    >
                        Confirm
                    </button>
                </div>
                
                <!-- Close buttom -->
                <button 
                    x-show="show"
                    x-transition:enter="transition ease-out duration-400 delay-500"
                    x-transition:enter-start="opacity-0 transform rotate-90"
                    x-transition:enter-end="opacity-100 transform rotate-0"
                    wire:click="cancel" 
                    class="absolute top-4 right-4 text-white hover:text-gray-200 transition-all duration-200 transform hover:rotate-90"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif
    <div  wire:loading.class.remove="hidden" wire:target="confirm" class="fixed hidden inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-md">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg flex flex-col items-center justify-center text-center w-64">
            <svg class="w-12 h-12 animate-spin text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M12 2v4m0 12v4m8-8h-4m-12 0H2m18.364-6.364l-2.828 2.828M6.364 17.636l-2.828 2.828m14.142 0l2.828-2.828M6.364 6.364L3.536 3.536" />
            </svg>
            <p class="mt-3 text-gray-700 dark:text-gray-300">Processing...</p>
        </div>
    </div>
    
</div>
</div>