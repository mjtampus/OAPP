<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\ProductsAttributesValues;

class OrderSidebar extends Component
{
    public $orders = [];
    public $orderItems = [];
    public $showSidebar = false;
    public $showOrderItemsSidebar = false;
    public $selectedOrderId = null;


    protected $listeners = ['toggleOrdersSidebar' => 'toggleSidebar'];

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = auth()->check()
            ? auth()->user()->orders()->latest()->take(5)->get()
            : [];
    }

    public function toggleSidebar()
    {
        $this->showSidebar = !$this->showSidebar;
    }

    public function viewOrderDetails($orderId)
    {
        // You can emit an event to show order details or redirect
        $this->dispatch('showOrderDetails', $orderId);
        // Or redirect: return redirect()->route('orders.show', $orderId);
    }

    public function viewAllOrders()
    {
        return redirect()->route('orders.index');
    }

    public function render()
    {
        return view('livewire.components.order-sidebar');
    }
}
