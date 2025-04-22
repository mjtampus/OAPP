<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="lg:grid lg:grid-cols-2 lg:gap-0">
                <!-- Image Section -->
                <div class="relative h-[600px] bg-gray-50">
                    <!-- Breadcrumb -->
                    <nav class="absolute top-4 left-4 z-10">
                        <ol class="flex items-center space-x-2 text-sm text-gray-500">
                            <li><a href="{{route('home')}}" class="hover:text-gray-700">Home</a></li>
                            <li><span class="text-gray-400">/</span></li>
                            <li><a href="{{route('shop')}}" class="hover:text-gray-700">Products</a></li>
                        </ol>
                    </nav>

                    <!-- Badge -->
                    @if($product['is_new_arrival'])
                        <div class="absolute top-4 right-4 z-10">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-indigo-50 text-indigo-700 ring-1 ring-indigo-600/10">
                                New Arrival
                            </span>
                        </div>
                    @endif

                    @if($product['is_featured'])
                        <div class="absolute top-4 right-32 z-10"> <!-- Adjusted top spacing -->
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-50 text-yellow-700 ring-1 ring-yellow-600/10">
                                Featured
                            </span>
                        </div>
                    @endif

                    <!-- Main Image -->
                    <div class="absolute inset-0 p-8">
                        <img 
                            src="{{ Storage::url($selectedSkuVariant->sku_image_dir ?? $product->product_image_dir) }}" 
                            alt="{{ $selectedSkuVariant ? 'Selected Variation' : ($product['name'] ?? 'Product Image') }}" 
                            class="w-full h-full object-contain transition-opacity duration-300"
                            wire:loading.class="opacity-50"
                        >
                    </div>
                </div>

                <!-- Product Info Section -->
                <div class="p-8 lg:p-12 bg-white">
                    <div class="space-y-8">
                        <div>
                            <h1 class="text-4xl font-bold text-gray-900 mb-4 leading-tight">
                                {{ $product['name'] ?? 'Product Name' }}
                            </h1>
                            <div class="flex items-center space-x-4">
                                <p class="text-3xl font-semibold text-indigo-600">
                                    ₱{{ number_format($selectedSkuVariant->price ?? $product['price'] ?? 0, 2) }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    In Stock
                                </span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="prose prose-sm text-gray-600 max-w-none">
                            <p class="leading-relaxed">{!! $product['description'] ?? 'No description available.' !!}</p>
                        </div>

                        <!-- Color Selection -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-900">Color</h3>
                                <span class="text-sm text-gray-500">
                                {{ $selectedColor[2] ?? 'Choose a color' }}

                                </span>
                            </div>
                            <div class="flex items-center space-x-3">
                            @foreach ($colors ?? [] as $color)
                            <button 
                                wire:click="selectColor({{ $color->products_attributes_id }}, {{ $color->id }} , '{{$color->value}}')"
                                type="button"
                                class="relative p-1 rounded-full flex items-center justify-center focus:outline-none">
                                <span class="h-10 w-10 rounded-full flex items-center justify-center
                                            {{ $selectedColor && $selectedColor[0] == $color->products_attributes_id && $selectedColor[1] == $color->id ? 'ring-2 ring-indigo-600 ring-offset-2' : '' }}">
                                    <span class="h-8 w-8 rounded-full" style="background-color: {{ $color->code }}"></span>
                            </button>
                            @endforeach 
                        </div>
                        

                        <!-- Size Selection -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-900">Size</h3>
                                <span class="text-sm text-gray-500">
                                    {{ $selectedSize[2] ?? 'Choose a size' }}
                                </span>
                            </div>
                            <div class="grid grid-cols-4 gap-2">
                                @foreach ($sizes ?? [] as $size)
                                    <button 
                                        wire:click="selectSize({{ $size->products_attributes_id }}, {{ $size->id }} , '{{$size->value}}')"
                                        type="button"
                                        class="h-12 flex items-center justify-center rounded-lg border-2 text-sm font-medium
                                            {{ $selectedSize && $selectedSize[0] == $size->products_attributes_id && $selectedSize[1] == $size->id
                                                ? 'border-indigo-600 bg-indigo-50 text-indigo-600' 
                                                : 'border-gray-200 hover:border-gray-300 text-gray-700' }}
                                            transition-all duration-200">
                                        {{ $size->value ?? 'Size' }} 
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Quantity Selection -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-900">Quantity</h3>
                                <span class="text-sm font-medium text-black-500">Stock : {{$selectedSkuVariant->stock ?? $product['stock'] ?? 0}}</span>
                            </div>
                            <div class="flex items-center space-x-6">
                                <button 
                                    wire:click="decrementQuantity"
                                    class="w-12 h-12 rounded-lg border-2 border-gray-200 flex items-center justify-center hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition-colors">
                                    <span class="text-gray-600 text-lg">−</span>
                                </button>
                                <span class="text-xl font-medium text-gray-900 w-12 text-center">{{ $quantity }}</span>
                                <button 
                                    wire:click="incrementQuantity"
                                    class="w-12 h-12 rounded-lg border-2 border-gray-200 flex items-center justify-center hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 transition-colors">
                                    <span class="text-gray-600 text-lg">+</span>
                                </button>
                            </div>
                        </div>

                        <!-- Add to Cart Button -->
                        <div class="pt-6">
                            <button 
                                wire:loading.attr="disabled"
                                wire:click="addToCart"
                                class="w-full bg-indigo-600 text-white px-8 py-4 rounded-xl font-medium
                                    hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-600 
                                    focus:ring-offset-2 transition-all duration-200
                                    disabled:opacity-50 disabled:cursor-not-allowed
                                    relative overflow-hidden"
                                @disabled(!$selectedColor || !$selectedSize)>
                                <span wire:loading.remove wire:target="addToCart" class="flex items-center justify-center">
                                    <span>Add to cart</span>
                                </span>
                                <span wire:loading wire:target="addToCart" class="flex items-center justify-center">
                                    <span>Adding to cart...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:max-w-7xl lg:px-8">
        <livewire:components.pages-components.comment-section :productId="$product->id" />
    </div>
</div>