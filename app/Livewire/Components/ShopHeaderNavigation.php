<?php

namespace App\Livewire\Components;

use App\Models\Carts;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShopHeaderNavigation extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cart-updated' => 'updateCartCount'];

    public function mount()
    {
        $this->updateCartCount();
    }
    public function updateCartCount()
    {
        if (!Auth::check()) {
            $cart = Session::get('cart', []); // Default to an empty array if null
            $this->cartCount = is_array($cart) ? count($cart) : 0; // Ensure count() is used on an array 
        }else{
            $this->cartCount = Carts::where('user_id', Auth::id())->count();
        }

    }
    public function logout()
    {
        Auth::logout();

        return redirect(route('login'));
    }
    public function render()
    {
        return view('livewire.components.shop-header-navigation');
    }
}
