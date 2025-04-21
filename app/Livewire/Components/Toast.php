<?php

namespace App\Livewire\Components;


use Livewire\Component;

class Toast extends Component
{
    public $messages = [];
    public $showToast = true;
    public int $authId;
    
    public function mount()
    {
        $this->authId = auth()->id() ?? 0;
    }
    public function getListeners()
    {
        return [
            'notify' => 'addMessage',
            "echo-private:App.Models.User.{$this->authId},CommentLikedEvent" => 'handleCommentLiked',
        ];
    }
    public function handleCommentLiked($payload)
    {
        logger('🔥 Comment Liked Payload', $payload);
    
        $this->addMessage([
            'message' => "{$payload['liker_name']} liked your comment",
            'type' => 'success',
            'duration' => 4000,
        ]);
    }
    public function addMessage($data)
    {
        $this->messages[] = [
            'id' => uniqid(),
            'message' => (string) ($data['message'] ?? ''), // Ensure string
            'type' => $data['type'] ?? 'success',
            'duration' => $data['duration'] ?? 3000
        ];
        
        $this->showToast = true;
    }

    public function removeMessage($messageId)
    {
        $this->messages = array_filter($this->messages, function($message) use ($messageId) {
            return $message['id'] !== $messageId;
        });
        
        if (empty($this->messages)) {
            $this->showToast = false;
        }
    }

    public function render()
    {
        return view('livewire.components.toast');
    }
}