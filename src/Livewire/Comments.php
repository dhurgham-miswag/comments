<?php

namespace DhurghamMiswag\Comments\Livewire;

use DhurghamMiswag\Comments\Models\Comment;
use DhurghamMiswag\Comments\Services\CommentService;
use Livewire\Component;
use Filament\Forms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class Comments extends Component implements HasForms
{
    use InteractsWithForms;

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

    public $formData = [];

    public $reply_form;

    protected $listeners = ['refreshComments' => '$refresh'];

    public function mount($model_type, $model_id)
    {
        $this->model_type = $model_type;
        $this->model_id = $model_id;
        $this->can_reply = config('comments.can_reply', true);
        $this->can_show_commentor_name = config('comments.can_show_commentor_name', true);
        $this->load_comments();
        $this->form->fill();
    }

    public function load_comments()
    {
        $this->comments = Comment::getRootCommentsWithReplies($this->model_type, $this->model_id);
    }


    protected function getFormSchema(): array
    {
        return [
            Forms\Components\RichEditor::make('comment')
                ->toolbarButtons(['bold', 'italic', 'link', 'mention'])
                ->required()
                ->columnSpanFull(),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();
        $comment_service = app(CommentService::class);
        $comment_service->create([
            'comment' => $data['comment'],
            'user_id' => auth()->id(),
            'commentable_type' => $this->model_type,
            'commentable_id' => $this->model_id,
        ]);
        $this->form->fill(['comment' => '']);
        $this->load_comments();
        $this->dispatch('refreshComments');
    }

    public function start_reply($comment_id)
    {
        if (! $this->can_reply) {
            return;
        }

        $this->replying_to = $comment_id;
        $this->reply_form = [ 'reply_text' => '' ];
    }

    public function add_reply()
    {
        $data = $this->getReplyForm()->getState();
        $comment_service = app(\DhurghamMiswag\Comments\Services\CommentService::class);
        $comment_service->create([
            'comment' => $data['reply_text'],
            'user_id' => auth()->id(),
            'parent_id' => $this->replying_to,
            'commentable_type' => $this->model_type,
            'commentable_id' => $this->model_id,
        ]);
        $this->reply_form = [ 'reply_text' => '' ];
        $this->replying_to = null;
        $this->load_comments();
        $this->dispatch('refreshComments');
    }

    public function getReplyForm()
    {
        return $this->makeForm()
            ->schema($this->getFormSchema())
            ->statePath('reply_form');
    }

    public function render()
    {
        return view('comments::livewire.comments');
    }
}
