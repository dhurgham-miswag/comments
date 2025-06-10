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
        'min_length' => 3,
    ],

    // Whether to allow replies to comments
    'can_reply' => true,

    'user_model' => [
        'p_key' => 'user_id', // primary key in user model
        'f_key' => 'user_id', // foreign key in comments table
    ],
];
