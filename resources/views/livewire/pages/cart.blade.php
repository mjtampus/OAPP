<div class="min-h-screen bg-gradient-to-b from-indigo-100 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <!-- Cart Header - Improved with more subtle gradient and micro-interactions -->
        <div class="bg-gradient-to-r from-violet-700 to-indigo-800 rounded-3xl p-8 mb-8 shadow-lg transform transition-all hover:shadow-2xl duration-300 overflow-hidden relative">
            <div class="absolute -bottom-8 -right-8 w-32 h-32 bg-white opacity-10 rounded-full"></div>
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-pink-400 to-purple-500"></div>
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-extrabold text-white tracking-tight">Shopping Cart</h1>
                    <p class="text-indigo-200 mt-2 font-light">{{ count($cartProducts) }} items in your cart</p>
                </div>
                <div class="hidden md:flex items-center space-x-2">
                    <div class="w-14 h-14 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>


            <!-- Loading Indicator - Improved with more subtle blur and animation -->
            <div 
            class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50 flex"
            wire:loading.class.remove="hidden"
        >
                <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full border border-indigo-50 flex flex-col items-center">
                    <div class="w-16 h-16 relative flex items-center justify-center">
                        <div class="w-12 h-12 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                        <div class="absolute inset-0 border-4 border-indigo-200 border-opacity-50 rounded-full animate-pulse"></div>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mt-4">Processing your request</h3>
                    <p class="text-gray-500 text-sm mt-1">This will only take a moment...</p>
                </div>
            </div>

            @if(count($cartProducts) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Cart Items Section - Improved card design and hover states -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-3xl shadow-xl p-8 overflow-hidden border border-indigo-50">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-violet-100 text-violet-700 w-10 h-10 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            Your Items
                        </h2>
                        <div class="space-y-6">
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" 
                                wire:model.live="selectAll"
                                class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="selectAll" class="text-gray-700">Select All</label>
                        </div>

                            
                            @foreach($cartProducts as $product)
                                <div x-data="{ checked: false }" 
                                @click="checked = !checked; $refs.checkbox.click()" 
                                  class="group bg-white rounded-2xl p-6 transition-all duration-300 hover:shadow-xl border border-gray-100 hover:border-indigo-100 relative overflow-hidden">
                                    <!-- Decorative pattern -->
                                    <input type="checkbox"
                                        x-ref="checkbox" x-model="checked"  
                                        wire:model.live="selectedProducts" 
                                        value="{{ $product['id'] }}" 
                                        class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                        @click.stop>
                                    <div class="absolute -right-16 -top-16 w-32 h-32 bg-gradient-to-br from-violet-100 to-transparent rounded-full opacity-0 group-hover:opacity-70 transition-all duration-500 transform scale-0 group-hover:scale-100"></div>
                                    
                                    <div class="flex gap-6 relative">
                                        <div class="w-28 sm:w-36 overflow-hidden rounded-2xl bg-gray-50 aspect-square shadow-sm group-hover:shadow-md transition-all duration-300">
                                            <img 
                                                src="{{ Storage::url($product['image']) }}"
                                                alt="{{ $product['name'] }}" 
                                                class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110"
                                            >
                                        </div>
                                        
                                        <div class="flex-1 flex flex-col justify-between">
                                            <div>
                                                <h3 class="text-lg text-gray-900 group-hover:text-violet-700 transition-colors duration-300">
                                                    <span class="font-bold">{{ $product['name'] }}</span> 
                                                    <span class="text-sm font-semibold text-gray-500">{{ $product['variant'] }}</span>
                                                </h3>                                                
                                                <p class="text-xs text-gray-500 mt-2 leading-relaxed">
                                                    {!! Str::limit($product['description'] , 200)  !!}
                                                </p>
                                            </div>
                                            
                                            <div class="mt-5 flex flex-wrap justify-between items-end gap-4">
                                                <div class="flex flex-col">
                                                    <span class="text-xs uppercase tracking-wider text-gray-500 font-medium">Price</span>
                                                    <div class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">
                                                        ₱{{ number_format($product['price'], 2) }}
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center gap-1 bg-gray-50 p-1 rounded-xl overflow-hidden border border-gray-100 group-hover:border-indigo-100 transition-colors duration-300">
                                                        <button 
                                                            wire:click="decrementQuantity({{ $product['id'] }})"
                                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-white shadow-sm hover:bg-violet-600 hover:text-white transition-all duration-300 focus:ring-2 focus:ring-violet-400 focus:outline-none"
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
                                                            class="w-9 h-9 flex items-center justify-center rounded-lg bg-white shadow-sm hover:bg-violet-600 hover:text-white transition-all duration-300 focus:ring-2 focus:ring-violet-400 focus:outline-none"
                                                        >
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <button 
                                                        wire:click="removeFromCart({{ $product['id'] }})"
                                                        class="relative overflow-hidden w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 transition-colors duration-300 focus:ring-2 focus:ring-red-400 focus:outline-none group"
                                                    >
                                                        <span class="absolute inset-0 bg-red-600 transform scale-0 group-hover:scale-100 transition-transform duration-300 rounded-lg"></span>
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

                <!-- Cart Summary Section - Improved with visual hierarchy and polish -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-3xl shadow-xl p-8 sticky top-8 overflow-hidden relative border border-indigo-50">
                        <!-- Decorative elements -->
                        <div class="absolute -right-16 -top-16 w-48 h-48 bg-gradient-to-br from-indigo-100 to-transparent rounded-full opacity-50"></div>
                        <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-gradient-to-tl from-violet-100 to-transparent rounded-full opacity-40"></div>
                        
                        <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                            <span class="bg-indigo-100 text-indigo-700 w-10 h-10 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </span>
                            Order Summary
                        </h2>
                        
                        <!-- Summary Details -->
                        <div class="space-y-4 mt-8 relative bg-indigo-50 bg-opacity-50 p-6 rounded-2xl">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium text-gray-900">₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="font-medium text-xs text-red-600">{{$shipping }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax</span>
                                <span class="font-medium text-xs text-red-600">{{ $tax }}</span>
                            </div>

                            <!-- Total -->
                            <div class="border-t border-dashed border-indigo-200 pt-4 mt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-base font-semibold text-gray-900">Total</span>
                                    <div class="text-2xl font-bold bg-gradient-to-r from-violet-600 to-indigo-600 bg-clip-text text-transparent">
                                        ₱{{ number_format($total, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons - Improved with better transitions and feedback -->
                        <div class="mt-8 space-y-4">
                        <button 
                            wire:click="checkout"
                            @if(!$selectedCartProducts) disabled 
                            @endif
                            class="w-full px-6 py-4 rounded-xl text-white flex items-center justify-center bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-violet-400 relative overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed">
                            
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-pink-500 to-purple-500 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
                            <span class="relative flex items-center">
                                <span wire:loading.remove wire:target="checkout" class="mr-2 font-medium">Proceed to Checkout</span>
                                <span wire:loading wire:target="checkout" class="mr-2 font-medium">Processing...</span>

                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </button>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('shop') }}" class="block"> 
                                    <button class="w-full px-6 py-3 rounded-xl text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all duration-300 hover:shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-gray-300 relative overflow-hidden group">
                                        <span class="absolute inset-0 w-full h-full bg-gray-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></span>
                                        <span class="relative flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                            </svg>
                                            Continue Shopping
                                        </span>
                                    </button>
                                </a>
                                <button 
                                    wire:click="clearCart"
                                    class="w-full px-6 py-3 rounded-xl text-sm text-red-700 bg-red-100 hover:bg-red-200 transition-all duration-300 hover:shadow-lg flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-red-300 relative overflow-hidden group"
                                >
                                    <span class="absolute inset-0 w-full h-full bg-red-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></span>
                                    <span class="relative flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Clear Cart
                                    </span>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Secure checkout badge - More prominent and trustworthy -->
                        <div class="mt-8 flex items-center justify-center text-xs bg-green-50 p-3 rounded-xl border border-green-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span class="text-green-800">Secure checkout with SSL encryption</span>
                        </div>
                    </div>
                </div>
            </div>

            @else
            <!-- Empty Cart State - More inviting and visually appealing -->
            <div class="text-center py-20 bg-white rounded-3xl shadow-xl transform transition-all duration-500 border border-indigo-50 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-indigo-50 to-white opacity-50"></div>
                <div class="relative z-10">
                    <div class="relative mx-auto w-40 h-40 mb-8">
                        <div class="absolute inset-0 bg-indigo-100 rounded-full animate-pulse"></div>
                        <div class="absolute inset-0 bg-gradient-to-br from-violet-100 to-indigo-100 rounded-full animate-ping opacity-30"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-40 w-40 text-indigo-500 relative animate-bounce" style="animation-duration: 3s;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">Your Cart is Empty</h3>
                    <p class="text-gray-600 mb-10 max-w-md mx-auto leading-relaxed">Looks like you haven't added any items to your cart yet. Discover our amazing products and start shopping!</p>
                    <a href="{{ route('shop') }}" class="inline-flex items-center px-8 py-4 rounded-xl text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-violet-400 relative overflow-hidden group">
                        <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-pink-500 to-purple-500 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></span>
                        <span class="relative flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            Start Shopping
                        </span>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>