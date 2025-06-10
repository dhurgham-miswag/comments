<?php

namespace DhurghamMiswag\Comments\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    protected $fillable = [
        'comment',
        'user_id',
        'parent_id',
    ];

    protected $with = ['user'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        $userModel = config('auth.providers.users.model');

        return $this->belongsTo($userModel);
    }

    /**
     * Get the parent comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the replies for the comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Scope a query to only include root comments.
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to only include replies.
     */
    public function scopeReplies(Builder $query): Builder
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Scope a query to include replies with their users.
     */
    public function scopeWithReplies(Builder $query): Builder
    {
        return $query->with(['replies.user']);
    }

    /**
     * Check if the comment is a reply.
     */
    public function isReply(): bool
    {
        return ! is_null($this->parent_id);
    }

    /**
     * Check if the comment has replies.
     */
    public function hasReplies(): bool
    {
        return $this->replies()->exists();
    }

    /**
     * Get all root comments with their replies.
     */
    public static function getRootCommentsWithReplies()
    {
        dd(static::root()
            ->with(['user', 'replies.user'])
            ->latest()
            ->get());

        return static::root()
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();
    }

    /**
     * Get all replies for a comment.
     */
    public static function getRepliesForComment(int $commentId)
    {
        return static::where('parent_id', $commentId)
            ->with('user')
            ->latest()
            ->get();
    }
}
