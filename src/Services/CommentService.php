<?php

namespace DhurghamMiswag\Comments\Services;

use DhurghamMiswag\Comments\Models\Comment;
use Illuminate\Support\Facades\Auth;
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

            // You can add additional logic here, such as:
            // - Notifications
            // - Events
            // - Activity logging
            // - Cache updates

            return $comment;
        });
    }


    /**
     * Get all root comments with their replies.
     */
    public function getRootCommentsWithReplies()
    {
        return Comment::getRootCommentsWithReplies();
    }

    /**
     * Get all replies for a comment.
     */
    public function getRepliesForComment(int $commentId)
    {
        return Comment::getRepliesForComment($commentId);
    }

    /**
     * Get a single comment with its replies.
     */
    public function getCommentWithReplies(int $commentId)
    {
        return Comment::with('replies.user')
            ->findOrFail($commentId);
    }

    /**
     * Get comments for a specific user.
     */
    public function getUserComments(int $userId)
    {
        return Comment::where('user_id', $userId)
            ->with('replies')
            ->latest()
            ->get();
    }
}
