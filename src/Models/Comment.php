<?php

namespace DhurghamMiswag\Comments\Models;

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
        $user_model = config('auth.providers.users.model');
        $user_config = config('comments.user_model', [
            'f_key' => 'user_id',
            'p_key' => 'user_id',
        ]);

        return $this->belongsTo($user_model, $user_config['f_key'], $user_config['p_key']);
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
     * Get all root comments with their replies for a specific model.
     */
    public static function get_root_comments_with_replies(string $model_type, int $model_id)
    {
        return static::root()
            ->for_model($model_type, $model_id)
            ->with(['user', 'replies.user'])
            ->latest()
            ->get();
    }
}
