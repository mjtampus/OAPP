<?php

namespace App\Livewire\Pages;

use App\Models\Brand;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\Products;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Session;

#[Title('Shop')]
class Shop extends Component
{
    use WithPagination;

    public $selectedCategories = [];
    public $selectedBrands = [];
    public $searchQuery = '';

    public $categories;
    public $brands;

    protected $queryString = ['searchQuery', 'selectedCategories', 'selectedBrands']; // Keep filters in URL

    public function mount()
    {
        $this->categories = Category::all();
        $this->brands = Brand::all();
    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
    }

    public function updatedSelectedBrands()
    {
        $this->resetPage();
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function addToCart($productId)
    {
        $product = Products::find($productId);

        if (!$product) {
            return;
        }

        $cart = Session::get('cart', []);

        // Check if product already exists in the cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        Session::put('cart', $cart);
        $this->cart = $cart;
    }

    public function getProducts()
    {
        $query = Products::query();

        if (!empty($this->selectedCategories)) {
            $query->whereHas('category', function ($query) {
                $query->whereIn('id', $this->selectedCategories);
            });
        }

        if (!empty($this->selectedBrands)) {
            $query->whereIn('brand_id', $this->selectedBrands);
        }

        if (!empty($this->searchQuery)) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
        }

        return $query->with('category', 'brand')->paginate(9);
    }

    public function render()
    {
        return view('livewire.pages.shop', [
            'products' => $this->getProducts()
        ])->layout('components.layouts.app');
    }
}
