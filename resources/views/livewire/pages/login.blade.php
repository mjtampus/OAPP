<div class="bg-gray-100 flex items-center justify-center min-h-screen py-8">
    <div class="w-[900px] bg-white shadow-2xl rounded-xl overflow-hidden">
        <div class="flex flex-col md:flex-row relative">
            <!-- Login/Register Container -->
            <div class="w-full md:w-1/2 transition-all duration-500 ease-in-out">
                <!-- Livewire Component Container -->
                <div x-data="{ activeTab: 'login' }">
                    <!-- Navigation Tabs -->
                    <div class="flex border-b">
                        <button 
                            @click="activeTab = 'login'" 
                            :class="{'text-blue-600 border-b-2 border-blue-600 font-medium': activeTab === 'login'}"
                            class="flex-1 py-4 px-6 text-gray-700 hover:text-blue-600 transition-all">
                            Login
                        </button>
                        <button 
                            @click="activeTab = 'register'" 
                            :class="{'text-blue-600 border-b-2 border-blue-600 font-medium': activeTab === 'register'}"
                            class="flex-1 py-4 px-6 text-gray-700 hover:text-blue-600 transition-all">
                            Register
                        </button>
                    </div>

                    <!-- Login Form -->
                    <div x-show="activeTab === 'login'" class="p-8" x-transition>
                        <div class="mb-8 text-center">
                            <h2 class="text-2xl font-bold text-gray-800">Welcome Back!</h2>
                            <p class="text-gray-600 mt-1">Login to your account</p>
                        </div>
                        
                        <form wire:submit.prevent="login" class="space-y-4">
                            <div>
                                <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                    <input 
                                        id="login-email"
                                        type="email" 
                                        wire:model.defer="email" 
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="your@email.com"
                                        required
                                    >
                                </div>
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input 
                                        id="login-password"
                                        type="password" 
                                        wire:model.defer="password" 
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="••••••••"
                                        required
                                    >
                                </div>
                                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember-me" type="checkbox" wire:model.defer="remember" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                                </div>
                                <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-500">Forgot password?</a>
                            </div>
                            
                            <button 
                                type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                wire:loading.class="opacity-75 cursor-wait"
                                wire:target="login"
                            >
                                <span wire:loading.remove wire:target="login">Sign In</span>
                                <div wire:loading wire:target="login" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </div>
                            </button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div x-show="activeTab === 'register'" class="p-8" x-transition>
                        <div class="mb-6 text-center">
                            <h2 class="text-2xl font-bold text-gray-800">Create Account</h2>
                            <p class="text-gray-600 mt-1">Join our community today</p>
                        </div>
                        
                        <form wire:submit.prevent="register" class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input 
                                        id="name"
                                        type="text" 
                                        wire:model.defer="name" 
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="John Doe"
                                        required
                                    >
                                </div>
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                    <input 
                                        id="register-email"
                                        type="email" 
                                        wire:model.defer="email" 
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="your@email.com"
                                        required
                                    >
                                </div>
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input 
                                        id="register-password"
                                        type="password" 
                                        wire:model.defer="password" 
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="••••••••"
                                        required
                                    >
                                </div>
                                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input 
                                        id="password_confirmation"
                                        type="password" 
                                        wire:model.defer="password_confirmation" 
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        placeholder="••••••••"
                                        required
                                    >
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="terms" type="checkbox" wire:model.defer="terms" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="terms" class="ml-2 block text-sm text-gray-700">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">Terms</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                                </label>
                            </div>
                            @error('terms') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            
                            <button 
                                type="submit" 
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                wire:loading.class="opacity-75 cursor-wait"
                                wire:target="register"
                            >
                                <span wire:loading.remove wire:target="register">Create Account</span>
                                <div wire:loading wire:target="register" class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </div>
                            </button>
                        </form>
                    </div>

                    <!-- Status Messages -->
                    <div class="px-8 pb-6">
                        @if (session()->has('success'))
                            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if (session()->has('error'))
                            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Image Section -->
            <div class="hidden md:block md:w-1/2 bg-cover bg-center h-full" style="background-image: url('https://picsum.photos/450/700')">
                <div class="h-full w-full bg-gradient-to-r from-blue-900/70 to-indigo-900/60 flex flex-col justify-center px-12 text-white">
                    <h3 class="text-3xl font-bold mb-4">Start Your Journey</h3>
                    <p class="text-lg mb-8">Access your account dashboard and manage all your activities in one place.</p>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="bg-white/20 p-2 rounded-full">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="ml-4">Secure account management</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-white/20 p-2 rounded-full">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="ml-4">Real-time data analytics</span>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-white/20 p-2 rounded-full">
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="ml-4">Premium support 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alpine.js for tab switching (required) -->
<script>
    document.addEventListener('alpine:init', () => {
        // Alpine is already initialized
    });
</script>

<!-- Livewire Component Script -->
<script>
    // This would be in your Livewire component
    class AuthComponent extends Livewire.Component {
        name = '';
        email = '';
        password = '';
        password_confirmation = '';
        remember = false;
        terms = false;
        
        login() {
            this.validate({
                email: 'required|email',
                password: 'required|min:8',
            });
            
            // Simulate login process with delay
            this.$wire.loading = true;
            setTimeout(() => {
                // Your actual login logic would go here
                if (this.email === 'test@example.com' && this.password === 'password') {
                    this.$wire.emit('success', 'Successfully logged in!');
                    // Redirect after login
                    // window.location.href = '/dashboard';
                } else {
                    this.$wire.emit('error', 'Invalid credentials. Please try again.');
                }
                this.$wire.loading = false;
            }, 1500);
        }
        
        register() {
            this.validate({
                name: 'required|min:3',
                email: 'required|email',
                password: 'required|min:8|same:password_confirmation',
                terms: 'accepted',
            });
            
            // Simulate registration process with delay
            this.$wire.loading = true;
            setTimeout(() => {
                // Your actual registration logic would go here
                this.$wire.emit('success', 'Account created successfully! Please check your email to verify your account.');
                this.$wire.loading = false;
            }, 1500);
        }
    }
    
    // Registration would be in your app.js or similar file
    Livewire.component('auth-component', AuthComponent);
</script>