<div>
    <!-- Sidebar Overlay -->
    <div 
        x-data="{ show: @entangle('showSidebar') }"
        x-cloak
        x-show="show" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm z-40"
        @click="show = false"
    ></div>

    <!-- Sidebar Panel -->
    <div 
        x-data="{ show: @entangle('showSidebar') }"
        x-cloak
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        :class="{ 'hidden': !show }"
        @class([
            'fixed right-0 top-0 h-full w-full max-w-xl bg-white shadow-xl z-50 transform rounded-l-lg',
        ])
    >
        <div class="flex flex-col h-full">
            <!-- Header -->
            <div class="p-5 bg-gradient-to-r from-blue-400 to-purple-800 text-white rounded-tl-lg">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h2 class="text-xl font-bold">Your Orders</h2>
                    </div>
                    <button @click="show = false" class="text-white hover:text-gray-200 transition-colors p-1 rounded-full hover:bg-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Orders List -->
            <div class="overflow-y-auto flex-grow">
                @if(count($orders) > 0)
                    <div class="py-3 px-5 bg-gray-50 border-b">
                        <p class="text-sm text-gray-500">Showing your recent orders</p>
                    </div>
                    @foreach($orders as $order)
                        <div class="p-5 border-b hover:bg-blue-50 transition-colors cursor-pointer">
                            <div class="flex justify-between items-center mb-3">
                                <span class="font-semibold text-gray-900">Order: {{ $order->order_number }}</span>
                                <span class="px-3 py-1 rounded-full text-xs font-medium 
                                    @if($order->order_status == 'delivered') bg-green-100 text-green-800
                                    @elseif($order->order_status == 'shipped') bg-blue-100 text-blue-800
                                    @elseif($order->order_status == 'processing') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucwords($order->order_status) }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between text-sm text-gray-500 mb-3">
                                <div class="flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ $order->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <span>{{ $order->items->count() }} {{ $order->items->count() > 1 ? 'items' : 'item' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center mt-2">
                                <span class="font-bold text-lg text-gray-900">₱{{ number_format($order->amount, 2) }}</span>
                                <button 
                                    wire:click="viewOrderDetails({{ $order->id }})"
                                    class="px-4 py-2 text-sm bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Details
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-10 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No Orders Found</h3>
                        <p class="text-gray-500">You haven't placed any orders yet.</p>
                    </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="p-5 bg-gray-50 mt-auto">
                <button 
                    wire:click="viewAllOrders"
                    class="block w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white text-center rounded-lg transition-colors font-medium flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Track Orders
                </button>
            </div>
        </div>
    </div>
</div>