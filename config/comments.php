<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Comments Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the comments package settings.
    |
    */

    // Comment validation settings
    'validation' => [
        // Minimum length required for a comment
        'min_length' => 2,
    ],

    // Whether to allow replies to comments
    'can_reply' => true,

    // Whether to show commentor's name
    'can_show_commentor_name' => true,

    // Old comments system configuration
    'old_comments' => [
        'enabled' => false, // Enable or disable fetching old comments
        'table_name' => 'old_comments', // Name of the old comments table
        'record_id' => 'id', // Column name for the incremental ID
        'model_id_column' => 'model_id', // Column name for the related model's ID
        'comment_column' => 'comment', // Column name for the comment text
        'created_at_column' => 'created_at', // Column name for the comment's creation date
    ],

    'user_model' => [
        'model' => config('auth.providers.users.model', \App\Models\User::class),
        'foreign_key' => 'user_id', // Foreign key in comments table
        'primary_key' => 'id',      // Primary key in users table
    ],
];
