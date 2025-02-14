<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class ShopHeaderNavigation extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cart-updated' => 'updateCartCount'];

    public function mount()
    {
        $cart = Session::get('cart',[]);
        $this->cartCount = count($cart);
    }
    public function updateCartCount()
    {
        $cart = Session::get('cart', []); // Default to an empty array if null
        $this->cartCount = is_array($cart) ? count($cart) : 0; // Ensure count() is used on an array
    }
    public function render()
    {
        return view('livewire.components.shop-header-navigation');
    }
}
