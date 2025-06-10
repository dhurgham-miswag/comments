<?php

namespace Herd\Comments\Services;

use Herd\Comments\Models\Comment;
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
     * Update an existing comment.
     */
    public function update(int $commentId, array $data): bool
    {
        return DB::transaction(function () use ($commentId, $data) {
            $comment = Comment::findOrFail($commentId);

            // Check authorization
            if ($comment->user_id !== Auth::id()) {
                throw new \Exception('Unauthorized to update this comment');
            }

            $updated = $comment->update($data);

            // You can add additional logic here, such as:
            // - Notifications
            // - Events
            // - Activity logging
            // - Cache updates

            return $updated;
        });
    }

    /**
     * Delete a comment and its replies.
     */
    public function delete(int $commentId): bool
    {
        return DB::transaction(function () use ($commentId) {
            $comment = Comment::findOrFail($commentId);

            // Check authorization
            if ($comment->user_id !== Auth::id()) {
                throw new \Exception('Unauthorized to delete this comment');
            }

            // Delete all replies first
            $comment->replies()->delete();

            // Delete the comment
            $deleted = $comment->delete();

            // You can add additional logic here, such as:
            // - Notifications
            // - Events
            // - Activity logging
            // - Cache updates

            return $deleted;
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
