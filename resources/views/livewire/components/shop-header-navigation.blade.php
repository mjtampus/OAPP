<nav class="bg-white/70 backdrop-blur-md shadow-lg py-4 sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto flex justify-between items-center px-6">
            <a href="/" class="text-3xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent hover:scale-105 transition-transform">
                SHOP NI MICHAEL
            </a>
            <div class="hidden md:flex space-x-8">
                <a href="/shop" wire:navigate.hover class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group">
                    Shop
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/cart" class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group flex items-center">
                    @if($cartCount > 0)
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0" y="0" viewBox="0 0 128 128" class="w-6 h-6">
                        <path fill="currentColor" d="M19.3 14.4c-.3-1.3-1.4-2.2-2.7-2.2H2.7C1.2 12.3 0 13.5 0 15c0 1.5 1.2 2.7 2.7 2.7h11.7l25.8 74.1c.3 1.3 1.4 2.2 2.7 2.2h71.6v-5.4H45.1L19.3 14.4zm92.4 68.7L128 34H32.7L49 83h62.7zm-60-5.5-1.6-5.4h1.6v5.4zm57.2-29.9h11.2V45h-11.2v-5.4h10.9l-3.9 13.6h-7v-5.5zm0 8.1h6.2l-1.6 5.4h-4.7v-5.4zm0 8.2h3.9l-1.6 5.4h-2.3V64zm0 8.2h1.6l-1.6 5.4v-5.4zM98 39.5h8.2v5.4H98v-5.4zm0 8.2h8.2v5.4H98v-5.4zm0 8.1h8.2v5.4H98v-5.4zm0 8.2h8.2v5.4H98V64zm0 8.2h8.2v5.4H98v-5.4zM87.2 39.5h8.2v5.4h-8.2v-5.4zm0 8.2h8.2v5.4h-8.2v-5.4zm0 8.1h8.2v5.4h-8.2v-5.4zm0 8.2h8.2v5.4h-8.2V64zm0 8.2h8.2v5.4h-8.2v-5.4zM76.3 39.5h8.2v5.4h-8.2v-5.4zm0 8.2h8.2v5.4h-8.2v-5.4zm0 8.1h8.2v5.4h-8.2v-5.4zm0 8.2h8.2v5.4h-8.2V64zm0 8.2h8.2v5.4h-8.2v-5.4zM65.4 39.5h8.2v5.4h-8.2v-5.4zm0 8.2h8.2v5.4h-8.2v-5.4zm0 8.1h8.2v5.4h-8.2v-5.4zm0 8.2h8.2v5.4h-8.2V64zm0 8.2h8.2v5.4h-8.2v-5.4zM54.5 39.5h8.2v5.4h-8.2v-5.4zm0 8.2h8.2v5.4h-8.2v-5.4zm0 8.1h8.2v5.4h-8.2v-5.4zm0 8.2h8.2v5.4h-8.2V64zm0 8.2h8.2v5.4h-8.2v-5.4zM40.9 39.5h10.9v5.4H40.6v2.7h11.2V53h-7l-3.9-13.5zm4.6 16.3h6.2v5.4H47l-1.5-5.4zm6.2 8.2v5.4h-2.3L47.9 64h3.8zm50.4 32.7c-5.3 0-9.5 4.3-9.5 9.5s4.3 9.5 9.5 9.5c5.3 0 9.5-4.3 9.5-9.5s-4.2-9.5-9.5-9.5zm-51.7 0c-5.3 0-9.5 4.3-9.5 9.5s4.3 9.5 9.5 9.5c5.3 0 9.5-4.3 9.5-9.5s-4.3-9.5-9.5-9.5z"/>
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
                    <!-- If user is logged in, show account icon -->
                    <a href="/profile" class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14c4.418 0 8 1.79 8 4v2H4v-2c0-2.21 3.582-4 8-4z" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                        Profile
                    </a>
                @else
                    <!-- If user is NOT logged in, show Login button -->
                    <a href="/login" wire:navigate.hover class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group">
                        Login
                        <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                @endauth

            </div>
            <button class="md:hidden text-gray-700 hover:text-blue-600 transition-colors duration-300" id="toggle-menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </nav>
    