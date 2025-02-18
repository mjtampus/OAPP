<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Products;
use App\Models\ProductsSKU;
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
    protected $listeners = ['auth-user-cart' => 'refreshCart'] ;
    
    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        $cart = Session::get('cart', []);
        // dd($cart);

        if (!empty($cart)) {
            $cartIds = collect($cart)->pluck('id')->toArray();
            $productskus = ProductsSKU::whereIn('id', $cartIds)->get();;

            $this->cartProducts = $productskus->map(function ($sku) use ($cart) {
                $cartItem = collect($cart)->firstWhere('id', $sku->id);
                $products = Products::find($sku->products_id);
                return  [
                    'id' => $sku->id,
                    'name' => $products->name,
                    'description' => $products->description,
                    'sku' => $sku->sku,
                    'image' => $sku->sku_image_dir,
                    'price' => $sku->price,
                    'quantity' => $cartItem['quantity'] ?? 1
                ];
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

        $index = collect($cart)->search(fn($item) => $item['id'] === $productId);

        if ($index !== false) {
            $cart[$index]['quantity']++;
            Session::put('cart', $cart);
            $this->refreshCart();
        }
    }

    public function decrementQuantity($productId)
    {
        $cart = Session::get('cart', []);

        $index = collect($cart)->search(fn($item) => $item['id'] === $productId);

        if ($index !== false) {
            if ($cart[$index]['quantity'] > 1) {
                $cart[$index]['quantity']--;
            } else {
                unset($cart[$index]);
                $cart = array_values($cart); // Reindex the array
            }

            Session::put('cart', $cart);
            $this->dispatch('cart-updated');
            $this->refreshCart();
        }
    }

    public function removeFromCart($productId)
    {
        $cart = Session::get('cart', []);

        $cart = collect($cart)->reject(fn($item) => $item['id'] === $productId)->values()->toArray();

        Session::put('cart', $cart);
        $this->refreshCart();
        $this->dispatch('cart-updated');
        $this->dispatch('notify', [
            'message' => 'Item removed from cart',
            'type' => 'success'
        ]);
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
        $this->subtotal = collect($this->cartProducts)->sum(fn($product) => $product['price'] * $product['quantity']);
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
