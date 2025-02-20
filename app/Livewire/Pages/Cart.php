<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Products;
use App\Models\ProductsSKU;
use App\Models\Carts;
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

    protected $listeners = ['auth-user-cart' => 'refreshCart' , 
                            'confirmedRemove' , 'clearCart'];

    public function mount()
    {
        $this->refreshCart();
    }

    public function refreshCart()
    {
        if (Auth::check()) {
            $this->loadAuthUserCart();
        } else {
            $this->loadGuestCart();
        }
    }

    private function loadAuthUserCart()
    {
        $userId = Auth::id();
        $cartItems = Carts::where('user_id', $userId)->get();

        if ($cartItems->isNotEmpty()) {
            $cartIds = $cartItems->pluck('sku_id')->toArray();
            $productSkus = ProductsSKU::whereIn('id', $cartIds)->get();

            $this->cartProducts = $productSkus->map(function ($sku) use ($cartItems) {
                $cartItem = $cartItems->firstWhere('sku_id', $sku->id);
                $product = Products::find($sku->products_id);
                return [
                    'id' => $sku->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'sku' => $sku->sku,
                    'image' => $sku->sku_image_dir,
                    'price' => $sku->price,
                    'quantity' => $cartItem->quantity ?? 1
                ];
            });

            $this->calculateTotals();
        } else {
            $this->cartProducts = [];
            $this->resetTotals();
        }
    }

    private function loadGuestCart()
    {
        $cart = Session::get('cart', []);

        if (!empty($cart)) {
            $cartIds = collect($cart)->pluck('id')->toArray();
            $productSkus = ProductsSKU::whereIn('id', $cartIds)->get();

            $this->cartProducts = $productSkus->map(function ($sku) use ($cart) {
                $cartItem = collect($cart)->firstWhere('id', $sku->id);
                $product = Products::find($sku->products_id);
                return [
                    'id' => $sku->id,
                    'name' => $product->name,
                    'description' => $product->description,
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
        if (Auth::check()) {
            $cartItem = Carts::where('user_id', Auth::id())->where('sku_id', $productId)->first();
            if ($cartItem) {
                $cartItem->increment('quantity');
            }
        } else {
            $cart = Session::get('cart', []);
            $index = collect($cart)->search(fn($item) => $item['id'] === $productId);
            if ($index !== false) {
                $cart[$index]['quantity']++;
                Session::put('cart', $cart);
            }
        }
        $this->refreshCart();
    }

    public function decrementQuantity($productId)
    {
        if (Auth::check()) {
            $cartItem = Carts::where('user_id', Auth::id())->where('sku_id', $productId)->first();
            if ($cartItem) {
                if ($cartItem->quantity > 1) {
                    $cartItem->decrement('quantity');
                } else {
                    $cartItem->delete();
                }
            }
        } else {
            $cart = Session::get('cart', []);
            $index = collect($cart)->search(fn($item) => $item['id'] === $productId);
            if ($index !== false) {
                if ($cart[$index]['quantity'] > 1) {
                    $cart[$index]['quantity']--;
                } else {
                    unset($cart[$index]);
                    $cart = array_values($cart);
                }
                Session::put('cart', $cart);
            }
        }
        $this->dispatch('cart-updated');
        $this->refreshCart();
    }


    public function removeFromCart($productId)
    {
        if ($productId) {
            $this->dispatch('openModal', 'Are you sure you want to remove it from the cart?', 'confirmedRemove', $productId);
        }
    }
    
    public function confirmedRemove($productId)
    {
        if (Auth::check()) {
            Carts::where('user_id', Auth::id())->where('sku_id', $productId)->delete();
        } else {
            $cart = collect(Session::get('cart', []))->reject(fn($item) => $item['id'] === $productId)->values()->toArray();
            Session::put('cart', $cart);
        }
    
        $this->refreshCart();
        $this->dispatch('cart-updated');
        $this->dispatch('notify', [
            'message' => 'Item removed from cart',
            'type' => 'success'
        ]);
    }


    public function clearCart($confirmed = false)
    {
        if (!$confirmed) {
            $this->dispatch('openModal', 'Are you sure you want to clear the cart?', 'clearCart', true);
            return;
        }
    
        if (Auth::check()) {
            Carts::where('user_id', Auth::id())->delete();
        } else {
            Session::forget('cart');
        }
    
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
            if (Auth::id() === 1) { 
                return redirect(route('login')); // Redirect user ID 1 to login
            }
            return redirect(route('checkout')); // Redirect logged-in users to checkout
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
