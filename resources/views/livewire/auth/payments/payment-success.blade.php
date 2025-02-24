<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 flex items-center justify-center"
     x-data="redirectTimer({{ $timeLeft }})"
     x-init="init()">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center transform transition-all duration-500 hover:scale-105"
         id="successCard">
        <!-- Success Icon with Animation -->
        <div class="mb-8 relative">
            <div class="w-32 h-32 bg-green-100 rounded-full flex items-center justify-center mx-auto opacity-0" id="successCircle">
                <svg class="w-20 h-20 text-green-500 transform scale-0" id="checkmark" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <!-- Success Message -->
        <h2 class="text-4xl font-bold text-gray-900 mb-4 opacity-0" id="title">ORDER CONFIRMED!</h2>
        <p class="text-gray-600 mb-6 opacity-0" id="message">
            Thank you for your payment. You will be redirected in 
            <span class="font-semibold text-gray-900" x-text="timeLeft"></span> seconds.
        </p>

        <!-- Progress Bar -->
        <div class="w-full bg-gray-100 rounded-full h-3 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-green-400 to-green-500 h-full rounded-full transition-all duration-1000 ease-out"
                 x-bind:style="`width: ${progress}%`">
            </div>
        </div>

        <!-- Button -->
        <a href="/" 
           class="inline-block px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            Return to Homepage
        </a>
    </div>

    <script>
        function redirectTimer(initialTime) {
            return {
                timeLeft: initialTime,
                progress: 100,
                init() {
                    gsap.to("#successCircle", { opacity: 1, duration: 0.5, ease: "power2.out" });
                    gsap.to("#checkmark", { scale: 1, duration: 0.5, delay: 0.3, ease: "back.out(1.7)" });
                    gsap.to("#title", { opacity: 1, y: 0, duration: 0.5, delay: 0.6 });
                    gsap.to("#message", { opacity: 1, y: 0, duration: 0.5, delay: 0.8 });

                    let interval = setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                            this.progress = (this.timeLeft / initialTime) * 100;
                        } else {
                            clearInterval(interval);
                            window.location.href = '/';
                        }
                    }, 1000);
                }
            };
        }

        document.addEventListener('livewire:load', function () {
            Livewire.on('startTimer', () => {
                redirectTimer(10).init(); // Updated to 10 seconds
            });
        });
    </script>
</div>
