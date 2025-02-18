<div>
    @if($showToast)
        <div class="fixed top-20 right-4 z-50 space-y-4 w-[320px]">
            @foreach($messages as $msg)
                <div 
                    x-data="{ show: true }"
                    x-show="show"
                    x-init="setTimeout(() => { 
                        show = false;
                        $wire.removeMessage('{{ $msg['id'] }}')
                    }, {{ $msg['duration'] }})"
                    x-transition:enter="transform ease-out duration-300 transition"
                    x-transition:enter-start="translate-y-4 opacity-0 sm:translate-y-0 sm:translate-x-4"
                    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="max-w-sm w-full bg-white shadow-xl rounded-lg pointer-events-auto"
                >
                    <div class="p-4 flex items-start space-x-4">
                        <!-- Icon Section -->
                        <div class="flex-shrink-0">
                            @if($msg['type'] === 'success')
                                <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($msg['type'] === 'error')
                                <svg class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @elseif($msg['type'] === 'warning')
                                <svg class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            @else
                                <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        
                        <!-- Message Section -->
                        <div class="flex-1 space-y-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ $msg['message'] }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $msg['type'] === 'error' ? 'An error occurred' : 'Notification' }}
                            </p>
                        </div>
                        
                        <!-- Close Button Section -->
                        <div class="ml-3 flex-shrink-0">
                            <button
                                @click="show = false; $wire.removeMessage('{{ $msg['id'] }}')"
                                class="inline-flex text-gray-400 hover:text-gray-500 focus:outline-none"
                            >
                                <span class="sr-only">Close</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
