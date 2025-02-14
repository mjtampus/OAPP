<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Products;

class HomeFeaturedProducts extends Component
{
    public $products;
    public function render()
    {
        return view('livewire.components.home-featured-products');
    }

    public function mount()
    {
        $this->getProducts();
    }

    public function getProducts()
    {
        $this->products = Products::where('is_featured', 1)->get();
    }
}
