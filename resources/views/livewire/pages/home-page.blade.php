<div>
<header class="relative w-full h-[80vh] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/90 to-purple-600/90 mix-blend-multiply"></div>
        <img src="https://picsum.photos/1080/720?random" alt="Hero" class="absolute inset-0 w-full h-full object-cover">
        <div class="relative h-full flex items-center justify-center text-white text-center px-6">
            <div class="max-w-3xl fade-in">
                <h1 class="text-6xl font-bold mb-6 leading-tight">Discover Your Style</h1>
                <p class="text-xl mb-8 text-gray-100">Shop the latest trends with unbeatable deals</p>
                <a href="/shop" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    Shop Now
                </a>
            </div>
        </div>
    </header>

    <!-- Featured Products -->
    <section class="container mx-auto py-20 px-6">
        <h2 class="text-4xl font-bold mb-12 text-center fade-in">Featured Products</h2>
        <livewire:components.home-featured-products />
    </section>

    <!-- Newsletter Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 py-20 px-6">
        <div class="container mx-auto max-w-4xl text-center fade-in">
            <h2 class="text-4xl font-bold text-white mb-6">Subscribe for Exclusive Offers</h2>
            <p class="text-xl text-gray-100 mb-8">Stay updated with the latest deals and promotions.</p>
            <form class="flex flex-col sm:flex-row gap-4 justify-center">
                <input type="email" placeholder="Enter your email" class="flex-1 max-w-md px-6 py-4 rounded-full text-gray-800 outline-none focus:ring-2 focus:ring-blue-400 transition-shadow">
                <button class="px-8 py-4 bg-black text-white rounded-full hover:bg-gray-900 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    Subscribe Now
                </button>
            </form>
        </div>
    </section>

</div>

@script 
<script>
        // Initialize GSAP ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        // Fade In Animation
        document.querySelectorAll('.fade-in').forEach(element => {
            gsap.fromTo(element, 
                { opacity: 0, y: 20 },
                { 
                    opacity: 1, 
                    y: 0, 
                    duration: 1,
                    scrollTrigger: {
                        trigger: element,
                        start: "top bottom-=100",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        });

        // Mobile Menu Toggle


        // Navbar Scroll Effect
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('nav');
            const currentScroll = window.pageYOffset;

            if (currentScroll <= 0) {
                navbar.classList.remove('py-2');
                navbar.classList.add('py-4');
            }
            if (currentScroll > lastScroll && currentScroll > 80) {
                navbar.classList.add('py-2');
                navbar.classList.remove('py-4');
            }
            lastScroll = currentScroll;
        });
    </script>

@endscript
