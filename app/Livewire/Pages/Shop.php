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
    public $availableBrands = []; // Stores brands related to selected categories
    public $cart = [];

    protected $queryString = ['searchQuery', 'selectedCategories', 'selectedBrands']; // Keep filters in URL

    public function mount()
    {
        $this->categories = Category::with('brands')->get();
        $this->updateAvailableBrands();
        $this->cart = Session::get('cart', []);
    }

    public function updatedSelectedCategories()
    {
        $this->resetPage();
        $this->updateAvailableBrands();
    }

    public function updatedSelectedBrands()
    {
        $this->resetPage();
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function updateAvailableBrands()
    {
        if (!empty($this->selectedCategories)) {
            $this->availableBrands = Brand::whereHas('category', function ($query) {
                $query->whereIn('id', $this->selectedCategories);
            })->get();
        } else {
            $this->availableBrands = collect(); // Return an empty collection
        }
    
        // Remove selected brands that are no longer available
        $this->selectedBrands = array_intersect($this->selectedBrands, $this->availableBrands->pluck('id')->toArray());
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

        if (Auth::id() === 1 || !Auth::check()) {
            return redirect(route('login'));
        }
        else if (Auth::id() !== 1) {
            $user->like($product);
            $this->dispatch('product-liked', ['productId' => $productId]);
            $this->dispatch('notify', [
                'message' => 'Product liked successfully',
                'type' => 'success'
            ]);
        } else {
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
        $this->updateAvailableBrands();
    }
}
