<?php
namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Products;
use App\Models\ProductsSKU;
use Livewire\Attributes\Title;
use App\Models\ProductsAttributes;
use App\Models\ProductsAttributesValue;
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
    public $selectedImage = null;
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
            // Get the attributes of each SKU (assuming it's stored as JSON)
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
        $this->updateSelectedImage();
    }
    
    public function selectSize($attributeId , $valueId)
    {
        $this->selectedSize = [$attributeId, $valueId];  // Store both attribute and value
        $this->updateSelectedImage();
    }
    

    public function addToCart()
    {
        if (!$this->selectedColor || !$this->selectedSize) {
            $this->dispatchBrowserEvent('notify', [
                'message' => 'Please select both color and size!'
            ]);
            return;
        }

        // Find SKU with matching attributes
        $selectedSku = $this->skus->first(function ($sku) {
            $attributes = json_decode($sku->attributes, true);
            return in_array([$this->selectedColor, 1], $attributes) &&
                   in_array([$this->selectedSize, 2], $attributes);
        });

        if (!$selectedSku) {
            $this->dispatchBrowserEvent('notify', [
                'message' => 'Selected combination is not available!'
            ]);
            return;
        }

        // Add to cart logic (e.g., session or database)
        $this->dispatchBrowserEvent('notify', [
            'message' => 'Product added to cart!'
        ]);
    }

    private function updateSelectedImage()
    {
        if (!$this->selectedColor || !$this->selectedSize) {
            $this->selectedImage = null;
            return;
        }
    
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

        
        $this->selectedImage = $selectedSku ? $selectedSku->sku_image_dir : null;

    }

    public function render()
    {
        return view('livewire.components.product-details', [
            'selectedImage' => $this->selectedImage,
        ]);
    }
}
