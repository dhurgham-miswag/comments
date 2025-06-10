<?php

namespace DhurghamMiswag\Comments\Livewire;

use DhurghamMiswag\Comments\Models\Comment;
use DhurghamMiswag\Comments\Services\CommentService;
use Livewire\Component;

class Comments extends Component
{
    public $comment = '';

    public $replyingTo = null;

    public $replyText = '';

    public $comments;

    public $modelType;

    public $modelId;

    public $canReply;

    protected $listeners = ['refreshComments' => '$refresh'];

    public function mount($modelType, $modelId)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->canReply = config('comments.can_reply', true);
        $this->loadComments();
    }

    public function loadComments()
    {
        $this->comments = Comment::getRootCommentsWithReplies($this->modelType, $this->modelId);
    }

    public function addComment()
    {
        $this->validate([
            'comment' => 'required|min:' . config('comments.validation.min_length'),
        ]);

        $commentService = app(CommentService::class);
        $commentService->create([
            'comment' => $this->comment,
            'user_id' => auth()->id(),
            'commentable_type' => $this->modelType,
            'commentable_id' => $this->modelId,
        ]);

        $this->comment = '';
        $this->loadComments();
        $this->dispatch('commentAdded');
    }

    public function startReply($commentId)
    {
        if (!$this->canReply) {
            return;
        }

        $this->replyingTo = $commentId;
        $this->replyText = '';
    }

    public function addReply()
    {
        if (!$this->canReply) {
            return;
        }

        $this->validate([
            'replyText' => 'required|min:' . config('comments.validation.min_length'),
        ]);

        $commentService = app(CommentService::class);
        $commentService->create([
            'comment' => $this->replyText,
            'user_id' => auth()->id(),
            'parent_id' => $this->replyingTo,
            'commentable_type' => $this->modelType,
            'commentable_id' => $this->modelId,
        ]);

        $this->replyingTo = null;
        $this->replyText = '';
        $this->loadComments();
        $this->dispatch('replyAdded');
    }

    public function render()
    {
        return view('comments::livewire.comments');
    }
}
