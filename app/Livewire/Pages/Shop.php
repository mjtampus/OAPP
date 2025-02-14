<?php

namespace App\Livewire\Pages;

use App\Models\Brand;
use Livewire\Component;
use App\Models\Category;
use App\Models\Products;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;
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
    public $cart = [];

    protected $queryString = ['searchQuery', 'selectedCategories', 'selectedBrands']; // Keep filters in URL

    public function mount()
    {
        $this->categories = Category::all();
        $this->brands = Brand::all();
        $this->cart = Session::get('cart', []);
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
            unset($cart[$productId]); 
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        Session::put('cart', $cart);
        $this->dispatch('cart-updated');
        $this->cart = $cart;
    }

    public function getProducts()
    {
        $query = Products::query()->with('category', 'brand');

        if (!empty($this->selectedCategories)) {
            $query->whereHas('category', function ($query) {
                $query->whereIn('id', $this->selectedCategories);
            });
        }
        
        if (!empty($this->selectedBrands)) {
            $query->whereIn('brand_id', $this->selectedBrands);
        }
        
        if (!empty($this->searchQuery)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('description', 'like', '%' . $this->searchQuery . '%');
            });
        }
        
        return $query->paginate(6);
        
    }

    public function likeProduct($productId)
    {
        $product = Products::find($productId);
        $user = Auth::user();

        if ($user->id === 1) {
            return redirect(route('login'));
        }
        else if ($user->id !== 1) {
            $user->like($product);
            $this->dispatch('product-liked', ['productId' => $productId]);
            $this->dispatch('notify', [
                'message' => 'Product liked successfully',
                'type' => 'success'
            ]);
        }else {
            return redirect(route('login'));
        }
    }

    public function render()
    {
        return view('livewire.pages.shop', [
            'products' => $this->getProducts()
        ])->layout('components.layouts.app');
    }
    public function resetFilters()
    {
        $this->reset('selectedCategories', 'selectedBrands', 'searchQuery');
    }
}
