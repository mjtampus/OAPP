<?php
namespace App\Livewire\Components;

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
    
        // Get the correct attribute IDs dynamically
        $colorAttributeId = ProductsAttributes::where('type', 'color')->value('id');
        $sizeAttributeId = ProductsAttributes::where('type', 'sizes')->value('id');
    
        // Initialize empty arrays for colors and sizes
        $this->colors = [];
        $this->sizes = [];


    
        foreach ($this->skus as $sku) {
            // Get the attributes of each SKU 
            $attributes = $sku->attributes ?? [];
    
            foreach ($attributes as $attribute) {
                $attrId = $attribute[0];  // Attribute ID (e.g., color, size)
                $attrValueId = $attribute[1]; // Attribute Value ID (e.g., red, large)
    
                // Fetch attribute value details
                if ($attrId == $colorAttributeId) {
                    // Color Attribute
                    $color = $this->getAttributeValue($attrValueId);
                    if ($color && !in_array($color, $this->colors)) {
                        $this->colors[] = $color;
                    }
                }

                if ($attrId == $sizeAttributeId) {
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
    public function selectColor($attributeId , $valueId)
    {
        $this->selectedColor = [$attributeId, $valueId];  // Store both attribute and value
        $this->updateSelectedSkuVariant();
    }
    
    public function selectSize($attributeId , $valueId)
    {
        $this->selectedSize = [$attributeId, $valueId];  // Store both attribute and value
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
    
        $cart = Session::get('cart', []);
    
        $index = collect($cart)->search(fn($item) => $item['id'] === $this->selectedSkuVariant->id);
    
        if ($index !== false) {

            $cart[$index]['quantity'] += $this->quantity;
        } else {

            $cart[] = [
                'id' => $this->selectedSkuVariant->id,
                'p_id' => $this->selectedSkuVariant->products_id,
                'quantity' => $this->quantity
            ];
        }
    
        Session::put('cart', $cart);
    
        $this->dispatch('cart-updated');
        $this->dispatch('auth-user-cart');
    
        $this->dispatch('notify', 
        ['message' => 'Cart added successfully',
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
