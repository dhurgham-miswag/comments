<?php

namespace DhurghamMiswag\Comments\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    protected $fillable = [
        'comment',
        'user_id',
        'parent_id',
        'commentable_type',
        'commentable_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        $user_model = config('comments.user_model.model');
        $foreign_key = config('comments.user_model.foreign_key', 'user_id');
        $primary_key = config('comments.user_model.primary_key', 'id');

        return $this->belongsTo($user_model, $foreign_key, $primary_key);
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
     * Get the model that owns the comment.
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include root comments.
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to filter comments for a specific model.
     */
    public function scopeForModel(Builder $query, string $model_type, int $model_id): Builder
    {
        return $query->where('commentable_type', $model_type)
            ->where('commentable_id', $model_id);
    }

    /**
     * Get all root comments with their replies for a specific model.
     */
    public static function getRootCommentsWithReplies(string $model_type, int $model_id)
    {
        return static::root()
            ->forModel($model_type, $model_id)
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();
    }
}
