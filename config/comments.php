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

    // The model that will be used for comments
    'model' => \Herd\Comments\Models\Comment::class,

    // The user model that will be used for comment authors
    'user_model' => \App\Models\User::class,

    // The number of comments to show per page
    'per_page' => 10,

    // Whether to allow nested replies
    'allow_replies' => true,

    // Maximum depth for nested replies (null for unlimited)
    'max_reply_depth' => null,

    // Whether to require authentication for commenting
    'require_auth' => true,

    // Whether to allow guest comments
    'allow_guest_comments' => false,

    // Whether to moderate comments before they appear
    'moderate_comments' => false,

    // The view to use for the comments component
    'view' => 'comments::livewire.comments',
];
