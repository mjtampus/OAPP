<!-- resources/views/livewire/popup-order-items-sidebar.blade.php -->
<div>
    <!-- Popup Sidebar - Hidden by default, shown when isVisible is true -->
    <div class="fixed inset-0 z-40 flex items-start"
        x-data="{ show: @entangle('isVisible') }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" 
             @click="show = false"
             wire:click="close"></div>
        
        <!-- Sidebar Content - Left sidebar that slides in from right -->
        <div class="relative z-30 bg-white h-screen w-full md:w-[480px] lg:w-[576px] shadow-xl transform transition-transform"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            
            <!-- Sidebar Header -->
            <div class="flex justify-between items-center p-4 border-b bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Order Items
                    @if(!empty($orderItems) && isset($orderItems[0]))
                        <span class="text-sm font-normal text-gray-500 ml-2">{{ $orderItems[0]->order_number }}</span>
                    @endif
                </h2>
                <button @click="show = false" wire:click="close" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Search and Filter -->
            <div class="p-4 border-b bg-white sticky top-0 z-10">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.debounce.300ms="searchQuery" 
                        placeholder="Search items..." 
                        class="w-full pl-10 p-2.5 border border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500"
                    >
                </div>
                
                <div class="flex gap-2 mt-3">
                    <select wire:model="sortBy" class="text-sm rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                        <option value="created_at_desc">Newest First</option>
                        <option value="created_at_asc">Oldest First</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                    </select>
                </div>
            </div>
            
            <!-- Order Summary -->
            @if(!empty($orderItems) && isset($orderItems[0]))
                <div class="bg-blue-50 p-4 border-b">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-xs font-medium text-blue-600">Order: {{ $orderItems[0]->order_number }}</span>
                            <div class="flex items-center mt-1">
                                <span class="text-xs px-2 py-1 rounded-full 
                                    @if($orderItems[0]->order_status == 'delivered') bg-green-100 text-green-800
                                    @elseif($orderItems[0]->order_status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($orderItems[0]->order_status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif"
                                >
                                    {{ ucfirst($orderItems[0]->order_status) }}
                                </span>
                                <span class="text-xs text-gray-500 ml-2">{{ \Carbon\Carbon::parse($orderItems[0]->created_at)->format('M d, Y') }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500">Total Amount</span>
                            <div class="text-lg font-bold text-gray-800">₱{{ number_format($orderItems[0]->amount, 2) }}</div>
                            <span class="text-xs text-gray-500">via {{ ucfirst($orderItems[0]->payment_method) }}</span>
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Order Items List -->
            <div class="overflow-y-auto" style="height: calc(100vh - 250px);">
                @forelse($orderItems as $order)
                    @foreach($order->items as $item)
                        <div 
                            wire:key="item-{{ $item->id }}" 
                            wire:click="selectItem({{ $item->id }})"
                            class="p-4 border-b cursor-pointer hover:bg-gray-50 transition duration-150"
                        >
                            <div class="flex justify-between items-start">
                                <div class="flex items-start space-x-3">
                                    <div class="bg-gray-100 rounded w-12 h-12 flex items-center justify-center text-gray-400">
                                        <img src="{{ Storage::url ( $item->sku->sku_image_dir) }}" alt="" class="w-full">
                                    </div>
                                    <div>
                                        <h3 class="font-medium text-gray-800">{{ $item->product->name }}</h3>
                                        <p class="text-xs text-gray-500">SKU: {{ $item->sku->sku}}</p>
                                        <p class="text-xs text-gray-500">Variant: {{ $item->sku_label}}</p>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm font-medium">
                                        ₱{{ number_format($item->price, 2) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between mt-2">
                                <div class="flex items-center">
                                    <span class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full">
                                        Qty: {{ $item->quantity }}
                                    </span>                            
                                        <span class="text-xs ml-2 font-medium text-gray-400">
                                            ₱{{ number_format($item->price, 2) }}
                                        </span>                                  
                                </div>
                                <button 
                                    wire:click.stop="viewItemDetails({{ $item->id }})"
                                    class="text-xs text-blue-600 hover:text-blue-800 hover:underline"
                                >
                                    View Details
                                </button>
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="text-center py-12 px-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No items found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter to find what you're looking for.</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Footer Actions -->
            @if(!empty($orderItems) && isset($orderItems[0]))
                <div class="border-t p-4 bg-gray-50 sticky bottom-0">
                    <div class="flex justify-end space-x-2">
                        <button 
                            wire:click="close" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50"
                        >
                            Close
                        </button>
                        <button 
                            wire:click="printOrderDetails"
                            class="px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Print Details
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>