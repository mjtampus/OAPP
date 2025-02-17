<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="lg:grid lg:grid-cols-2 lg:gap-0">
                <!-- Image Section -->
                <div class="relative h-[600px] bg-gray-100">
                    @if($selectedImage)
                        <img src="{{ Storage::url($selectedImage) }}" alt="Selected Variation" 
                            class="absolute inset-0 w-full h-full object-contain">
                    @else
                        <img src="{{ Storage::url($product->product_image_dir) }}" 
                            alt="{{ $product['name'] ?? 'Product Image' }}" 
                            class="absolute inset-0 w-full h-full object-contain">
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white text-gray-800 shadow-md">
                            New Arrival
                        </span>
                    </div>
                </div>


                <!-- Product Info Section -->
                <div class="p-8 lg:p-12">
                    <div class="space-y-8">
                        <!-- Header -->
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                                {{ $product['name'] ?? 'Product Name' }}
                            </h1>
                            <p class="text-2xl font-semibold text-indigo-600">
                                ${{ number_format($product['price'] ?? 0, 2) }}
                            </p>
                        </div>

                        <!-- Description -->
                        <div class="prose prose-sm text-gray-600">
                            <p>{!! $product['description'] ?? 'No description available.' !!}</p>
                        </div>

                        <!-- Color Selection -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Select Color</h3>
                            <div class="flex items-center space-x-4">
                            @foreach ($colors ?? [] as $color)
                                <button wire:click="selectColor({{ $color->products_attributes_id }}, {{ $color->id }})"
                                        type="button"
                                        class="group relative">
                                    <div class="relative">
                                        <span class="h-10 w-10 rounded-full flex items-center justify-center
                                                    {{ $selectedColor && $selectedColor[0] == $color->products_attributes_id && $selectedColor[1] == $color->id ? 'ring-2 ring-indigo-600 ring-offset-2' : '' }}">
                                            <span class="h-8 w-8 rounded-full" 
                                                style="background-color: {{ $color->code }}"></span>
                                    </div>
                                </button>
                            @endforeach



                            </div>
                        </div>

                        <!-- Size Selection -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Select Size</h3>
                            <div class="grid grid-cols-4 gap-3">
                            @foreach ($sizes ?? [] as $size)
                                <button wire:click="selectSize({{ $size->products_attributes_id }}, {{ $size->id }})"
                                        type="button"
                                        class="h-12 flex items-center justify-center rounded-lg border-2
                                                {{ $selectedSize && $selectedSize[0] == $size->products_attributes_id && $selectedSize[1] == $size->id
                                                    ? 'border-indigo-600 bg-indigo-50 text-indigo-600' 
                                                    : 'border-gray-200 hover:border-gray-300 text-gray-700' }}
                                                transition-all duration-200">
                                    <span class="text-sm font-medium">{{ $size->value ?? 'Size' }}</span>
                                </button>
                            @endforeach

                            </div>
                        </div>

                        <!-- Quantity Selection -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-900 mb-4">Quantity</h3>
                            <div class="flex items-center space-x-4">
                                <button wire:click="decrementQuantity" 
                                    class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                                    <span class="text-gray-600">−</span>
                                </button>
                                <span class="text-lg font-medium text-gray-900">{{ $quantity }}</span>
                                <button wire:click="incrementQuantity"
                                    class="w-10 h-10 rounded-full border-2 border-gray-300 flex items-center justify-center hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2">
                                    <span class="text-gray-600">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        <div class="pt-4">
                            <button wire:click="addToCart" 
                                class="w-full bg-indigo-600 text-white px-8 py-4 rounded-xl font-medium
                                    hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 
                                    focus:ring-offset-2 transition-colors duration-200
                                    disabled:opacity-50 disabled:cursor-not-allowed"
                                @disabled(!$selectedColor || !$selectedSize)>
                                Add to Cart
                            </button>
                        </div>

                        <!-- Notification Message -->
                        <div>
                            @if(session()->has('message'))
                                <div class="p-4 bg-green-100 text-green-700 rounded-lg">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
