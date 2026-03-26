<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $orderId;
    public string $orderNumber;
    public string $status;
    public ?string $previousStatus;
    public string $updatedAt;

    protected int $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(Order $order, ?string $previousStatus = null)
    {
        $this->orderId = $order->id;
        $this->orderNumber = $order->order_number;
        $this->status = $order->order_status;
        $this->previousStatus = $previousStatus;
        $this->updatedAt = optional($order->updated_at)->toDateTimeString() ?? now()->toDateTimeString();
        $this->userId = $order->user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('App.Models.User.' . $this->userId);
    }

    public function broadcastAs(): string
    {
        return 'OrderStatusUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->orderId,
            'order_number' => $this->orderNumber,
            'status' => $this->status,
            'previous_status' => $this->previousStatus,
            'updated_at' => $this->updatedAt,
        ];
    }
}
