<?php

namespace App\Observers;

use App\Events\OrderStatusUpdated;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        if ($order->wasChanged('order_status')) {
            $previousStatus = $order->getOriginal('order_status');

            event(new OrderStatusUpdated($order, $previousStatus));
        }
    }
}
