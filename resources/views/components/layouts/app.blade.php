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
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>

</head>
<body class="min-h-screen flex flex-col">
    
    <!-- Global Loading Indicator -->

    <livewire:components.shop-header-navigation />
    
    <div class="toastify">
        <livewire:components.toast />
    </div>
    
    <div class="flex-grow relative">
        <div x-data="{ loading: false }"
        x-on:wire-navigate-start.window="loading = true"
        x-on:wire-navigate-end.window="loading = false">
   
       <!-- Loading Overlay -->
       <div x-show="loading" class="fixed inset-0 flex items-center justify-center bg-white bg-opacity-80 z-50">
           <div class="animate-spin h-12 w-12 border-4 border-blue-500 border-t-transparent rounded-full"></div>
       </div>
   
       <!-- Main Page Content -->
       {{ $slot }}
   </div>

   
    </div>

    <div class="alert-modal" >
        <livewire:components.alertModal />
    </div>

    <div class="order-sidebar">
        <livewire:components.order-sidebar />
    </div>

    <div class="order-item-sidebar">
        <livewire:components.order-item-sidebar />
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
    @vite('resources/js/app.js')
    @livewireScripts


</body>
</html>
