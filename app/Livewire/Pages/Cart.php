<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Products;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

#[Title('Shopping Cart')]
class Cart extends Component
{
    public $cartProducts = [];
    public $subtotal = 0;
    public $tax = 0;
    public $shipping = 9.99;
    public $total = 0;
    
    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $cart = Session::get('cart', []);


        if (!empty($cart)) {
            $cartIds = array_keys($cart);
            $products = Products::whereIn('id', $cartIds)->get();
            
            $this->cartProducts = $products->map(function ($product) use ($cart) {
                $product->quantity = $cart[$product->id]['quantity'];
                return $product;
            });
            $this->calculateTotals();
        } else {
            $this->cartProducts = [];
            $this->resetTotals();
        }
    }

    public function incrementQuantity($productId)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
            Session::put('cart', $cart);
            $this->refreshCart();
        }
    }

    public function decrementQuantity($productId)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            if ($cart[$productId]['quantity'] > 1) {
                $cart[$productId]['quantity']--;
            } else {
                unset($cart[$productId]);
            }
            
            Session::put('cart', $cart);
            $this->dispatch('cart-updated');
            $this->refreshCart();
        }
    }

    public function removeFromCart($productId)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
            $this->refreshCart();
            $this->dispatch('cart-updated');
            $this->dispatch('notify', [
                'message' => 'Item removed from cart',
                'type' => 'success'
            ]);
        }
    }

    public function clearCart()
    {
        Session::forget('cart');
        $this->refreshCart();
        $this->dispatch('cart-updated');
        $this->dispatch('notify', [
            'message' => 'Cart cleared successfully',
            'type' => 'success'
        ]);
    }

    private function calculateTotals()
    {
        $this->subtotal = $this->cartProducts->sum(function($product) {
            return $product->price * $product->quantity;
        });
        
        $this->tax = $this->subtotal * 0.1; // 10% tax
        $this->total = $this->subtotal + $this->tax + $this->shipping;
    }

    private function resetTotals()
    {
        $this->subtotal = 0;
        $this->tax = 0;
        $this->total = 0;
    }

    public function checkout()
    {
        if (Auth::check()) {
            $user = auth()->user();
    
            if ($user->id === 1) { 
                return redirect(route('login')); // Redirect user ID 1 to login
            }
    
            return redirect(route('checkout')); // Redirect all other logged-in users to checkout
        } 
    
        return redirect(route('login')); // Redirect guests to login
    }
    


    public function render()
    {
        try {
            return view('livewire.pages.cart', [
                'cartIsEmpty' => empty($this->cartProducts)
            ]);
        } catch (\Exception $e) {
            Log::error('Cart render error: ' . $e->getMessage());
            return view('livewire.pages.cart', [
                'cartIsEmpty' => true,
                'error' => 'Unable to load cart'
            ]);
        }
    }
}