<?php

namespace App\Livewire\Components;

use App\Models\Carts;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShopHeaderNavigation extends Component
{
    public $cartCount = 0;

    protected $listeners = ['cart-updated' => 'updateCartCount',
                            'logout'];

    public function mount()
    {
        $this->updateCartCount();
    }
    public function updateCartCount()
    {
        if (!Auth::check()) {
            $cart = Session::get('cart', []);
            $this->cartCount = is_array($cart) ? count($cart) : 0; 
        }else{
            $this->cartCount = Carts::where('user_id', Auth::id())->count();
        }

    }
    public function logout($comfirmed = false)
    {
        if (!$comfirmed) {
            $this->dispatch('openModal', 'Are you sure you want to logout?', 'logout', true);
            return;
        }
        Auth::logout();

        return redirect(route('login'));
    }
    public function render()
    {
        return view('livewire.components.shop-header-navigation');
    }
}
