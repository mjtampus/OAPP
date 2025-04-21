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

class CommentLikedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $liker;
    public $commentOwnerId;
    public $commentId;
    public function __construct($liker, $commentOwnerId, $commentId)
    {
        $this->liker = $liker;
        $this->commentOwnerId = $commentOwnerId;
        $this->commentId = $commentId;
    }
    public function broadcastOn()
    {
        return new PrivateChannel("App.Models.User.{$this->commentOwnerId}");
    }
    public function broadcastWith()
    {
        return [
            'liker_name' => $this->liker->name,
            'comment_id' => $this->commentId,
        ];
    }
}
