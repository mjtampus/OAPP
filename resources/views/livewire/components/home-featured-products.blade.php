 <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach($products as $product)
        <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 fade-in">
            <div class="relative overflow-hidden group">
                <img src="{{ Storage::url ( $product->product_image_dir) }}" alt="Product" class="w-full h-64 object-cover transition-transform duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-2">Product {{ $product->name }}</h3>
                <div class="flex justify-between items-center">
                    <p class="text-blue-600 font-bold">${{$product->price}}</p>
                    <a href="/product/{{ $product->id }}" class="text-gray-600 hover:text-blue-600 transition-colors duration-300">
                        View Details →
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
