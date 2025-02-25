<?php
namespace App\Livewire\Components;

use App\Models\Carts;
use Livewire\Component;
use App\Models\Products;
use App\Models\ProductsSKU;
use Livewire\Attributes\Title;
use App\Models\ProductsAttributes;
use App\Models\ProductsAttributesValue;
use Illuminate\Support\Facades\Session;
use App\Models\ProductsAttributesValues;

#[Title('Products')]
class ProductDetails extends Component
{
    public $productId;
    public $product;
    public $skus = [];
    public $colors = [];
    public $sizes = [];


    public $selectedColor = null;
    public $selectedSize = null;
    public $selectedSkuVariant = null;
    public $quantity = 1;

    public function mount($productId)
    {
        $this->product = Products::with('sku')->find($productId);
        $this->loadVariations();
    }

    private function loadVariations()
    {
        if (!$this->product) return;
    
        // Get all SKUs associated with this product
        $this->skus = $this->product->sku;
    
        // Extract unique attribute IDs from SKU attributes
        $attributeIds = collect($this->skus)
            ->pluck('attributes') // Get attributes column
            ->flatten(1) // Flatten only one level to maintain key-value pairs
            ->pluck(0) // Extract only the attribute IDs (first index in each pair)
            ->unique() // Remove duplicates
            ->values(); // Reset keys
    
        // Fetch attribute details from the database
        $attributes = ProductsAttributes::whereIn('id', $attributeIds)->get();
    
        // Initialize empty arrays for attribute types
        $this->colors = [];
        $this->sizes = [];
    
        // Process each SKU
        foreach ($this->skus as $sku) {
            // Get the attributes of each SKU 
            $skuAttributes = $sku->attributes ?? [];
    
            foreach ($skuAttributes as $attribute) {
                $attrId = $attribute[0];  // Attribute ID (e.g., color, size)
                $attrValueId = $attribute[1]; // Attribute Value ID (e.g., red, large)
    
                // Find the attribute type dynamically
                $attributeType = $attributes->firstWhere('id', $attrId)?->type;
    
                if ($attributeType === 'color') {
                    // Color Attribute
                    $color = $this->getAttributeValue($attrValueId);
                    if ($color && !in_array($color, $this->colors)) {
                        $this->colors[] = $color;
                    }
                }
    
                if ($attributeType === 'sizes') {
                    // Size Attribute
                    $size = $this->getAttributeValue($attrValueId);
                    if ($size && !in_array($size, $this->sizes)) {
                        $this->sizes[] = $size;
                    }
                }
            }
        }
    }
    
    
    private function getAttributeValue($valueId, $type = null)
    {
        return ProductsAttributesValues::find($valueId);
    }
    public function selectColor($attributeId , $valueId , $Value )
    {
        $this->selectedColor = [$attributeId, $valueId , $Value ]; 
        $this->updateSelectedSkuVariant();

    }
    
    public function selectSize($attributeId , $valueId, $Value )
    {
        $this->selectedSize = [$attributeId, $valueId , $Value ];  // Store both attribute and value
        $this->updateSelectedSkuVariant();
    }
    public function incrementQuantity()
    {
        $this->quantity++;
    }
    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }
    
    public function addToCart()
    {
        if (!$this->selectedSkuVariant) {
            return response()->json([
                'success' => false,
                'message' => 'Please select a color and size.'
            ]);
        }
    
        $quantity = $this->quantity;
        $skuId = $this->selectedSkuVariant->id;
        $productId = $this->selectedSkuVariant->products_id;
    
        if (auth()->check()) {
            // User is logged in, store in database
            $userId = auth()->id();
            
            $cartItem = Carts::where('user_id', $userId)
                ->where('sku_id', $skuId)
                ->first();
    
            if ($cartItem) {
                $cartItem->increment('quantity', $quantity);
            } else {
                Carts::create([
                    'user_id' => $userId,
                    'sku_id' => $skuId,
                    'products_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }

            $this->dispatch('auth-user-cart');

        } else {
            // User is not logged in, store in session
            $cart = Session::get('cart', []);
    
            $index = collect($cart)->search(fn($item) => $item['id'] === $skuId);
    
            if ($index !== false) {
                $cart[$index]['quantity'] += $quantity;
            } else {
                $cart[] = [
                    'id' => $skuId,
                    'p_id' => $productId,
                    'quantity' => $quantity
                ];
            }
    
            Session::put('cart', $cart);
        }
    
        $this->dispatch('cart-updated');
    
        $this->dispatch('notify', [
            'message' => 'Cart added successfully',
            'type' => 'success'
        ]);
    }
    
    
    
    private function updateSelectedSkuVariant()
    {
        if (!$this->selectedColor || !$this->selectedSize) {
            $this->selectedSkuVariant = null; // Clear the selected variant if no color or size is selected
            return;
        }
    
        // Find the SKU variant matching the selected color and size
        $selectedSku = $this->skus->first(function ($sku) {
            $attributes = $sku->attributes;
            
            // Check for matching color and size attributes and values
            $hasColor = collect($attributes)->contains(function ($attribute) {
                return $attribute[0] == $this->selectedColor[0] && $attribute[1] == $this->selectedColor[1];
            });
    
            $hasSize = collect($attributes)->contains(function ($attribute) {
                return $attribute[0] == $this->selectedSize[0] && $attribute[1] == $this->selectedSize[1];
            });
    
            return $hasColor && $hasSize;
        });
    
        $this->selectedSkuVariant = $selectedSku ? $selectedSku : null;
    }
    
    public function render()
    {
        return view('livewire.components.product-details');
    }
}
