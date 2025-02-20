<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Page Title' }}</title>
    
    @vite('resources/css/app.css')
    @livewireStyles
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
</head>
<body class="min-h-screen flex flex-col">
    
    <!-- Global Loading Indicator -->

    
    <livewire:components.shop-header-navigation />
    
    <div class="toastify">
        <livewire:components.toast />
    </div>
    
    <div class="flex-grow relative">
        <!-- Page Content -->
        {{ $slot }}
    </div>

    <div wire:loading class="fixed inset-0 bg-white bg-opacity-80 flex items-center justify-center z-50">
        <div class="animate-spin h-12 w-12 border-4 border-blue-500 border-t-transparent rounded-full"></div>
    </div>
    
    <div class="alert-modal" >
        <livewire:components.alertModal />
    </div>
    
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
    
    <script>
        let isLoginActive = true;

        function toggleForms() {
            const loginForm = document.getElementById("login-form");
            const registerForm = document.getElementById("register-form");
            const imageSection = document.getElementById("image-section");

            if (isLoginActive) {
                registerForm.style.right = "0";
                loginForm.style.transform = "translateX(-100%)";
                imageSection.style.transform = "translateX(-100%)";
            } else {
                registerForm.style.right = "-100%";
                loginForm.style.transform = "translateX(0)";
                imageSection.style.transform = "translateX(0)";
            }

            isLoginActive = !isLoginActive;
        }
    </script>
</body>
</html>
