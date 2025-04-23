<?php

namespace App\Livewire\Components;

use App\Models\Order;
use Livewire\Component;
use App\Models\ProductsAttributesValues;

class OrderItemSidebar extends Component
{
    public $orderId = null;
    public $orderItems = [];
    public $isVisible = false;
    public $searchQuery = '';
    public $filterStatus = '';
    
    protected $listeners = [
        'showOrderDetails' => 'showOrderItems',
        'orderUpdated' => '$refresh'
    ];

    public function showOrderItems($orderId)
    {
        $this->orderId = $orderId;
        $this->loadOrderItems();
        $this->isVisible = true;
    }

    public function loadOrderItems()
    {
        if (!$this->orderId) {
            return;
        }
    
        $query = Order::where('id', $this->orderId)->with('items.sku', 'items.product');
    
        if (!empty($this->searchQuery)) {
            $query->whereHas('items', function ($q) {
                $q->whereHas('product', function ($q2) {
                    $q2->where('name', 'like', '%' . $this->searchQuery . '%');
                })->orWhereHas('sku', function ($q2) {
                    $q2->where('sku', 'like', '%' . $this->searchQuery . '%');
                });
            });
        }
    
        $orders = $query->get();
    
        // Append sku_label to each item
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $sku = $item->sku;
    
                if (!$sku || !is_array($sku->attributes)) {
                    $item->sku_label = $sku->sku ?? 'N/A';
                    continue;
                }
    
                $ids = array_column($sku->attributes, 1);
                $values = ProductsAttributesValues::whereIn('id', $ids)->pluck('value')->toArray();
    
                $item->sku_label = implode(', ', $values);
            }
        }
        $this->orderItems = $orders;
    }

    public function updatedSearchQuery()
    {
        $this->loadOrderItems();
    }
    
    public function updatedFilterStatus()
    {
        $this->loadOrderItems();
    }

    public function selectItem($itemId)
    {
        $this->dispatch('showOrderItemDetails', $itemId);
    }

    public function close()
    {
        $this->isVisible = false;
    }

    public function render()
    {
        return view('livewire.components.order-item-sidebar');
    }
}
