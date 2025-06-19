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

    'user_model' => [
        'model' => config('auth.providers.users.model', \App\Models\User::class),
        'foreign_key' => 'user_id', // Foreign key in comments table
        'primary_key' => 'id',      // Primary key in users table
        'display_name' => 'name',   // Column to use for displaying user name
        'searchable_fields' => [    // Fields to search when looking for users to mention
            'name',
            'username',
            'email',
        ],
        'select_fields' => [        // Fields to select when fetching users
            'id',
            'name',
            'username',
            'email',
        ],
        'avatar_field' => 'avatar', // Field for user avatar (if exists)
    ],
];
