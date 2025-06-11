<?php

namespace DhurghamMiswag\Comments\Services;

use DhurghamMiswag\Comments\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentService
{
    /**
     * Create a new comment.
     */
    public function create(array $data): Comment
    {
        return DB::transaction(function () use ($data) {
            $comment = Comment::create($data);

            // ToDo: can add additional logic here

            return $comment;
        });
    }
}
