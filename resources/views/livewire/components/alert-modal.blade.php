<div>
@if($showModal)
<div 
    x-data="{ show: true }" 
    x-show="show"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    role="dialog"
    aria-modal="true"
    aria-labelledby="modal-title"
>
    <!-- Backdrop with enhanced blur effect -->
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="show = false; $wire.cancel()"
        class="fixed inset-0 bg-black/50 backdrop-blur-md"
    ></div>
    
    <!-- Modal container with improved animations -->
    <div class="flex items-center justify-center min-h-screen p-5">
        <div 
            x-show="show"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            @click.away="show = false; $wire.cancel()"
            class="relative bg-white dark:bg-gray-800 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700"
        >
            <!-- Glass morphism effect for header -->
            <div 
                x-show="show"
                x-transition:enter="transition ease-out duration-400 delay-200"
                x-transition:enter-start="opacity-0 transform -translate-y-5"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="bg-gradient-to-r from-violet-600 to-indigo-600 px-6 py-5 relative overflow-hidden"
            >
                <!-- Decorative elements -->
                <div class="absolute -top-8 -right-8 w-16 h-16 bg-white/10 rounded-full"></div>
                <div class="absolute top-10 right-10 w-8 h-8 bg-white/10 rounded-full"></div>
                
                <h3 id="modal-title" class="text-xl font-semibold text-white relative z-10">{{ $title ?? 'Confirmation' }}</h3>
                <p class="text-indigo-100 text-sm mt-1 opacity-80">{{ $subtitle ?? 'Please confirm your action' }}</p>
            </div>
            
            <!-- Modal content with better spacing -->
            <div 
                x-show="show"
                x-transition:enter="transition ease-out duration-400 delay-300"
                x-transition:enter-start="opacity-0 transform translate-y-5"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="px-6 py-5 space-y-3"
            >
                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $message }}</p>
            </div>
            
            <!-- Modal footer with improved button design -->
            <div 
                x-show="show"
                x-transition:enter="transition ease-out duration-400 delay-400"
                x-transition:enter-start="opacity-0 transform translate-y-5"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="px-6 py-4 bg-gray-50 dark:bg-gray-750 flex justify-end gap-3 border-t border-gray-100 dark:border-gray-700"
            >
                <button 
                    wire:click="cancel" 
                    class="px-4 py-2 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-500"
                >
                    Cancel
                </button>
                <button 
                    wire:click="confirm" 
                    class="px-5 py-2 bg-gradient-to-r from-violet-600 to-indigo-600 rounded-xl text-white transition-all duration-200 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30 relative overflow-hidden group"
                >
                    <span class="relative z-10">Confirm</span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-indigo-600 to-violet-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                </button>
            </div>
            
            <!-- Close button with hover animation -->
            <button 
                x-show="show"
                x-transition:enter="transition ease-out duration-400 delay-500"
                x-transition:enter-start="opacity-0 transform rotate-90"
                x-transition:enter-end="opacity-100 transform rotate-0"
                wire:click="cancel" 
                class="absolute top-4 right-4 text-white/80 hover:text-white transition-all duration-200 transform hover:rotate-90 focus:outline-none focus:ring-2 focus:ring-white/50 rounded-full p-1"
                aria-label="Close modal"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
@endif

<!-- Improved loading indicator -->
<div wire:loading.class.remove="hidden" wire:target="confirm" class="fixed hidden inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-xl flex flex-col items-center justify-center text-center w-64 border border-gray-100 dark:border-gray-700">
        <div class="relative">
            <!-- Outer spinner -->
            <div class="w-16 h-16 border-4 border-indigo-100 dark:border-gray-700 border-dashed rounded-full animate-spin"></div>
            <!-- Inner spinner -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-10 h-10 border-4 border-t-4 border-indigo-600 dark:border-indigo-500 rounded-full animate-spin"></div>
            </div>
        </div>
        <p class="mt-4 text-gray-700 dark:text-gray-300 font-medium">Processing...</p>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Please wait a moment</p>
    </div>
</div>
</div>