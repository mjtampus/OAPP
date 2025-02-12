<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Products;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class Cart extends Component
{
    public $cartProducts = [];

    public function mount()
    {
        $cart = Session::get('cart', []);

        if (!empty($cart)) {
            $cartIds = array_keys($cart);
            $products = Products::whereIn('id', $cartIds)->get();
            

            $this->cartProducts = $products->map(function ($product) use ($cart) {
                $product->quantity = $cart[$product->id]['quantity'];
                return $product;
            });
        } else {
            $this->cartProducts = []; 
        }
    }

    public function render()
    {
        return view('livewire.pages.cart')
        ->title('Hatdog');
    }

}
