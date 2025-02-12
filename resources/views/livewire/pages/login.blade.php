<div class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="flex h-screen items-center justify-center">
        <!-- Main Container -->
        <div class="relative w-[900px] h-[700px] bg-white shadow-lg rounded-lg overflow-hidden flex">
            
            <!-- Left Section (Login) -->
            <div class="w-1/2 p-8 flex flex-col justify-center items-center transition-all duration-500" id="login-form">
                <h2 class="text-2xl font-semibold text-gray-700">Welcome Back!</h2>
                <p class="text-gray-600 mb-14">Login to continue</p>
                <form action="" method="POST" class="w-full max-w-xs">
                    @csrf
                    <input type="email" name="email" placeholder="Email" required class="w-full p-3 mb-4 border border-gray-300 rounded">
                    <input type="password" name="password" placeholder="Password" required class="w-full p-3 mb-4 border border-gray-300 rounded">
                    <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded hover:bg-blue-600">Login</button>
                </form>
                <p class="text-blue-500 cursor-pointer mt-4" onclick="toggleForms()">Don't have an account? Register</p>
            </div>

            <!-- Right Section (Register) -->
            <div class="w-1/2 p-8 mt-[150px] flex flex-col justify-center items-center transition-all duration-500 absolute right-[-100%]" id="register-form">
                <h2 class="text-2xl font-semibold text-gray-700">Come Aboard!</h2>
                <p class="text-gray-600 mb-4">Register to get started</p>
                <form action="" method="POST" class="w-full max-w-xs">
                    @csrf
                    <input type="text" name="name" placeholder="Full Name" required class="w-full p-3 mb-4 border border-gray-300 rounded">
                    <input type="email" name="email" placeholder="Email" required class="w-full p-3 mb-4 border border-gray-300 rounded">
                    <input type="password" name="password" placeholder="Password" required class="w-full p-3 mb-4 border border-gray-300 rounded">
                    <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded hover:bg-blue-600">Register</button>
                </form>
                <p class="text-blue-500 cursor-pointer mt-4" onclick="toggleForms()">Already have an account? Login</p>
            </div>

            <!-- Image Section (Now positioned correctly on the right) -->
            <div class="absolute top-0 right-0 w-1/2 h-full transition-all duration-500" id="image-section">
                <img src="https://picsum.photos/450/500" alt="Random Image" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</div>
