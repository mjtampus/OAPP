<?php

namespace App\Livewire\Components\PagesComponents;

use Dom\Comment;
use App\Models\Replies;
use Livewire\Component;
use App\Models\Comments;
use App\Models\CommentLikes;
use App\Events\CommentLikedEvent;
use App\Notifications\CommentLikedNotification;
// use App\Notifications\CommentLikedNotification;

class CommentSection extends Component
{
    public $newComment = '';
    public $productId;
    public $replyComment = '';
    public $replyingTo = '';
    public $comments;
    public $likes;

    public function mount($productId)
    {
        $this->productId = $productId;
        $this->refreshComments();        
    }

    public function ensureAuthenticated()
    {
        if (!auth()->check()) {
            return redirect(route('login'));
        }
    }

    public function refreshComments()
    {
        $this->comments = Comments::where('products_id', $this->productId)->with('user' , 'replies.user', 'likes')->get()->toArray();
        // dd($this->comments);
    }
    
    public function startReply($commentId)
    {
        $this->replyingTo = $commentId;
        $this->replyComment = '';
    }
    
    public function cancelReply()
    {
        $this->replyingTo = null;
    }
    
    public function addComment()
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        if (empty($this->newComment)) {
            return;
        }
        
         comments::create([
            'products_id' => $this->productId,
            'user_id' => auth()->user()->id,
            'comment' => $this->newComment,
        ]);

        $this->newComment = ''; // Clear the input

        $this->refreshComments();
        
        session()->flash('message', 'Comment added successfully!');
    }
    
    public function addReply($commentId)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        if (empty($this->replyComment)) {
            return;
        }
        
        // Find the comment to add reply to
        foreach ($this->comments as $key => $comment) {
            if ($comment['id'] == $commentId) {
                $newReply = Replies::create([
                    'comments_id' => $commentId,
                    'user_id' => auth()->user()->id,
                    'comment' => $this->replyComment,
                ]);
                
                $this->comments[$key]['replies'][] = $newReply;
                break;
            }
        }
        
        $this->replyingTo = null;
        $this->replyComment = '';
        
        session()->flash('message', 'Reply added successfully!');
    }

    public function getUserLikedComment($commentId)
    {
        $comment = Comments::find($commentId);

        return $comment->user;
    }
    
    public function likeComment($commentId)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $comment = CommentLikes::where('comments_id', $commentId)->where('user_id', auth()->user()->id)->first();

        if (!$comment) {
            CommentLikes::create([
                'comments_id' => $commentId,
                'user_id' => auth()->user()->id,
                'like_status' => 'like',
            ]);

            $fetchLikedComment = $this->getUserLikedComment($commentId);
            $liker = auth()->user();

            if ($fetchLikedComment->id == auth()->user()->id) {
                
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'You like your own comment',
                ]);    
            }else {
                event(new CommentLikedEvent($liker, $fetchLikedComment->id, $commentId));
                $this->dispatch('notify', [
                    'type' => 'success',
                    'message' => 'You like ' . $fetchLikedComment->name . ' comment',
                ]);
            }

        }
        else {
            CommentLikes::where('comments_id', $commentId)->where('user_id', auth()->user()->id)->delete();
        }
 
        $this->refreshComments();
    }

    public function likeReply($replyId)
    {
        if ($redirect = $this->ensureAuthenticated()) {
            return $redirect;
        }

        $reply = Replies::find($replyId);

        if ($reply->is_liked == false) {
            $reply->update([
                'is_liked' => true,
            ]);
        } else {
            $reply->update([
                'is_liked' => false,
            ]);
        }
        $this->refreshComments();

    }
    public function render()
    {
        return view('livewire.components.pages-components.comment-section');
    }
}
