<div class="min-h-screen bg-gradient-to-b from-indigo-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <!-- Cart Header -->
        <div class="bg-gradient-to-r from-violet-600 to-indigo-600 rounded-2xl p-6 mb-6 shadow-lg transform transition-all hover:shadow-xl duration-300">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-white">Shopping Cart</h1>
                    <p class="text-indigo-100 mt-2">{{ count($cartProducts) }} items in your cart</p>
                </div>
                <div class="hidden md:block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-indigo-200 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div 
            x-data="{ isProcessing: false }"
            x-init="
                window.addEventListener('livewire:load', () => {
                    Livewire.hook('message.sent', () => { isProcessing = true })
                    Livewire.hook('message.processed', () => { isProcessing = false })
                })
            "
        >
            <!-- Loading Indicator (Alpine.js based) -->
            <div 
                x-show="isProcessing" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm flex items-center justify-center z-50"
            >
                <div class="bg-white p-6 rounded-xl shadow-2xl transform transition-all scale-100 max-w-md w-full">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-10 h-10 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">Processing your request</h3>
                            <p class="text-gray-500 text-sm mt-1">This will only take a moment...</p>
                        </div>
                    </div>
                </div>
            </div>

            @if(count($cartProducts) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Cart Items Section -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl shadow-xl p-6 overflow-hidden">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <span class="bg-violet-100 text-violet-700 w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            Your Items
                        </h2>
                        <div class="space-y-5">
                            @foreach($cartProducts as $product)
                                <div class="group bg-white rounded-xl p-4 transition-all duration-300 hover:shadow-xl border border-gray-100 relative overflow-hidden">
                                    <!-- Decorative pattern -->
                                    <div class="absolute -right-10 -top-10 w-24 h-24 bg-gradient-to-br from-violet-100 to-transparent rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
                                    
                                    <div class="flex gap-4 relative">
                                        <div class="w-24 sm:w-32 overflow-hidden rounded-xl bg-gray-50 aspect-square">
                                            <img 
                                                src="{{ Storage::url($product['image']) }}"
                                                alt="{{ $product['name'] }}" 
                                                class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105"
                                            >
                                        </div>
                                        
                                        <div class="flex-1 flex flex-col justify-between">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-violet-600 transition-colors duration-300">
                                                    {{ $product['name'] }}
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    {!! $product['description'] !!}
                                                </p>
                                            </div>
                                            
                                            <div class="mt-4 flex flex-wrap justify-between items-end gap-4">
                                                <div class="flex flex-col">
                                                    <span class="text-xs text-gray-500">Price</span>
                                                    <div class="text-xl font-bold text-gray-900">
                                                        ₱{{ number_format($product['price'], 2) }}
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center gap-2">
                                                    <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg">
                                                        <button 
                                                            wire:click="decrementQuantity({{ $product['id'] }})"
                                                            class="w-8 h-8 flex items-center justify-center rounded-md bg-white shadow-sm hover:bg-violet-600 hover:text-white transition-all duration-300 focus:ring-2 focus:ring-violet-400 focus:outline-none"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                            </svg>
                                                        </button>
                                                        <span class="w-10 text-center text-base font-medium">
                                                            {{ $product['quantity'] }}
                                                        </span>
                                                        <button 
                                                            wire:click="incrementQuantity({{ $product['id'] }})"
                                                            class="w-8 h-8 flex items-center justify-center rounded-md bg-white shadow-sm hover:bg-violet-600 hover:text-white transition-all duration-300 focus:ring-2 focus:ring-violet-400 focus:outline-none"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <button 
                                                        wire:click="removeFromCart({{ $product['id'] }})"
                                                        class="relative overflow-hidden w-8 h-8 flex items-center justify-center rounded-md bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 transition-colors duration-300 focus:ring-2 focus:ring-red-400 focus:outline-none group"
                                                    >
                                                        <span class="absolute inset-0 bg-red-600 transform scale-0 group-hover:scale-100 transition-transform duration-300 rounded-md"></span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 relative z-10 group-hover:text-white transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Cart Summary Section -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-8 overflow-hidden relative">
                        <!-- Decorative elements -->
                        <div class="absolute -right-10 -top-10 w-40 h-40 bg-gradient-to-br from-indigo-100 to-transparent rounded-full opacity-50"></div>
                        
                        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <span class="bg-indigo-100 text-indigo-700 w-8 h-8 rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </span>
                            Order Summary
                        </h2>
                        
                        <!-- Summary Details -->
                        <div class="space-y-3 mt-6 relative">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($tax, 2) }}</span>
                            </div>

                            <!-- Total -->
                            <div class="border-t border-dashed border-gray-200 pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <div class="text-2xl font-bold bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent">
                                        ₱{{ number_format($total, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 space-y-4">
                            <button 
                                wire:click="checkout"
                                class="w-full px-6 py-3 rounded-xl text-white flex items-center justify-center bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-violet-400"
                            >
                                <span class="mr-2">Proceed to Checkout</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </button>
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('shop') }}" class="block"> 
                                    <button class="w-full px-6 py-3 rounded-xl text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all duration-300 hover:shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-gray-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        Continue Shopping
                                    </button>
                                </a>
                                <button 
                                    wire:click="clearCart"
                                    class="w-full px-6 py-3 rounded-xl text-sm text-red-700 bg-red-100 hover:bg-red-200 transition-all duration-300 hover:shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-red-300"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Clear Cart
                                </button>
                            </div>
                        </div>
                        
                        <!-- Secure checkout badge -->
                        <div class="mt-6 flex items-center justify-center text-xs text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Secure checkout with SSL encryption
                        </div>
                    </div>
                </div>
            </div>

            @else
            <!-- Empty Cart State -->
            <div class="text-center py-16 bg-white rounded-2xl shadow-xl transform transition-all duration-500">
                <div class="relative mx-auto w-32 h-32 mb-6">
                    <div class="absolute inset-0 bg-indigo-100 rounded-full animate-pulse"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 text-indigo-500 relative" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Your Cart is Empty</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Looks like you haven't added any items to your cart yet. Discover our amazing products and start shopping!</p>
                <a href="{{ route('shop') }}" class="inline-flex items-center px-6 py-3 rounded-xl text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-violet-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Start Shopping
                </a>
            </div>
            @endif
        </div>
    </div>
</div>