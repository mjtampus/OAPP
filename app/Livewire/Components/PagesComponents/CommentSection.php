<?php

namespace App\Livewire\Components\PagesComponents;

use Dom\Comment;
use App\Models\Replies;
use Livewire\Component;
use App\Models\Comments;
use App\Events\ReplyEvent;
use App\Models\CommentLikes;
use App\Events\CommentEvents;
use App\Events\CommentLikedEvent;
use Illuminate\Support\Facades\Auth;
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
    public int $authId ;
    public $expandedComments = [];

    public $editingCommentId = null;
    public $editingReplyId = null;
    public $editCommentText;
    public $editReplyText;
    

    public function getListeners()
    {   
        $this->authId = auth()->id() ?? 0;
        return [
        "echo-private:App.Models.User.{$this->authId},CommentLikedEvent" => 'refreshComments',
        "echo-private:App.Models.User.{$this->authId},ReplyEvent" => 'refreshComments',
        "echo:CommentRefresh,CommentEvents" => 'refreshComments',
        'confirmedDelete','ConfirmDeleteReply',
        ];
    }

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
        event(new CommentEvents());
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
        event(new CommentEvents());
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
 
        event(new CommentEvents());
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
        event(new CommentEvents());

    }
    public function expandReplies($commentId)
    {
        $this->expandedComments[$commentId] = true;
    }
    
    // Method to collapse replies for a comment
    public function collapseReplies($commentId)
    {
        $this->expandedComments[$commentId] = false;
    }

    public function startEditingComment($commentId)
    {
        $comment = Comments::findOrFail($commentId);
        
        // Check if the user owns this comment
        if ($comment->user_id !== Auth::id()) {
            return;
        }
        
        $this->editingCommentId = $commentId;
        $this->editCommentText = $comment->comment;
        
        // Reset any other editing state
        $this->editingReplyId = null;
        $this->replyingTo = null;
    }
    
    // Start editing a reply
    public function startEditingReply($replyId)
    {
        $reply = Replies::findOrFail($replyId);
        
        // Check if the user owns this reply
        if ($reply->user_id !== Auth::id()) {
            return;
        }
        
        $this->editingReplyId = $replyId;
        $this->editReplyText = $reply->comment;
        
        // Reset any other editing state
        $this->editingCommentId = null;
        $this->replyingTo = null;
    }
    
    // Cancel editing
    public function cancelEditing()
    {
        $this->reset(['editingCommentId', 'editingReplyId', 'editCommentText', 'editReplyText']);
    }
    
    // Update comment
    public function updateComment($commentId)
    {
        $comment = Comments::findOrFail($commentId);
        
        // Check if the user owns this comment
        if ($comment->user_id !== Auth::id()) {
            return;
        }
        
        $this->validate([
            'editCommentText' => 'required|min:2',
        ]);
        
        $comment->update([
            'comment' => $this->editCommentText,
        ]);
        
        $this->cancelEditing();
        $this->refreshComments();
        session()->flash('message', 'Comment updated successfully!');
    }
    
    // Update reply
    public function updateReply($replyId)
    {
        $reply = Replies::findOrFail($replyId);
        
        // Check if the user owns this reply
        if ($reply->user_id !== Auth::id()) {
            return;
        }
        
        $this->validate([
            'editReplyText' => 'required|min:2',
        ]);
        
        $reply->update([
            'comment' => $this->editReplyText,
        ]);
        
        $this->cancelEditing();
        $this->refreshComments();

        session()->flash('message', 'Reply updated successfully!');
    }
    
    // Delete comment
    public function deleteComment($commentId)
    {
        if ($commentId) {
            $this->dispatch('openModal', 'Are you sure you want to delete this comment?', 'confirmedDelete', $commentId);
        }
    }

    public function confirmedDelete($commentId)
    {
        $comment = Comments::findOrFail($commentId);

        // Check if the user owns this comment
        if ($comment->user_id !== Auth::id()) {
            return;
        }

        // Delete the comment
        $comment->delete();
        $this->refreshComments();
        session()->flash('message', 'Comment deleted successfully!');
    }
    
    // Delete reply
    public function deleteReply($replyId)
    {
        if ($replyId) {
            $this->dispatch('openModal', 'Are you sure you want to delete this reply?', 'ConfirmDeleteReply', $replyId);
        }
    }
    public function ConfirmDeleteReply($replyId)
    {
        $reply = Replies::findOrFail($replyId);
        
        // Check if the user owns this reply
        if ($reply->user_id !== Auth::id()) {
            return;
        }
        
        // Delete the reply
        $reply->delete();
        $this->refreshComments();
        session()->flash('message', 'Reply deleted successfully!');
    }

    public function render()
    {
        return view('livewire.components.pages-components.comment-section');
    }
}
