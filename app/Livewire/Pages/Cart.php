<?php

namespace App\Livewire\Pages;

use App\Models\Carts;
use Livewire\Component;
use App\Models\Products;
use App\Models\ProductsSKU;
use Illuminate\Support\Arr;
use Livewire\Attributes\Title;
use App\Models\ProductsAttributes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\ProductsAttributesValues;

#[Title('Shopping Cart')]
class Cart extends Component
{
    public $selectAll = false;
    public $cartProducts = [];
    public $selectedProducts = [];
    public $selectedCartProducts = [];
    public $subtotal = 0;
    public $tax = 'computed after checkout';
    public $shipping = 'computed after checkout';
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
                $valuesIds = is_array($sku->attributes)
                ? array_map(fn($attr) => $attr[1] ?? null, $sku->attributes)
                : [];
                $values = ProductsAttributesValues::whereIn('id', $valuesIds)->pluck('value')->toArray();
                return [
                    'id' => $sku->id,
                    'cart_id' => $cartItem->id,
                    'name' => $product->name,
                    'product_id' => $product->id,
                    'description' => $product->description,
                    'sku' => $sku->sku,
                    'variant' => implode(' | ', $values),
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
            $cartItems = collect($cart); // Define cartItems from the session data
        
            $productSkus = ProductsSKU::whereIn('id', $cartIds)->get();
        
            $this->cartProducts = $productSkus->map(function ($sku) use ($cartItems) {
                $cartItem = $cartItems->firstWhere('id', $sku->id); // Use 'id' to match cart item
                $product = Products::find($sku->products_id);
        
                $valuesIds = is_array($sku->attributes)
                    ? array_map(fn($attr) => $attr[1] ?? null, $sku->attributes)
                    : [];
        
                $values = ProductsAttributesValues::whereIn('id', $valuesIds)->pluck('value')->toArray();
        
                return [
                    'id' => $sku->id,
                    'name' => $product->name,
                    'product_id' => $product->id,
                    'description' => $product->description,
                    'sku' => $sku->sku,
                    'variant' => implode(' | ', $values),
                    'image' => $sku->sku_image_dir,
                    'price' => $sku->price,
                    'quantity' => $cartItem['quantity'] ?? 1 // Fix: Use array syntax
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
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedProducts = collect($this->cartProducts)->pluck('id')->toArray();
        } else {
            $this->selectedProducts = [];
        }
    
        // Trigger update to maintain consistency
        $this->updatedSelectedProducts();
    }
    
    public function updatedSelectedProducts()
    {
       
        $this->selectAll = !empty($this->cartProducts) && count($this->selectedProducts) === count($this->cartProducts);
    
        $this->selectedCartProducts = !empty($this->selectedProducts)
            ? collect($this->cartProducts)->whereIn('id', $this->selectedProducts)->toArray()
            : [];
    
        $this->calculateTotals();
    }
    
    private function calculateTotals()
    {
        // Get only selected products 
        $selectedCartProducts = collect($this->cartProducts)
            ->whereIn('id', $this->selectedProducts);
    
        // Calculate subtotal only for selected products
        $this->subtotal = $selectedCartProducts->sum(fn($product) => $product['price'] * $product['quantity']);
        $this->total = $this->subtotal;
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
            Session::put('cart-checkout', $this->selectedCartProducts);

            return redirect(route('checkout')); // Redirect logged-in users to checkout
        } 
        return redirect(route('login')); 
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
