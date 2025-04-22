<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class ReplyEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $commenter;
    public $commentOwnerId;
    public $commentId;

    /**
     * Create a new event instance.
     */
    public function __construct($commenter, $commentOwnerId, $commentId)
    {
        $this->commenter = $commenter;
        $this->commentOwnerId = $commentOwnerId;
        $this->commentId = $commentId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("App.Models.User.{$this->commentOwnerId}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'commenter_name' => $this->commenter->name,
            'comment_id' => $this->commentId,
        ];
    }
}
