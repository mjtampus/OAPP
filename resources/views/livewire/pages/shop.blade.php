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

                <!-- Brands -->
                <div class="bg-white mt-10 rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-900">Brand</h2>
                    </div>
                    <div class="p-5 space-y-4">
                        @foreach($brands as $brand)
                        <label class="flex items-center space-x-3 group cursor-pointer">
                            <input type="checkbox" wire:model.live="selectedBrands" value="{{ $brand->id }}" 
                                class="form-checkbox h-5 w-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
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
                <!-- Global Loading State -->
                <div wire:loading.flex wire:target="searchQuery, selectedCategories, selectedBrands" 
                    class="absolute inset-0 items-center justify-center bg-white/80 backdrop-blur-sm z-50">
                    <div class="text-center">
                        <div class="inline-block animate-spin h-8 w-8 border-4 border-purple-600 border-t-transparent rounded-full"></div>
                        <p class="mt-4 text-sm font-medium text-purple-600">Loading products...</p>
                    </div>
                </div>

                <!-- Products Grid -->
                <div wire:loading.remove wire:target="searchQuery, selectedCategories, selectedBrands">
                    @if($products->isEmpty())
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center h-96 bg-white rounded-xl border border-gray-100 p-8">
                        <div class="w-24 h-24 bg-purple-100 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Products Found</h3>
                        <p class="text-gray-500 text-center max-w-md">
                            We couldn't find any products matching your current filters. Try adjusting your search or clearing some filters.
                        </p>
                        <button wire:click="resetFilters" class="mt-6 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
                            Clear All Filters
                        </button>
                    </div>
                    @else
                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                        <div class="group bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden border border-gray-100 flex flex-col">
                            <div class="relative">
                                <img src="{{ Storage::url($product->product_image_dir) }}" alt="Product Image" class="w-full h-48 object-cover">
                                <div class="absolute top-3 right-3">
                                    <button wire:click="likeProduct({{ $product->id }})" class="bg-white p-2 rounded-full shadow-md hover:scale-110 transition-transform duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="p-5 flex flex-col flex-grow">
                                <div class="mb-2">
                                    @if($product->is_featured)
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-green-100 text-green-800">
                                        Featured
                                    </span>
                                    @endif

                                    @if($product->is_new_arrival)
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full bg-purple-100 text-purple-800">
                                        New Arrival
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-200">
                                    {{$product->name}}
                                </h3>

                                <!-- 🔥 Fixed Height for Description -->
                                <div class="mt-2 text-sm text-gray-500 h-16 overflow-hidden">
                                    {!! $product->description !!}
                                </div>
                                <!-- 🔥 Push Price & Button to the Bottom -->
                                <div class="mt-auto flex items-center justify-between w-full">
                                    <span class="text-lg font-bold text-gray-900">${{ $product->price }}</span>
                                    @if(isset($cart[$product->id]))
                                    <button 
                                        wire:click="addToCart({{ $product->id }})"
                                        class="px-4 py-2 rounded-lg flex items-center space-x-1 transition-colors duration-200
                                            bg-red-600 text-white hover:bg-red-700 
                                           ">
                                        <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                            Remove to cart
                                        </span>
                                    
                                        <span wire:loading wire:target="addToCart({{ $product->id }})">
                                            Removing...
                                        </span>
                                    </button>
                             
                                @else
                                    <button 
                                        wire:click="addToCart({{ $product->id }})"
                                        class="px-4 py-2 rounded-lg flex items-center space-x-1 transition-colors duration-200
                                            @if(isset($cart[$product->id])) bg-green-600 text-white hover:bg-green-700 
                                            @else bg-purple-600 text-white hover:bg-purple-700 @endif">
                                        
                                        <svg wire:loading.remove wire:target="addToCart({{ $product->id }})" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>

                                        <span wire:loading.remove wire:target="addToCart({{ $product->id }})">
                                            Add To Cart
                                        </span>
                                    
                                        <span wire:loading wire:target="addToCart({{ $product->id }})">
                                            Adding to cart...
                                        </span>
                                    </button>
                                
                                @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @endif

                    <!-- Pagination -->
                    <div class="mt-8" wire:key="pagination">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>