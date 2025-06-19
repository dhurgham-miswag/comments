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

    /**
     * Users found for mentions
     */
    public $users = [];

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
        $this->comments = Comment::getRootCommentsWithReplies($this->model_type, $this->model_id);
    }

    /**
     * Search users for mentions
     */
    public function search_users($search)
    {
        if (strlen($search) >= 2) {
            $user_model = config('comments.user_model.model');
            $query = $user_model::query();

            // Get searchable fields from config
            $searchable_fields = config('comments.user_model.searchable_fields', ['name']);

            // Build the search query
            $query->where(function ($q) use ($searchable_fields, $search) {
                foreach ($searchable_fields as $index => $field) {
                    if ($index === 0) {
                        $q->where($field, 'like', "%{$search}%");
                    } else {
                        $q->orWhere($field, 'like', "%{$search}%");
                    }
                }
            });

            // Get select fields from config
            $select_fields = config('comments.user_model.select_fields', ['id', 'name']);

            // Get the results
            $this->users = $query->limit(5)
                ->get($select_fields)
                ->map(function ($user) {
                    $display_name = $user->{config('comments.user_model.display_name', 'name')};
                    $primary_key = $user->{config('comments.user_model.primary_key', 'id')};

                    return [
                        'id' => $primary_key,
                        'name' => $display_name,
                    ];
                })
                ->toArray();
        } else {
            $this->users = [];
        }
    }

    /**
     * Format the comment text to handle mentions
     */
    protected function format_comment($text)
    {
        // Convert markdown-style mentions to HTML
        return preg_replace('/\*\*@([^*]+)\*\*/', '<strong>@$1</strong>', $text);
    }

    /**
     * Add a comment with formatted mentions
     */
    public function add_comment()
    {
        $this->validate([
            'comment' => 'required|min:2',
        ]);

        $formatted_comment = $this->format_comment($this->comment);

        $comment_service = app(CommentService::class);
        $comment_service->create([
            'comment' => $formatted_comment,
            'user_id' => auth()->id(),
            'commentable_type' => $this->model_type,
            'commentable_id' => $this->model_id,
        ]);

        $this->comment = '';
        $this->users = [];
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

    /**
     * Add a reply with formatted mentions
     */
    public function add_reply()
    {
        $this->validate([
            'reply_text' => 'required|min:2',
        ]);

        $formatted_reply = $this->format_comment($this->reply_text);

        $comment_service = app(CommentService::class);
        $comment_service->create([
            'comment' => $formatted_reply,
            'user_id' => auth()->id(),
            'parent_id' => $this->replying_to,
            'commentable_type' => $this->model_type,
            'commentable_id' => $this->model_id,
        ]);

        $this->reply_text = '';
        $this->replying_to = null;
        $this->users = [];
        $this->load_comments();
        $this->dispatch('refreshComments');
    }

    public function render()
    {
        return view('comments::livewire.comments');
    }
}
