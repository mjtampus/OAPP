<nav x-data="{ mobileMenuOpen: false }" class="bg-white/70 backdrop-blur-md shadow-lg py-4 sticky top-0 z-50 transition-all duration-300">
    <div class="container mx-auto flex justify-between items-center px-6">
        <a href="/" class="text-3xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent hover:scale-105 transition-transform">
            SHOP NI MICHAEL
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-8 items-center">
            <a href="/shop" wire:navigate.hover class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group">
                Shop
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="/cart" class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group flex items-center">
                @if($cartCount > 0)
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 128 128">
                    <path d="M19.3 14.4c-.3-1.3-1.4-2.2-2.7-2.2H2.7C1.2 12.3 0 13.5 0 15c0 1.5 1.2 2.7 2.7 2.7h11.7l25.8 74.1c.3 1.3 1.4 2.2 2.7 2.2h71.6v-5.4H45.1L19.3 14.4zm92.4 68.7L128 34H32.7L49 83h62.7zm-60-5.5-1.6-5.4h1.6v5.4zm57.2-29.9h11.2V45h-11.2v-5.4h10.9l-3.9 13.6h-7v-5.5zm0 8.1h6.2l-1.6 5.4h-4.7v-5.4zm0 8.2h3.9l-1.6 5.4h-2.3V64zm0 8.2h1.6l-1.6 5.4v-5.4z"/>
                </svg>
                <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs font-bold px-1.5 rounded-full">
                    {{ $cartCount }}
                </span>
                @else
                Cart
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                @endif
            </a>
<<<<<<< Updated upstream
=======
        </div>

        <!-- Account and notification on the right -->
        <div class="hidden md:flex space-x-6 items-center justify-end flex-1">
            @php
                use Illuminate\Support\Carbon;
            @endphp
            <!-- Notification Icon -->
            <div class="relative" x-data="{ notificationOpen: false }">
                <button @click="notificationOpen = !notificationOpen" @click.away="notificationOpen = false" class="relative text-gray-700 hover:text-blue-600 transition-colors duration-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if($notificationBadge > 0)
                        <!-- Notification Badge -->
                        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $notificationBadge }}
                        </span>
                    @endif
                </button>

                <!-- Notification Dropdown -->
                <div
                    x-show="notificationOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50">
                    <h3 class="px-4 py-2 font-medium text-gray-900 border-b">Order Updates</h3>
                    <div class="max-h-60 overflow-y-auto divide-y">
                        @forelse($orderNotifications as $notification)
                            <a href="{{ route('orders.index') }}" class="block px-4 py-3 hover:bg-gray-50">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-gray-900">
                                        Order {{ $notification['order_number'] }}
                                    </p>
                                    <span class="text-[11px] text-gray-400">
                                        {{ Carbon::parse($notification['updated_at'])->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="mt-1 flex items-center text-xs">
                                    @php
                                        $status = ucfirst(str_replace('_', ' ', $notification['status']));
                                        $statusColor = match ($notification['status']) {
                                            'delivered' => 'bg-green-100 text-green-700',
                                            'cancelled' => 'bg-red-100 text-red-700',
                                            'pending' => 'bg-yellow-100 text-yellow-700',
                                            'processing' => 'bg-blue-100 text-blue-700',
                                            'in_transit' => 'bg-purple-100 text-purple-700',
                                            'shipped' => 'bg-indigo-100 text-indigo-700',
                                            default => 'bg-gray-100 text-gray-700',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium {{ $statusColor }}">
                                        {{ $status }}
                                    </span>
                                </p>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-sm text-gray-500">
                                No recent order updates.
                            </div>
                        @endforelse
                    </div>
                    <div class="px-4 py-2 border-t">
                        <a href="{{ route('orders.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all orders</a>
                    </div>
                </div>
            </div>

            <!-- User Profile Menu -->
>>>>>>> Stashed changes
            @auth
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14c4.418 0 8 1.79 8 4v2H4v-2c0-2.21 3.582-4 8-4z"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
 
                </button>
<<<<<<< Updated upstream
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                    <a href="/account" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">My Account</a>
                    <a href="/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">Orders</a>
                    <a wire:click.prevent="logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600" wire:loading.attr="disabled">
                        Logout
                    </a>
=======
                <div
                x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">

                <a href="/account" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">My Account</a>
                <a href="#" wire:click.prevent="showOrders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">Orders</a>
                <a href="/settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">Settings</a>
                <div class="border-t my-1"></div>
                <a wire:click.prevent="logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600 cursor-pointer" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="logout">Logout</span>
                    <span wire:loading wire:target="logout">Processing...</span>
                </a>
>>>>>>> Stashed changes
                </div>
            </div>
            @else
            <a href="/login" wire:navigate.hover class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group">
                Login
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
            @endauth
        </div>

        <!-- Mobile Menu Button -->
        <button class="md:hidden text-gray-700 hover:text-blue-600 transition-colors duration-300" @click="mobileMenuOpen = !mobileMenuOpen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
<<<<<<< Updated upstream
    <div x-show="mobileMenuOpen" 
=======
    <div x-show="mobileMenuOpen"
         x-cloak
>>>>>>> Stashed changes
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t mt-4">
        <div class="container mx-auto px-6 py-4">
            <a href="/shop" class="block py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">Shop</a>
<<<<<<< Updated upstream
            <a href="/cart" class="block py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">Cart</a>
=======
            <a href="/cart" class="flex items-center justify-between py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">
                <span>Cart</span>
                @if($cartCount > 0)
                <span class="bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                @endif
            </a>

            <!-- Mobile Notification Link -->


>>>>>>> Stashed changes
            @auth
            <a href="/account" class="block py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">My Account</a>
            <a href="/orders" class="block py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">Orders</a>
            <button wire:click.prevent="logout" class="block w-full text-left py-2 text-gray-700 hover:text-blue-600" wire:loading.attr="disabled">
                Logout
            </button>
            @else
            <a href="/login" class="block py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">Login</a>
            @endauth
        </div>
    </div>
</nav>
