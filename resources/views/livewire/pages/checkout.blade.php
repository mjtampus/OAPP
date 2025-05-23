<div class="bg-gradient-to-b from-gray-50 to-white min-h-screen py-8">
    <div class="checkout-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold mb-2 text-gray-800">Checkout</h1>
        <p class="text-gray-600 mb-8">Complete your purchase securely</p>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Order Summary -->
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                    <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-800">Order Summary</h2>
                    </div>
                    
                    @if(count($cartItems) > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach($cartItems as $item)
                                <div class="flex items-start justify-between p-6 transition hover:bg-gray-50">
                                    <div class="flex items-center">
                                        <div class="relative">
                                            <img src="{{Storage::url($item['image'])}}"  alt="{{ $item['name'] }}" class="w-20 h-20 object-cover rounded-lg shadow-sm">
                                            <span class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center">
                                                {{ $item['quantity'] }} 
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="font-medium text-gray-900">{{ $item['name'] }}</h3>
                                            <p class="text-gray-600 mt-1">₱{{ number_format($item['price'], 2) }}</p>
                                        
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center">
                                            <button 
                                                wire:click="decrementQuantity({{ $item['id'] }})" 
                                                class="w-8 h-8 rounded-full flex items-center justify-center border border-gray-300 hover:bg-gray-100"
                                                
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                                </svg>
                                            </button>
                                            
                                            <span class="w-10 text-center text-gray-800">{{ $item['quantity'] }}</span>
                                            
                                            <button 
                                                wire:click="incrementQuantity({{ $item['id'] }})" 
                                                class="w-8 h-8 rounded-full flex items-center justify-center border border-gray-300 hover:bg-gray-100"
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <button 
                                            wire:click="removeItem({{ $item['id'] }})"
                                            wire:loading.attr="disabled"
                                            class="text-red-500 hover:text-red-700 transition"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="p-6 bg-gray-50">
                            <div class="space-y-3">
                                <div class="flex justify-between text-gray-600">
                                    <span>Subtotal</span>
                                    <span>₱{{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Tax</span>
                                    <span>₱{{ number_format($tax, 2) }}</span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span>Shipping</span>
                                    <span>₱{{ number_format($shipping, 2) }}</span>
                                </div>
                                <div class="h-px bg-gray-200 my-2"></div>
                                <div class="flex justify-between font-bold text-lg text-gray-900">
                                    <span>Total</span>
                                    <span>₱{{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-8 flex flex-col items-center justify-center text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-gray-600 mb-4">Your cart is empty.</p>
                            <a href="{{ route('shop') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Continue shopping
                            </a>
                        </div>
                    @endif
                </div>

            </div>
            
            <!-- Right Column: Checkout Form -->
            <div class="lg:col-span-1 order-1 lg:order-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden sticky top-6">
                    <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                        <h2 class="text-xl font-semibold text-gray-800">Shipping Information</h2>
                    </div>
                    
                    <div class="p-6">
                        <form wire:submit.prevent="placeOrder">
                            <div class="space-y-6">
                                <!-- Personal Information -->
                                <div>
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                            <input type="text" id="firstName" wire:model.defer="firstName" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                placeholder="John">
                                            @error('firstName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                            <input type="text" id="lastName" wire:model.defer="lastName" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                placeholder="Doe">
                                            @error('lastName') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" id="email" wire:model.defer="email" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            placeholder="john.doe@example.com">
                                        @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                        <input type="text" id="phone" wire:model.defer="phone" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            placeholder="(123) 456-7890">
                                        @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <!-- Address Section -->
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Delivery Address</h3>
                                    
                                    <div class="mb-4">
                                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                                        <input type="text" id="address" wire:model.defer="address" 
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            placeholder="123 Main St, Apt 4B">
                                        @error('address') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <div>
                                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                            <input type="text" id="city" wire:model.defer="city" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                placeholder="New York">
                                            @error('city') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                                            <input type="text" id="state" wire:model.defer="state" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                placeholder="NY">
                                            @error('state') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-4 mb-6">
                                        <div>
                                            <label for="postalCode" class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                            <input type="text" id="postalCode" wire:model.defer="postalCode" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                placeholder="10001">
                                            @error('postalCode') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        <div>
                                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                            <select id="country" wire:model.defer="country" 
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                <option value="">Select country</option>
                                                <option value="PH">Philippines</option>
                                                <option value="UK">United Kingdom</option>
                                            </select>
                                            @error('country') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Payment Method Section -->
                                <div x-data="{ paymentMethod: @entangle('paymentMethod') }">
                                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Method</h3>
                                    
                                    <div class="mb-4 space-y-3">
                                        <!-- Credit Card Option -->
                                        <div class="relative flex items-center p-4 border rounded-lg transition-all" 
                                            :class="{ 'border-blue-500 bg-blue-50': paymentMethod === 'credit_card', 'border-gray-200': paymentMethod !== 'credit_card' }">
                                            <input id="credit_card" type="radio" value="credit_card" wire:model="paymentMethod" 
                                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <label for="credit_card" class="ml-3 flex items-center cursor-pointer w-full">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-700">Credit Card</span>
                                                </div>
                                            </label>
                                        </div>
                                
                                        <!-- E-Wallet Option -->
                                        <div class="relative flex items-center p-4 border rounded-lg transition-all" 
                                            :class="{ 'border-blue-500 bg-blue-50': paymentMethod === 'E_wallet', 'border-gray-200': paymentMethod !== 'E_wallet' }">
                                            <input id="E_wallet" type="radio" value="E_wallet" wire:model="paymentMethod" 
                                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <label for="E_wallet" class="ml-3 flex items-center cursor-pointer w-full">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-700">E wallet</span>
                                                </div>
                                            </label>
                                        </div>
                                
                                        <!-- Cash on Delivery Option -->
                                        <div class="relative flex items-center p-4 border rounded-lg transition-all" 
                                            :class="{ 'border-blue-500 bg-blue-50': paymentMethod === 'COD', 'border-gray-200': paymentMethod !== 'COD' }">
                                            <input id="COD" type="radio" value="COD" wire:model="paymentMethod" 
                                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                            <label for="COD" class="ml-3 flex items-center cursor-pointer w-full">
                                                <div class="flex items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-700">Cash on Delivery</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                
                                    <!-- Show E-Wallet options when selected -->
                                    <div x-show="paymentMethod === 'E_wallet'" class="mb-6 ml-12 space-y-4">
                                        <h4 class="text-sm font-medium text-gray-700 mb-3">Select E-Wallet Provider</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="relative rounded-lg border p-4 transition-all cursor-pointer"
                                                :class="{ 'border-blue-500 bg-green-50': $wire.eWalletType === 'gcash' }"
                                                x-on:click="$wire.set('eWalletType', 'gcash')">
                                                <span class="text-sm font-medium">GCash</span>
                                            </div>
                                            <div class="relative rounded-lg border p-4 transition-all cursor-pointer"
                                                :class="{ 'border-green-500 bg-blue-50': $wire.eWalletType === 'paymaya' }"
                                                x-on:click="$wire.set('eWalletType', 'paymaya')">
                                                <span class="text-sm font-medium">Maya</span>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                                
                                
                                <div class="pt-4 border-t">
                                    <button type="submit" 
                                        wire:loading.attr="disabled"
                                        class="w-full flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <span wire:loading.remove wire:target="placeOrder">Complete Order</span>
                                        <span wire:loading wire:target="placeOrder" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Processing...
                                        </span>
                                    </button>
                                    
                                    <p class="mt-3 text-xs text-center text-gray-500">
                                        By placing your order, you agree to our 
                                        <a href="#" class="text-blue-600 hover:text-blue-800">Terms of Service</a> and 
                                        <a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a>.
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>