<div>
    @if($showToast)
        <div class="fixed top-20 right-5 z-50 space-y-3 w-[350px]">
            @foreach($messages as $msg)
                <div 
                    x-data="{ 
                        show: true, 
                        progress: 100,
                        interval: null,
                        startCountdown() {
                            const totalDuration = {{ $msg['duration'] }};
                            const stepTime = 50; // update every 50ms for smooth animation
                            const steps = totalDuration / stepTime;
                            const decrement = 100 / steps;
                            
                            this.interval = setInterval(() => {
                                this.progress -= decrement;
                                if (this.progress <= 0) {
                                    clearInterval(this.interval);
                                }
                            }, stepTime);
                        }
                    }"
                    x-show="show"
                    x-init="
                        startCountdown();
                        setTimeout(() => { 
                            show = false;
                            clearInterval(interval);
                            $wire.removeMessage('{{ $msg['id'] }}')
                        }, {{ $msg['duration'] }})
                    "
                    x-transition:enter="transform ease-out duration-300 transition"
                    x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:translate-x-4"
                    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg pointer-events-auto overflow-hidden"
                >
                    <div class="relative">
                        <!-- Colored top border -->
                        <div class="h-1 {{ 
                            $msg['type'] === 'success' ? 'bg-green-500' : 
                            ($msg['type'] === 'error' ? 'bg-red-500' : 
                            ($msg['type'] === 'warning' ? 'bg-yellow-500' : 'bg-blue-500')) 
                        }}"></div>
                        
                        <div class="p-4 flex items-start">
                            <!-- Icon Section with colored circle background -->
                            <div class="flex-shrink-0 mr-3">
                                <div class="rounded-full p-1 {{ 
                                    $msg['type'] === 'success' ? 'bg-green-100 dark:bg-green-900/30' : 
                                    ($msg['type'] === 'error' ? 'bg-red-100 dark:bg-red-900/30' : 
                                    ($msg['type'] === 'warning' ? 'bg-yellow-100 dark:bg-yellow-900/30' : 'bg-blue-100 dark:bg-blue-900/30')) 
                                }}">
                                    @if($msg['type'] === 'success')
                                        <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($msg['type'] === 'error')
                                        <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($msg['type'] === 'warning')
                                        <svg class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Message Section -->
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $msg['message'] }}
                                </p>
                                @if(isset($msg['description']))
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $msg['description'] }}
                                </p>
                                @else
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $msg['type'] === 'success' ? 'Operation completed' : 
                                    ($msg['type'] === 'error' ? 'An error occurred' : 
                                    ($msg['type'] === 'warning' ? 'Warning notice' : 'Information')) }}
                                </p>
                                @endif
                                
                                <!-- Time indicator -->
                                <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="mr-1 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="Math.ceil((progress / 100) * ({{ $msg['duration'] }} / 1000)) + 's'"></span>
                                </div>
                            </div>
                            
                            <!-- Close Button Section -->
                            <button
                                @click="show = false; clearInterval(interval); $wire.removeMessage('{{ $msg['id'] }}')"
                                class="ml-3 flex-shrink-0 text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition duration-150 rounded-full p-1 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-400"
                                aria-label="Close"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Advanced progress bar -->
                        <div class="absolute bottom-0 left-0 w-full h-1 bg-gray-200 dark:bg-gray-700">
                            <div 
                                class="h-full {{ 
                                    $msg['type'] === 'success' ? 'bg-green-500' : 
                                    ($msg['type'] === 'error' ? 'bg-red-500' : 
                                    ($msg['type'] === 'warning' ? 'bg-yellow-500' : 'bg-blue-500')) 
                                }} transition-all duration-200 ease-linear"
                                x-bind:style="'width: ' + progress + '%'"
                            ></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>