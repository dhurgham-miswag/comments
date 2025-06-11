<?php

namespace DhurghamMiswag\Comments\Livewire;

use DhurghamMiswag\Comments\Models\Comment;
use DhurghamMiswag\Comments\Services\CommentService;
use Livewire\Component;

class Comments extends Component
{
    public $comment = '';

    public $replying_to = null;

    public $reply_text = '';

    public $comments;

    public $model_type;

    public $model_id;

    public $can_reply;

    public $can_show_commentor_name;

    protected $listeners = ['refreshComments' => '$refresh'];

    public function mount($model_type, $model_id)
    {
        $this->model_type = $model_type;
        $this->model_id = $model_id;
        $this->can_reply = config('comments.can_reply', true);
        $this->can_show_commentor_name = config('comments.can_show_commentor_name', true);
        $this->load_comments();
    }

    public function load_comments()
    {
        $this->comments = Comment::get_root_comments_with_replies($this->model_type, $this->model_id);
    }

    public function add_comment()
    {
        $this->validate([
            'comment' => 'required|min:'.config('comments.validation.min_length'),
        ]);

        $comment_service = app(CommentService::class);
        $comment_service->create([
            'comment' => $this->comment,
            'user_id' => auth()->id(),
            'commentable_type' => $this->model_type,
            'commentable_id' => $this->model_id,
        ]);

        $this->comment = '';
        $this->load_comments();
        $this->dispatch('refreshComments');
    }

    public function start_reply($comment_id)
    {
        if (! $this->can_reply) {
            return;
        }

        $this->replying_to = $comment_id;
        $this->reply_text = '';
    }

    public function add_reply()
    {
        if (! $this->can_reply) {
            return;
        }

        $this->validate([
            'reply_text' => 'required|min:'.config('comments.validation.min_length'),
        ]);

        $comment_service = app(CommentService::class);
        $comment_service->create([
            'comment' => $this->reply_text,
            'user_id' => auth()->id(),
            'parent_id' => $this->replying_to,
            'commentable_type' => $this->model_type,
            'commentable_id' => $this->model_id,
        ]);

        $this->replying_to = null;
        $this->reply_text = '';
        $this->load_comments();
        $this->dispatch('refreshComments');
    }

    public function render()
    {
        return view('comments::livewire.comments');
    }
}
