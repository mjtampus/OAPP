<?php

namespace App\Livewire\Pages;

use App\Models\Carts;
use App\Models\Order;
use Livewire\Component;
use App\Models\Products;
use App\Models\OrderItems;
use App\Models\ProductsSKU;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Checkout extends Component
{
    // Checkout form fields
    public $firstName;
    public $lastName;
    public $email;
    public $phone;
    
    // Shipping address fields
    public $address;
    public $city;
    public $state;
    public $postalCode;
    public $country;
    
    // Payment method
    public $paymentMethod = 'credit_card';
    public $eWalletType = '';
    public $cardNumber;
    public $cardExpiry;
    public $cardCvv;
    
    // Cart items
    public $cartItems = [];
    public $subtotal = 0;
    public $tax = 0;
    public $shipping = 0;
    public $total = 0;
    
    protected $rules = [
        'firstName' => 'required|min:2',
        'lastName' => 'required|min:2',
        'email' => 'required|email',
        'phone' => 'required',
        'address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'postalCode' => 'required',
        'country' => 'required',
        'paymentMethod' => 'required',
    ];
    
    public function mount()
    {
        // Fetch cart items from session or database
        $this ->getUser();
        $this->loadCartItems();
        $this->calculateTotals();
    }
    public function getUser()
    {
        if (!Auth::check()) {
            return redirect(route('login'));
        }else{

            $user = auth()->user();
            $this->email = $user->email;
            $this->firstName = $user->name;        }
    }

    public function incrementQuantity($cartId)
    {   
        $updateCart = Carts::find($cartId);
    
        if ($updateCart) {
            $updateCart->increment('quantity');
    
            // Update only the quantity in the Livewire cartItems array
            foreach ($this->cartItems as &$cartItem) {
                if ($cartItem['sku_id'] == $cartId) {
                    $cartItem['quantity'] = $updateCart->quantity; 
                    break;
                }
            }

            return $this->calculateTotals();
        }
    }
    public function decrementQuantity($cartId)
    {
        $updateCart = Carts::find($cartId);
    
        if ($updateCart && $updateCart->quantity > 1) {
            $updateCart->decrement('quantity');
    
            // Update only the quantity in the Livewire cartItems array
            foreach ($this->cartItems as &$cartItem) {
                if ($cartItem['sku_id'] == $cartId) {
                    $cartItem['quantity'] = $updateCart->quantity; 
                    break;
                }
            }
            return $this->calculateTotals();
        }
    }
    
    public function loadCartItems()
    {
        // Initialize cart items
        $this->cartItems = [];
    
        // Retrieve cart session data
        $cartCheckout = Session::get('cart-checkout', []);
    
        foreach ($cartCheckout as $cart) {
            // Find product and SKU from database
            $product = Products::find($cart['product_id']);
            $sku = ProductsSKU::find($cart['id']); // Assuming 'id' is the SKU ID
    
            if (!$product || !$sku) {
                continue; // Skip if not found
            }
    
            // Store cart details
            $this->cartItems[] = [
                'id' => $cart['id'],
                'cart_id' => $cart['cart_id'], // SKU ID
                'product_id' => $cart['product_id'],
                'sku_id' => $cart['id'],
                'price' => $sku->price,
                'image' => $sku->sku_image_dir,
                'name' => $product->name,
                'quantity' => $cart['quantity'] ?? 1, // Default to 1 if not set
            ];
        }    
    }
    
    
    public function calculateTotals()
    {
        $this->subtotal = collect($this->cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
        
        $this->tax = $this->subtotal * 0.08; // 8% tax
        $this->shipping = 15.00;
        $this->total = $this->subtotal + $this->tax + $this->shipping;
    }
    
    public function updateQuantity($itemId, $newQuantity)
    {
        if ($newQuantity > 0) {
            foreach ($this->cartItems as $index => $item) {
                if ($item['sku_id'] == $itemId) {
                    $this->cartItems[$index]['quantity'] = $newQuantity;
                    break;
                }
            }
            $this->calculateTotals();
        }
    }
    public function removeItem($itemId)
{
    // Get the current cart from session
    $cartCheckout = Session::get('cart-checkout', []);

    // Filter out the item with the given ID
    $updatedCart = array_filter($cartCheckout, function ($item) use ($itemId) {
        return $item['sku_id'] != $itemId; // Keep items that don't match the given ID
    });

    // Reindex the array (optional)
    $updatedCart = array_values($updatedCart);

    // Store the updated cart back in session
    Session::put('cart-checkout', $updatedCart);

    // Update local Livewire cart state
    $this->cartItems = $updatedCart;

    // Recalculate totals if needed
    $this->calculateTotals();
}

    
public function placeOrder()
{
    $this->validate();

    if (empty($this->cartItems)) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    $totalAmount = collect($this->cartItems)->sum(fn ($item) => $item['price'] * $item['quantity']);

    $order = Order::create([
        'user_id' => auth()->user()->id,
        'amount' => $totalAmount,
        'payment_method' => $this->eWalletType ?? $this->paymentMethod,
        'order_status' => 'pending'
    ]);

    foreach ($this->cartItems as $item) {
        OrderItems::create([
            'order_id' => $order->id,
            'cart_id' => $item['cart_id'],
            'product_id' => $item['product_id'],
            'sku_id' => $item['sku_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'total_price' => $this->total,
        ]);
    }

    // Determine Payment Gateway
    $gateway = match ($this->paymentMethod) {
        'credit_card' => 'stripe',
        'E_wallet' => 'paymongo',
        default => 'COD',
    };

    return redirect()->route('payment', ['id' => $order->id, 'gateway' => $gateway]);
}



    public function render()
    {
        return view('livewire.pages.checkout');
    }
}
