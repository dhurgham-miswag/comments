<?php

namespace Herd\Comments\Livewire;

use Herd\Comments\Models\Comment;
use Herd\Comments\Services\CommentService;
use Livewire\Component;

class Comments extends Component
{
    public $comment = '';

    public $editingComment = null;

    public $editingText = '';

    public $replyingTo = null;

    public $replyText = '';

    public $comments;

    protected $listeners = ['refreshComments' => '$refresh'];

    public function mount()
    {
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = Comment::getRootCommentsWithReplies();
    }

    public function addComment()
    {
        $this->validate([
            'comment' => 'required|min:3',
        ]);

        $commentService = app(CommentService::class);
        $commentService->create([
            'comment' => $this->comment,
            'user_id' => auth()->id(),
        ]);

        $this->comment = '';
        $this->loadComments();
        $this->dispatch('commentAdded');
    }

    public function startReply($commentId)
    {
        $this->replyingTo = $commentId;
        $this->replyText = '';
    }

    public function addReply()
    {
        $this->validate([
            'replyText' => 'required|min:3',
        ]);

        $commentService = app(CommentService::class);
        $commentService->create([
            'comment' => $this->replyText,
            'user_id' => auth()->id(),
            'parent_id' => $this->replyingTo,
        ]);

        $this->replyingTo = null;
        $this->replyText = '';
        $this->loadComments();
        $this->emit('replyAdded');
    }

    public function startEdit($commentId)
    {
        $comment = Comment::find($commentId);
        $this->editingComment = $commentId;
        $this->editingText = $comment->comment;
    }

    public function updateComment()
    {
        $this->validate([
            'editingText' => 'required|min:3',
        ]);

        $commentService = app(CommentService::class);
        $commentService->update($this->editingComment, [
            'comment' => $this->editingText,
        ]);

        $this->editingComment = null;
        $this->editingText = '';
        $this->loadComments();
        $this->emit('commentUpdated');
    }

    public function deleteComment($commentId)
    {
        $commentService = app(CommentService::class);
        $commentService->delete($commentId);

        $this->loadComments();
        $this->dispatch('commentAdded');
    }

    public function render()
    {
        return view('comments::livewire.comments');
    }
}
