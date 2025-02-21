<div class="bg-gray-50 flex items-center justify-center min-h-screen py-8">
    <div class="w-full max-w-4xl bg-white shadow-xl rounded-2xl overflow-hidden">
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
                    <div x-show="activeTab === 'login'" class="p-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                        <div class="mb-8 text-center">
                            <h2 class="text-2xl font-bold text-gray-800">Welcome Back!</h2>
                            <p class="text-gray-600 mt-1">Login to your account</p>
                        </div>
                        
                        <form wire:submit.prevent="login" class="space-y-5">
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
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-colors"
                                        placeholder="your@email.com"
                                        required
                                    >
                                </div>
                                <div class="mt-1 h-5">
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative" x-data="{ showPassword: false }">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input 
                                        id="login-password"
                                        :type="showPassword ? 'text' : 'password'" 
                                        wire:model.defer="password" 
                                        class="pl-10 pr-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-colors"
                                        placeholder="••••••••"
                                        required
                                    >
                                    <button 
                                        type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-1 h-5">
                                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
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
                            
                            <div class="text-center mt-4">
                                <p class="text-sm text-gray-600">
                                    Don't have an account? 
                                    <button type="button" @click="activeTab = 'register'" class="text-blue-600 hover:underline">Create one now</button>
                                </p>
                            </div>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div x-show="activeTab === 'register'" class="p-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
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
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-colors"
                                        placeholder="John Doe"
                                        required
                                    >
                                </div>
                                <div class="mt-1 h-5">
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
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
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-colors"
                                        placeholder="your@email.com"
                                        required
                                    >
                                </div>
                                <div class="mt-1 h-5">
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative" x-data="{ showPassword: false }">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input 
                                        id="register-password"
                                        :type="showPassword ? 'text' : 'password'" 
                                        wire:model.defer="password" 
                                        class="pl-10 pr-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-colors"
                                        placeholder="••••••••"
                                        required
                                    >
                                    <button 
                                        type="button" 
                                        @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                                    >
                                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-1 h-5">
                                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
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
                                        class="pl-10 w-full py-3 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-gray-50 transition-colors"
                                        placeholder="••••••••"
                                        required
                                    >
                                </div>
                                <div class="mt-1 h-5"></div>
                            </div>
                            
                            <div class="flex items-center">
                                <input id="terms" type="checkbox" wire:model.defer="terms" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="terms" class="ml-2 block text-sm text-gray-700">
                                    I agree to the <a href="#" class="text-blue-600 hover:underline">Terms</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                                </label>
                            </div>
                            <div class="h-5">
                                @error('terms') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            
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
                            
                            <div class="text-center mt-4">
                                <p class="text-sm text-gray-600">
                                    Already have an account? 
                                    <button type="button" @click="activeTab = 'login'" class="text-blue-600 hover:underline">Sign in</button>
                                </p>
                            </div>
                        </form>
                    </div>

                    <!-- Status Messages -->
                    <div class="px-8 pb-6">
                        <div x-data="{ show: false, message: '', type: '' }" 
                             x-show="show" 
                             x-init="
                                @this.on('success', (message) => {
                                    show = true;
                                    type = 'success';
                                    message = message;
                                    setTimeout(() => { show = false }, 5000);
                                });
                                @this.on('error', (message) => {
                                    show = true;
                                    type = 'error';
                                    message = message;
                                    setTimeout(() => { show = false }, 5000);
                                });
                             "
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="relative"
                        >
                            <div :class="{'bg-green-100 text-green-700': type === 'success', 'bg-red-100 text-red-700': type === 'error'}" 
                                 class="p-4 rounded-lg flex items-start">
                                <div class="flex-shrink-0 mr-3" :class="{'text-green-500': type === 'success', 'text-red-500': type === 'error'}">
                                    <svg x-show="type === 'success'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                    <svg x-show="type === 'error'" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="text-sm" x-text="message"></div>
                                <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-gray-400 p-1.5 hover:bg-gray-200 inline-flex h-8 w-8 items-center justify-center" :class="{'text-green-700': type === 'success', 'text-red-700': type === 'error'}">
                                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

 <!-- Image Section -->
                <div class="w-full md:w-1/2 bg-cover bg-center min-h-[300px] md:min-h-[500px] relative flex items-center justify-center"
                style="background-image: url('https://picsum.photos/1080/720')">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/80 to-indigo-900/70"></div>
                    <div class="relative z-10 flex flex-col items-center text-center px-12 text-white">
                        <h3 class="text-3xl font-bold mb-4">Start Your Journey</h3>
                        <p class="text-lg mb-6">Access your account dashboard and manage all your activities in one place.</p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="bg-white/20 p-3 rounded-full">
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span>Secure account management</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="bg-white/20 p-3 rounded-full">
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span>Real-time data analytics</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="bg-white/20 p-3 rounded-full">
                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span>Premium support 24/7</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>