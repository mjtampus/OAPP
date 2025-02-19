<?php

namespace App\Livewire\Pages;

use Livewire\Component;

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
        $this->loadCartItems();
        $this->calculateTotals();
    }
    
    public function loadCartItems()
    {
        // Sample data - in a real app, fetch from database
        $this->cartItems = [
            [
                'id' => 1,
                'name' => 'Premium Headphones',
                'price' => 129.99,
                'quantity' => 1,
                'image' => '/images/headphones.jpg'
            ],
            [
                'id' => 2,
                'name' => 'Wireless Charger',
                'price' => 45.50,
                'quantity' => 2,
                'image' => '/images/charger.jpg'
            ],
        ];
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
                if ($item['id'] == $itemId) {
                    $this->cartItems[$index]['quantity'] = $newQuantity;
                    break;
                }
            }
            $this->calculateTotals();
        }
    }
    
    public function removeItem($itemId)
    {
        $this->cartItems = array_filter($this->cartItems, function ($item) use ($itemId) {
            return $item['id'] != $itemId;
        });
        $this->calculateTotals();
    }
    
    public function placeOrder()
    {
        $this->validate();
        
        // Process the order (simplified)
        $orderId = rand(10000, 99999);
        
        // In a real app, you would save to database
        session()->flash('order_completed', true);
        session()->flash('order_id', $orderId);
        
        return redirect()->route('checkout.confirmation');
    }
    public function render()
    {
        return view('livewire.pages.checkout');
    }
}
