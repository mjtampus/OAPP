<?php

namespace App\Notifications;

use Log;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class CommentLikedNotification extends Notification implements ShouldBroadcastNow
{
    use Queueable;

    protected $liker;
    protected $commentId;

    /**
     * Create a new notification instance.
     */
    public function __construct($liker, $commentId)
    {
        $this->liker = $liker;
        $this->commentId = $commentId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'liker_id' => $this->liker->id,
            'commentId' => $this->commentId
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
