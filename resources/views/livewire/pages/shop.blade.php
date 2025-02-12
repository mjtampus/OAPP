<div>

<div class="min-h-screen bg-gray-50">
    <!-- Top Navigation Bar -->
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 space-y-4 md:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Discover</h1>
                <p class="mt-1 text-sm text-gray-500">Browse through our curated collection</p>
            </div>
                    <div class="relative mb-4">
                <input type="text" wire:model.live.debounce.500ms="searchQuery"
                    placeholder="Search products..."
                    class="w-full md:w-80 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Categories -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Categories</h2>
                    </div>
                    <div class="p-5 space-y-4">
                    @foreach($categories as $category)
                        <label class="flex items-center space-x-3 group cursor-pointer">
                            <input type="checkbox" wire:model.live="selectedCategories" value="{{ $category->id }}"
                                class="form-checkbox h-5 w-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                            <span class="text-gray-700">{{ $category->name }}</span>
                        </label>
                    @endforeach
                    </div>
                </div>
                
                    <div class="bg-white mt-10 rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Brand</h2>
                    </div>
                    <div class="p-5 space-y-4">
                    @foreach($brands as $brand)
                        <label class="flex items-center space-x-3 group cursor-pointer">
                            <input type="checkbox" wire:model.live="selectedBrands" value="{{ $brand->id }}" class="form-checkbox h-5 w-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                            <span class="text-gray-700 group-hover:text-gray-900">{{ $brand->name }}</span>
                        </label>
                        @endforeach
                        </div>
                
                </div>

                <!-- Price Range -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Price Range</h2>
                    </div>
                    <div class="p-5">
                        <div class="space-y-4">
                            <input type="range" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-purple-600">
                            <div class="flex justify-between text-sm text-gray-500">
                                <span>$0</span>
                                <span>$1000</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3 relative">
            <!-- Loading Overlay -->
            <div wire:loading wire:target="searchQuery, selectedCategories, selectedBrands" class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 backdrop-blur-md z-50">
                <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-purple-600"></div>
                <p class="mt-4 text-lg font-semibold text-purple-600">Loading products...</p>
            </div>

            <!-- Products Grid (Hidden While Loading) -->
            <div wire:loading.remove wire:target="searchQuery, selectedCategories, selectedBrands">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($products as $product)
                    <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden border border-gray-100">
                        <div class="relative">
                            <img src="https://picsum.photos/400/300?random={{ $product }}" alt="Product Image" class="w-full h-48 object-cover">
                            <div class="absolute top-3 right-3">
                                <button class="bg-white p-2 rounded-full shadow-md hover:scale-110 transition-transform duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="mb-2">
                                <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800">New Arrival</span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-200">{{$product->name}}</h3>
                            <p class="mt-2 text-sm text-gray-500">{{$product->description}}</p>
                            <div class="mt-4 flex items-center justify-between">
                                <div>
                                    <span class="text-lg font-bold text-gray-900">$199.99</span>
                                    <span class="ml-2 text-sm text-gray-500 line-through">$249.99</span>
                                </div>
                                <button wire:click="addToCart({{$product->id}})" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors duration-200 flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    <span>Add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                    <div class="mt-6">
                    {{ $products->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
