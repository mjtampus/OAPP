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
            @auth
            <div class=" relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition-colors duration-300">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14c4.418 0 8 1.79 8 4v2H4v-2c0-2.21 3.582-4 8-4z"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>{{ Auth::user()->name }}</span>
 
                </button>
                <div 
                x-show="open" 
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                :class="{ 'hidden': !open }" 
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 hidden">

                <a href="/account" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">My Account</a>
                <a href="/orders" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600">Orders</a>

                <a wire:click.prevent="logout" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600" wire:loading.attr="disabled">
                    Logout
                </a>
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
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="md:hidden bg-white border-t mt-4">
        <div class="container mx-auto px-6 py-4">
            <a href="/shop" class="block py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">Shop</a>
            <a href="/cart" class="block py-2 text-gray-700 hover:text-blue-600" @click="mobileMenuOpen = false">Cart</a>
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
