<nav x-data="{ mobileMenuOpen: false }" class="bg-white/70 backdrop-blur-md shadow-lg py-4 sticky top-0 z-50 transition-all duration-300">
    <div class="container mx-auto flex justify-between items-center px-6">
        <!-- Logo on the left -->
        <div class="flex-1">
            <a href="/" class="text-3xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent hover:scale-105 transition-transform">
                SHOP NI MICHAEL
            </a>
        </div>

        <!-- Navigation links in the middle -->
        <div class="hidden md:flex space-x-8 items-center justify-center flex-1">
            <a href="/" wire:navigate.hover class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group">
                Home
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="/shop" wire:navigate.hover class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group">
                Shop
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
            </a>
            <a href="/cart" class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group flex items-center">
                Cart
                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                @if($cartCount > 0)
                <span class="ml-1 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                    {{ $cartCount }}
                </span>
                @endif
            </a>
        </div>

        <!-- Account and notification on the right -->
        <div class="hidden md:flex space-x-6 items-center justify-end flex-1">
            <!-- Notification Icon -->
            <div class="relative" x-data="{ notificationOpen: false }">
                <button @click="notificationOpen = !notificationOpen" @click.away="notificationOpen = false" class="relative text-gray-700 hover:text-blue-600 transition-colors duration-300 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <!-- Notification Badge -->
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">3</span>
                </button>
                
                <!-- Notification Dropdown -->
                <div 
                    x-show="notificationOpen" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg py-2 z-50">
                    <h3 class="px-4 py-2 font-medium text-gray-900 border-b">Notifications</h3>
                    <div class="max-h-60 overflow-y-auto">
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b">
                            <p class="text-sm font-medium text-gray-900">Your order #12345 has been shipped</p>
                            <p class="text-xs text-gray-500 mt-1">2 minutes ago</p>
                        </a>
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50 border-b">
                            <p class="text-sm font-medium text-gray-900">New product available: Summer Collection</p>
                            <p class="text-xs text-gray-500 mt-1">2 hours ago</p>
                        </a>
                        <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                            <p class="text-sm font-medium text-gray-900">Flash sale starting in 24 hours!</p>
                            <p class="text-xs text-gray-500 mt-1">Yesterday</p>
                        </a>
                    </div>
                    <div class="px-4 py-2 border-t">
                        <a href="/notifications" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all notifications</a>
                    </div>
                </div>
            </div>
            
            <!-- User Profile Menu -->
            @auth
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors duration-300">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">

                <a href="/account" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">My Account</a>
                <a href="/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">Orders</a>
                <a href="/settings" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">Settings</a>
                <div class="border-t my-1"></div>
                <a wire:click.prevent="logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600 cursor-pointer" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="logout">Logout</span>
                    <span wire:loading wire:target="logout">Processing...</span>
                </a>
                </div>
            </div>
            @else
            <a href="/login" wire:navigate.hover class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-300">
                Login
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
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t mt-4">
        <div class="container mx-auto px-6 py-4 space-y-3">
            <a href="/" class="block py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">Home</a>
            <a href="/shop" class="block py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">Shop</a>
            <a href="/cart" class="flex items-center justify-between py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">
                <span>Cart</span>
                @if($cartCount > 0)
                <span class="bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                @endif
            </a>
            
            <!-- Mobile Notification Link -->
            <a href="/notifications" class="flex items-center justify-between py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">
                <span>Notifications</span>
                <span class="bg-red-600 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">3</span>
            </a>
            
            @auth
            <div class="border-t pt-2">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span class="font-medium">{{ Auth::user()->name }}</span>
                </div>
                <a href="/account" class="block py-2 text-gray-700 hover:text-blue-600 pl-11" @click="mobileMenuOpen = false">My Account</a>
                <a href="/orders" class="block py-2 text-gray-700 hover:text-blue-600 pl-11" @click="mobileMenuOpen = false">Orders</a>
                <a href="/settings" class="block py-2 text-gray-700 hover:text-blue-600 pl-11" @click="mobileMenuOpen = false">Settings</a>
                <button wire:click.prevent="logout" class="block w-full text-left py-2 text-gray-700 hover:text-blue-600 pl-11" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="logout">Logout</span>
                    <span wire:loading wire:target="logout">Processing...</span>
                </button>
            </div>
            @else
            <a href="/login" class="block w-full py-2 text-center rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-300" @click="mobileMenuOpen = false">Login</a>
            @endauth
        </div>
    </div>
</nav>