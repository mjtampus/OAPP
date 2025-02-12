<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite('resources/css/app.css')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    

        <title>{{ $title ?? 'Page Title' }}</title>
        @livewireStyles
    </head>
    <body>

    <nav class="bg-white/70 backdrop-blur-md shadow-lg py-4 sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto flex justify-between items-center px-6">
            <a href="/home" class="text-3xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent hover:scale-105 transition-transform">
                SHOP NI MICHAEL
            </a>
            <div class="hidden md:flex space-x-8">
                <a href="/shop" wire:navigate.hover class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group">
                    Shop
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/cart" wire:navigate.hover class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group">
                    Cart
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="/login" wire:navigate.hover class="text-gray-700 hover:text-blue-600 transition-colors duration-300 relative group">
                    Login
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </div>
            <button class="md:hidden text-gray-700 hover:text-blue-600 transition-colors duration-300" id="toggle-menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </nav>

    <div id="mobile-menu" class="fixed bottom-0 left-1/2 transform -translate-x-1/2 translate-y-full w-64 bg-white transition-transform duration-300 ease-in-out z-50 shadow-2xl rounded-t-lg">
    <div class="p-6 relative">
        <!-- Menu Links -->
        <div class="flex flex-col space-y-4 mt-2 text-center">
            <a href="/shop" class="text-gray-700 hover:text-blue-600 transition-colors duration-300">Shop</a>
            <a href="/cart" class="text-gray-700 hover:text-blue-600 transition-colors duration-300">Cart</a>
            <a href="/login" class="text-gray-700 hover:text-blue-600 transition-colors duration-300">login</a>
        </div>
    </div>
</div>


        {{ $slot }}


    <footer class="bg-gray-900 text-gray-400 py-12">
        <div class="container mx-auto px-6 text-center">
            <p class="mb-4">&copy; 2025 eCommerce. All rights reserved.</p>
            <div class="flex justify-center space-x-6">
                <a href="#" class="hover:text-white transition-colors duration-300">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors duration-300">Terms of Service</a>
                <a href="#" class="hover:text-white transition-colors duration-300">Contact Us</a>
            </div>
        </div>
    </footer>
    @livewireScripts
    </body>

<script>
let isLoginActive = true;

function toggleForms() {
    const loginForm = document.getElementById("login-form");
    const registerForm = document.getElementById("register-form");
    const imageSection = document.getElementById("image-section");

    if (isLoginActive) {
        // Move register form into view
        registerForm.style.right = "0";
        loginForm.style.transform = "translateX(-100%)";

        // Move image to the left side
        imageSection.style.transform = "translateX(-100%)";
    } else {
        // Move the login form back into view
        registerForm.style.right = "-100%";
        loginForm.style.transform = "translateX(0)";

        // Move image back to the right side
        imageSection.style.transform = "translateX(0)";
    }

    isLoginActive = !isLoginActive;
}

        // Mobile Menu Toggle
    const menu = document.getElementById("mobile-menu");
    const toggleButton = document.getElementById("toggle-menu");
    const closeButton = document.getElementById("close-menu");

    toggleButton.addEventListener("click", () => {
        menu.classList.toggle("translate-y-full");
    });

    closeButton.addEventListener("click", () => {
        menu.classList.add("translate-y-full");
    });

</script>
</html>
