<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <!-- Cart Header -->
        <div class="bg-gradient-to-r from-violet-600 to-indigo-600 rounded-2xl p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white">Shopping Cart</h1>
            <p class="text-indigo-100 mt-2">{{ count($cartProducts) }} items in your cart</p>
        </div>

        @if(count($cartProducts) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Cart Items Section -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="space-y-4">
                        @foreach($cartProducts as $product)
                            <div class="group bg-white rounded-xl p-4 transition-all duration-300 hover:shadow-2xl border border-gray-100">
                                <div class="flex gap-4">
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
                                            <div class="text-xl font-bold text-gray-900">
                                            ₱{{ number_format($product['price'], 2) }}
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg">
                                                    <button 
                                                        wire:click="decrementQuantity({{ $product['id'] }})"
                                                        wire:loading.attr="disabled"
                                                        class="w-8 h-8 flex items-center justify-center rounded-md bg-white shadow-sm hover:bg-violet-600 hover:text-white transition-all duration-300"
                                                    >
                                                        -
                                                    </button>
                                                    <span class="w-10 text-center text-base font-medium">
                                                        {{ $product['quantity'] }}
                                                    </span>
                                                    <button 
                                                        wire:click="incrementQuantity({{ $product['id'] }})"
                                                        wire:loading.attr="disabled"
                                                        class="w-8 h-8 flex items-center justify-center rounded-md bg-white shadow-sm hover:bg-violet-600 hover:text-white transition-all duration-300"
                                                    >
                                                        +
                                                    </button>
                                                </div>
                                                <button 
                                                    wire:click="removeFromCart({{ $product['id'] }})"
                                                    wire:loading.attr="disabled"
                                                    class="text-red-500 hover:text-red-600 transition-colors duration-300"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                <div class="bg-white rounded-2xl shadow-xl p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Order Summary</h2>
                    
                    <!-- Summary Details -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Subtotal</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Shipping</span>
                            <span>₱{{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Tax</span>
                            <span>₱{{ number_format($tax, 2) }}</span>
                        </div>

                        <!-- Total -->
                        <div class="border-t border-gray-100 pt-3 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-gray-900">Total</span>
                                <span class="text-xl font-bold text-gray-900">₱{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        <button 
                            wire:click="checkout"
                            wire:loading.attr="disabled"
                            class="w-full px-6 py-3 rounded-xl text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-0.5"
                        >
                            <span wire:loading.remove wire:target="checkout">Proceed to Checkout</span>
                            <span wire:loading wire:target="checkout">Processing...</span>
                        </button>
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('shop') }}" class="block"> 
                                <button class="w-full px-6 py-3 rounded-xl text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all duration-300 hover:shadow-lg">
                                    Continue Shopping
                                </button>
                            </a>
                            <button 
                                wire:click="clearCart"
                                wire:loading.attr="disabled"
                                class="w-full px-6 py-3 rounded-xl text-sm text-red-700 bg-red-100 hover:bg-red-200 transition-all duration-300 hover:shadow-lg"
                            >
                                Clear Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @else
        <!-- Empty Cart State -->
        <div class="text-center py-16 bg-white rounded-2xl shadow-xl">
            <div class="w-24 h-24 mx-auto mb-6 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Your Cart is Empty</h3>
            <p class="text-gray-500 mb-6">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('shop') }}" class="inline-block px-6 py-3 rounded-xl text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-0.5">
                Start Shopping
            </a>
        </div>
        @endif

        <!-- Loading States Overlay -->
        <div wire:loading.delay class="fixed inset-0 bg-black bg-opacity-25 flex items-center justify-center z-50">
            <div class="bg-white p-4 rounded-lg shadow-xl">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-violet-600 mx-auto"></div>
                <p class="mt-2 text-gray-600">Processing...</p>
            </div>
        </div>
    </div>
</div>