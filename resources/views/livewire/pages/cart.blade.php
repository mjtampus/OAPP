<div>
<div class="min-h-screen bg-gray-50">
    <div class="w-full px-4 sm:px-6 py-8">
        <!-- Cart Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl p-6 mb-6 shadow-lg">
            <h1 class="text-3xl font-bold text-white">Shopping Cart</h1>
            <p class="text-indigo-100 mt-2">Your selected items</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Cart Items Section -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-2xl shadow-xl p-6">
                    <div class="space-y-4">
                        @foreach($cartProducts as $product)
                            <div class="group bg-white rounded-xl p-4 transition-all duration-300 hover:shadow-2xl border border-gray-100 hover:border-indigo-100">
                                <div class="flex gap-4">
                                    <div class="w-24 sm:w-32 overflow-hidden rounded-xl bg-gray-50 aspect-square">
                                        <img 
                                            src="https://picsum.photos/400/300?random={{ $product->id }}"
                                            alt="Product Image" 
                                            class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-105"
                                        >
                                    </div>
                                
                                    
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">
                                                {{ $product->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 mt-1 group-hover:text-gray-600 transition-colors duration-300">
                                                High-quality premium item with exceptional craftsmanship
                                            </p>
                                        </div>
                                        
                                        <div class="mt-4 flex flex-wrap justify-between items-end gap-4">
                                            <div class="text-xl font-bold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">
                                                ${{ number_format(99.99 + ($product->id * 20), 2) }}
                                            </div>
                                            
                                            <div class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg group-hover:bg-indigo-50 transition-colors duration-300">
                                                <button class="w-7 h-7 flex items-center justify-center rounded-md bg-white shadow-sm hover:bg-indigo-600 hover:text-white transition-all duration-300">
                                                    -
                                                </button>
                                                <span class="w-10 text-center text-base font-medium">
                                                    
                                                {{ $product->quantity }}
                                                </span>
                                                <button href="" class="w-7 h-7 flex items-center justify-center rounded-md bg-white shadow-sm hover:bg-indigo-600 hover:text-white transition-all duration-300">
                                                    +
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
                            <span>$359.97</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Shipping</span>
                            <span>$9.99</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Tax</span>
                            <span>$36.00</span>
                        </div>

                        <!-- Discount Code Input -->
                        <div class="pt-3">
                            <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">Discount Code</label>
                            <div class="flex gap-2">
                                <input type="text" id="discount" class="flex-1 text-sm rounded-lg border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                                <button class="px-3 py-2 bg-gray-100 text-sm text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-300">
                                    Apply
                                </button>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="border-t border-gray-100 pt-3 mt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-base font-semibold text-gray-900">Total</span>
                                <span class="text-xl font-bold text-gray-900">$405.96</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-6 space-y-3">
                        <button class="w-full px-6 py-2.5 rounded-xl text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-0.5">
                            Proceed to Checkout
                        </button>
                    <a href="/shop" wire:navigate> 
                        <button class="w-full px-6 py-2.5 rounded-xl text-sm text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all duration-300 hover:shadow-lg">
                            Continue Shopping
                        </button>
                    </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
